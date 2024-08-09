<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Models\Audit;
use App\Models\AuditObjection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            ->where('status', '>=', Audit::AUDIT_STATUS_AUDITOR_ADDED_OBJECTION)
            ->where('department_id', $authUser->department_id)
            ->latest()
            ->get();

        return view('admin.compliance-audits')->with(['audits' => $audits]);
    }

    public function complianceInfo(Request $request, Audit $audit)
    {
        $audit->load(['objections' => fn($q) => $q->whereNotNull('user_id')->with([
            'auditorApprover' => fn($q) => $q->first()?->append('full_name')
        ])]);

        $innerHtml = '
                <div class="mb-3 row">
                    <div class="col-md-6 mt-3">
                        <label class="col-form-label" for="hmm_no">HMM No.</label>
                        <input name="hmm_no" class="form-control" value="' . $audit->audit_no . '" readonly type="text">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="col-form-label" for="date">Date.</label>
                        <input name="date" class="form-control" readonly type="date" value="' . $audit->date . '">
                    </div>
                </div>';

        foreach ($audit->objections as $key => $objection) {
            $isEditable = in_array($objection->status, [2, 4]) ? 'readonly' : '';

            $innerHtml .= '
                <div class="row custm-card">
                    <input type="hidden" name="objection_id[]" value="' . $objection->id . '">
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="objection_' . $key . '">(Objection ' . $objection->objection_no . ')</label>
                        <textarea name="objection_' . $key . '" id="objection_' . $key . '" class="form-control" readonly cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->objection . '</textarea>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="compliance_' . $key . '">Compliance <span class="text-danger">*</span></label>
                        <textarea name="compliance_' . $key . '" ' . $isEditable . ' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->answer . '</textarea>
                        <span class="text-danger is-invalid compliance_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="remark_' . $key . '">Remark <span class="text-danger">*</span></label>
                        <input type="text" name="remark_' . $key . '" ' . $isEditable . ' value="' . $objection->remark . '" class="form-control">
                        <span class="text-danger is-invalid remark_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="status_' . $key . '">Status</label>
                        <input type="text" name="status_' . $key . '" class="form-control" value="' . $objection?->status_name . '" readonly>
                    </div>
                    <div class="col-md-2"></div>

                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">Auditor Action</label>
                        <select name="action_' . $key . '" readonly class="form-control">
                            <option value="">Action</option>
                            <option value="1" ' . ($objection->auditor_action_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($objection->auditor_action_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid auditor_action_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="auditor_action_remark_' . $key . '">Auditor Remark</label>
                        <textarea name="auditor_action_remark_' . $key . '" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->auditor_remark . '</textarea>
                        <span class="text-danger is-invalid auditor_action_remark_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="mca_action_' . $key . '">MCA Action</label>
                        <select name="mca_action_' . $key . '" readonly class="form-control">
                            <option value="">Action</option>
                            <option value="1" ' . ($objection->mca_action_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($objection->mca_action_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid mca_action_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="mca_action_remark_' . $key . '">MCA Remark</label>
                        <textarea name="mca_action_remark_' . $key . '" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->mca_remark . '</textarea>
                        <span class="text-danger is-invalid mca_action_' . $key . '_err"></span>
                    </div>
                </div>';
        }


        $response = [
            'result' => 1,
            'innerHtml' => $innerHtml,
            'audit' => $audit,
        ];

        return $response;
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
