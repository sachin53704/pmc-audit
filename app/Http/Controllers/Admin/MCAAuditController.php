<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\AssignAuditorRequest;
use App\Models\Audit;
use App\Models\AuditObjection;
use App\Models\User;
use App\Models\UserAssignedAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MCAAuditController extends Controller
{
    public function statusWiseAuditList(Request $request, $status = 'pending')
    {
        $statusCode = strtoupper('AUDIT_STATUS_' . $status);
        $statusCode = constant("App\Models\Audit::$statusCode");

        // $audits = Audit::query()
        //     ->withCount('assignedAuditors as assigned_auditors_count')
        //     // ->where('department_id', Auth::user()->department_id)
        //     ->when($statusCode == 2, fn ($q) => $q->where('status', 2)->orWhere('status', '>=', 4))
        //     ->when($statusCode != 2, fn ($q) => $q->where('status', $statusCode))
        //     ->latest()
        //     ->get();
        // return $statusCode;
        $audits = Audit::query()
            ->withCount('assignedAuditors as assigned_auditors_count')
            // ->where('department_id', Auth::user()->department_id)
            ->when(Auth::user()->hasRole('DY MCA'), function ($q) use ($statusCode) {
                $q->where('dymca_status', $statusCode);
            })->when(Auth::user()->hasRole('MCA'), function ($q) use ($statusCode) {
                $q->where('mca_status', $statusCode);
            })
            ->latest()
            ->get();

        return view('admin.audit-list')->with(['status' => $status, 'audits' => $audits]);
    }


    public function auditStatusChange(Audit $audit, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required_if:action,reject',
        ], [
            'reject_reason.required_if' => 'Please enter reject reason',
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        $action = $request->action;
        if ($action == "reject") {
            if (Auth::user()->hasRole('DY MCA')) {
                $audit->update([
                    'dymca_status' => 3,
                    'dymca_remark' => $request->reject_reason,
                ]);
            } elseif (Auth::user()->hasRole('MCA')) {
                $audit->update([
                    'mca_status' => 3,
                    'mca_remark' => $request->reject_reason,
                    'status' => Audit::AUDIT_STATUS_REJECTED,
                    // 'reject_reason' => $request->reject_reason,
                ]);
            }

            return response()->json(['success' => 'Programme audit rejected successfully']);
        } else {
            if (Auth::user()->hasRole('DY MCA')) {
                $audit->update([
                    'dymca_status' => 2,
                    'mca_status' => 1
                ]);
            } elseif (Auth::user()->hasRole('MCA')) {
                $audit->update([
                    'mca_status' => 2,
                    'status' => Audit::AUDIT_STATUS_REJECTED,
                    // 'reject_reason' => $request->reject_reason,
                ]);
            }
            // $audit->update([
            //     'status' => Audit::AUDIT_STATUS_APPROVED,
            // ]);

            return response()->json(['success' => 'Programme audit approved successfully']);
        }
    }


    public function assignAudiorList(Request $request)
    {
        $status = 'approved';
        $statusCode = strtoupper('AUDIT_STATUS_' . $status);
        $statusCode = constant("App\Models\Audit::$statusCode");
        $page_type = 'assign_auditor';

        $audits = Audit::query()
            ->with('assignedAuditors.user')
            // ->where('department_id', Auth::user()->department_id)
            ->when($statusCode == 2, fn($q) => $q->where('mca_status', 2)->orWhere('status', '>=', 4))
            ->when($statusCode != 2, fn($q) => $q->where('mca_status', $statusCode))
            ->latest()
            ->get();

        // return $audits;

        // $audits = Audit::query()->withCount('assignedAuditors as assigned_auditors_count')
        //         ->when(Auth::user()->hasRole('MCA'), fn($q) => $q->where('mca_status', 2))
        //         ->where(Auth::user()->hasRole(''))

        return view('admin.audit-list')->with(['status' => $status, 'audits' => $audits, 'page_type' => $page_type]);
    }


    public function getAuditors(Audit $audit)
    {
        $userAssignedAudit = UserAssignedAudit::where('audit_id', $audit->id)->latest()->pluck('user_id')->toArray();
        $auditors = User::withWhereHas('roles', fn($q) => $q->where('name', 'Auditor'))->orderBy('id', 'DESC')->get()->append('full_name');

        $auditorsHtml = '<span>
            <option value="">--Select Auditor--</option>';
        foreach ($auditors as $auditor) :
            $is_select = in_array($auditor->id, $userAssignedAudit) ? "selected" : "";
            $auditorsHtml .= '<option value="' . $auditor->id . '" ' . $is_select . '>' . $auditor->full_name . '(' . $auditor->auditor_no . ')' . '</option>';
        endforeach;
        $auditorsHtml .= '</span>';

        $response = [
            'result' => 1,
            'audit' => $audit,
            'auditorsHtml' => $auditorsHtml,
        ];

        return $response;
    }


    public function assignAuditor(User $user, AssignAuditorRequest $request)
    {
        try {
            DB::beginTransaction();

            UserAssignedAudit::where(['audit_id' => $request->audit_id])->delete();
            foreach ($request->auditor_id as $auditorId) {
                UserAssignedAudit::create(['audit_id' => $request->audit_id, 'user_id' => $auditorId]);
            }
            Audit::where('id', $request->audit_id)->update(['status' => Audit::AUDIT_STATUS_AUDITOR_ASSIGNED]);

            DB::commit();

            return response()->json(['success' => 'Auditor assigned successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'assigning', 'Auditor');
        }
    }


    public function draftReview(Request $request)
    {
        $audits = Audit::query()
            ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
            // ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
            // ->where('department_id', Auth::user()->department_id)
            ->latest()
            ->get();

        return view('admin.draft-review')->with(['audits' => $audits]);
    }


    public function draftAnswerDetails(Request $request, Audit $audit)
    {
        $audit->load([
            'objections' => fn($q) => $q
                ->where('auditor_action_status', 1)
                ->with([
                    'user',
                    'mcaApprover' => fn($q) => $q->first()?->append('full_name'),
                    'answeredBy' => fn($q) => $q->first()?->append('full_name')
                ])
        ]);

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
        $auditorSatus = "disabled";
        $mcaSatus = "disabled";
        if(Auth::user()->hasRole('Auditor')){
            $auditorSatus = "";
        }

        if(Auth::user()->hasRole('MCA')){
            $mcaSatus = "";
        }

        foreach ($audit->objections as $key => $objection) {
            $isEditable = $objection->status > 3 ? 'readonly' : '';

            $innerHtml .= '
                <div class="row custm-card">
                    <input type="hidden" name="objection_id[]" value="' . $objection->id . '">
                    <h5>' . $objection?->user?->first_name . ' ' . $objection?->user?->middle_name . ' ' . $objection?->user?->last_name . '(' . $objection?->user?->auditor_no . ')' . '</h5>
                    <div class="col-md-3">
                        <label class="col-form-label" for="objection_' . $key . '">(Objection ' . $objection->objection_no . ')</label>
                        <textarea name="objection_' . $key . '" id="objection_' . $key . '" class="form-control" readonly cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->objection . '</textarea>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="compliance_' . $key . '">Compliance <span class="text-danger">*</span></label>
                        <textarea name="compliance_' . $key . '" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->answer . '</textarea>
                        <span class="text-danger is-invalid compliance_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="remark_' . $key . '">Remark <span class="text-danger">*</span></label>
                        <input type="text" name="remark_' . $key . '" readonly value="' . $objection->remark . '" class="form-control">
                        <span class="text-danger is-invalid remark_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="status_' . $key . '">Status</label>
                        <input type="text" name="status_' . $key . '" class="form-control" value="' . $objection?->status_name . '" readonly>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="officer_detail' . $key . '">Officer Detail</label>
                        <input type="text" name="officer_detail' . $key . '" class="form-control" value="' . $objection?->answeredBy?->full_name . '" readonly>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="action_' . $key . '">Approve/Reject</label>
                        <select name="action_' . $key . '" readonly '.$auditorSatus.' class="form-select">
                            <option value="">Action</option>
                            <option value="1" ' . ($objection->auditor_action_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($objection->auditor_action_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid action_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="action_remark_' . $key . '">Approve/Reject Remark</label>
                        <textarea name="action_remark_' . $key . '" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->auditor_remark . '</textarea>
                        <span class="text-danger is-invalid action_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-2 mt-3">
                        <label class="col-form-label" for="mca_action_' . $key . '">MCA Action</label>
                        <select '.$mcaSatus.' name="mca_action_' . $key . '" ' . $isEditable . ' class="form-select">
                            <option value="">Action</option>
                            <option value="1" ' . ($objection->mca_action_status == 1 ? "selected" : "") . '>Approve</option>
                            <option value="2" ' . ($objection->mca_action_status == 2 ? "selected" : "") . '>Reject</option>
                        </select>
                        <span class="text-danger is-invalid mca_action_' . $key . '_err"></span>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label class="col-form-label" for="mca_action_remark_' . $key . '">MCA Remark</label>
                        <textarea name="mca_action_remark_' . $key . '" ' . $isEditable . ' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">' . $objection->mca_remark . '</textarea>
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


    public function draftApproveAnswer(Request $request, Audit $audit)
    {
        $fieldArray['objection_id'] = 'required';
        $messageArray['objection_id.required'] = 'Objection no not found';

        for ($i = 0; $i < count($request->objection_id); $i++) {
            if ($request->{'mca_action_' . $i}) {
                $fieldArray['compliance_' . $i] = 'required';
                $fieldArray['remark_' . $i] = 'required';
                $fieldArray['mca_action_remark_' . $i] = 'required';
                $messageArray['compliance_' . $i . '.required'] = 'Please type compliance';
                $messageArray['remark_' . $i . '.required'] = 'Please type remark';
                $messageArray['mca_action_remark_' . $i . '.required'] = 'Please type approve/reject remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try {
            DB::beginTransaction();
            // $audit->update([ 'status' => Audit::AUDIT_STATUS_AUDITOR_APPROVED_COMPLIANCE ]);

            for ($i = 0; $i < count($request->objection_id); $i++) {
                if ($request->{'mca_action_' . $i}) {
                    $actionParamName = 'mca_action_' . $i;
                    $actionRemarkParamName = 'mca_action_remark_' . $i;
                    AuditObjection::where(['id' => $request->objection_id[$i]])
                        ->update([
                            'status' => $request->{$actionParamName} == 1 ? AuditObjection::OBJECTION_STATUS_MCA_APPROVED : AuditObjection::OBJECTION_STATUS_MCA_REJECTED,
                            'mca_action_status' => $request->{$actionParamName},
                            'mca_remark' => $request->{$actionRemarkParamName},
                            'approved_by_mca' => Auth::user()->id
                        ]);
                }
            }
            DB::commit();

            return response()->json(['success' => 'Action successful']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'taking', 'action');
        }
    }


    public function finalReport(Request $request)
    {
        $audits = Audit::query()
            ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
            ->withCount([
                'objections as approved' => fn($q) => $q->where('status', 4),
                'objections as unapproved' => fn($q) => $q->where('status', 5),
            ])
            // ->where('department_id', Auth::user()->department_id)
            ->latest()
            ->get();

        return view('admin.final-report')->with(['audits' => $audits]);
    }
}
