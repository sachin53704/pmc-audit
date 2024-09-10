<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\Audit;
use App\Models\AuditObjection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\AuditParaCategory;
use App\Models\Severity;
use App\Models\AuditType;
use App\Models\FiscalYear;
use App\Models\Zone;
use App\Models\Department;

class DepartmentAuditController extends Controller
{

    public function index(Request $request)
    {
        $authUser = Auth::user();
        $audits = Audit::query()
            ->where('status', '>=', Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT)
            ->where('department_id', $authUser->department_id)
            ->latest()
            ->get();

        return view('admin.department-letters')->with(['audits' => $audits]);
    }

    public function createCompliance()
    {
        $authUser = Auth::user();
        $audits = Audit::query()
            ->whereHas('objections', function ($q) {
                $q->where('mca_action_status', 2);
            })
            ->where('department_id', $authUser->department_id)
            ->latest()
            ->get();

        $departments = Department::where('is_audit', 1)->select('id', 'name')->get();

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('admin.compliance-audits')->with([
            'audits' => $audits,
            'departments' => $departments,
            'zones' => $zones,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }

    public function complianceInfo(Request $request, Audit $audit)
    {
        $audit->load(['objections' => fn($q) => $q->whereNotNull('user_id')->with([
            'user',
            'auditorApprover' => fn($q) => $q->first()?->append('full_name')
        ])]);

        return "";
    }

    public function updateCompliance(Request $request, Audit $audit)
    {
        $fieldArray['objection_id'] = 'required';
        $fieldArray['date'] = 'required';
        $fieldArray['hmm_no'] = 'required';
        $messageArray['objection_id.required'] = 'Objection no not found';
        $messageArray['date.required'] = 'Please enter date';
        $messageArray['hmm_no.required'] = 'HMM No is missing';

        for ($i = 0; $i < count($request->objection_id); $i++) {
            if ($request->{'compliance_' . $i} != null && $request->{'compliance_' . $i} != '') {
                $fieldArray['objection_' . $i] = 'required';
                $fieldArray['compliance_' . $i] = 'required';
                $fieldArray['remark_' . $i] = 'required';
                $messageArray['objection_' . $i . '.required'] = 'Please type objection';
                $messageArray['compliance_' . $i . '.required'] = 'Please type compliance';
                $messageArray['remark_' . $i . '.required'] = 'Please type remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try {
            DB::beginTransaction();
            $audit->update(['status' => Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE]);

            for ($i = 0; $i < count($request->objection_id); $i++) {
                $compParamName = 'compliance_' . $i;
                $remParamName = 'remark_' . $i;
                AuditObjection::where(['id' => $request->objection_id[$i]])
                    ->update([
                        'answer' => $request->{$compParamName},
                        'remark' => $request->{$remParamName},
                        'answered_by' => Auth::user()->id,
                        'auditor_action_status' => 0,
                        // 'auditor_remark' => null,
                        'mca_action_status' => 0,
                        // 'mca_remark' => null,
                    ]);
            }
            DB::commit();

            return response()->json(['success' => 'Compliance updated successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'compliance');
        }
    }
}
