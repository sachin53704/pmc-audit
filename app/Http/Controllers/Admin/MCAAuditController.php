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
        $statusCode = strtoupper('AUDIT_STATUS_'.$status);
        $statusCode = constant("App\Models\Audit::$statusCode");

        $audits = Audit::query()
                        ->where('department_id', Auth::user()->department_id)
                        ->when($statusCode == 2, fn($q) => $q->where('status', 2)->orWhere('status', '>=', 4) )
                        ->when($statusCode != 2, fn($q) => $q->where('status', $statusCode))
                        ->get();

        return view('admin.audit-list')->with(['status' => $status, 'audits' => $audits]);
    }


    public function auditStatusChange(Audit $audit, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required_if:action,reject',
        ],[
            'reject_reason.required_if' => 'Please enter reject reason',
        ]);

        if($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        $action = $request->action;
        if($action == "reject")
        {
            $audit->update([
                'status' => Audit::AUDIT_STATUS_REJECTED,
                'reject_reason' => $request->reject_reason,
            ]);

            return response()->json(['success'=> 'Programme audit rejected successfully']);
        }
        else
        {
            $audit->update([
                'status' => Audit::AUDIT_STATUS_APPROVED,
            ]);

            return response()->json(['success'=> 'Programme audit approved successfully']);
        }
    }


    public function assignAudiorList(Request $request)
    {
        $status = 'approved';
        $statusCode = strtoupper('AUDIT_STATUS_'.$status);
        $statusCode = constant("App\Models\Audit::$statusCode");
        $page_type = 'assign_auditor';

        $audits = Audit::query()
                        ->where('department_id', Auth::user()->department_id)
                        ->when($statusCode == 2, fn($q) => $q->where('status', 2)->orWhere('status', '>=', 4) )
                        ->when($statusCode != 2, fn($q) => $q->where('status', $statusCode))
                        ->get();

        return view('admin.audit-list')->with(['status' => $status, 'audits' => $audits, 'page_type' => $page_type]);
    }


    public function getAuditors(Audit $audit)
    {
        $userAssignedAudit = UserAssignedAudit::where('audit_id', $audit->id)->latest()->pluck('user_id')->toArray();
        $auditors = User::withWhereHas('roles', fn($q) => $q->where('name', 'Auditor'))->orderBy('id', 'DESC')->get()->append('full_name');

        $auditorsHtml = '<span>
            <option value="">--Select Auditor--</option>';
            foreach($auditors as $auditor):
                $is_select = in_array($auditor->id, $userAssignedAudit) ? "selected" : "";
                $auditorsHtml .= '<option value="'.$auditor->id.'" '.$is_select.'>'.$auditor->full_name.'</option>';
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
        try
        {
            DB::beginTransaction();

            UserAssignedAudit::where(['audit_id'=> $request->audit_id])->delete();
            foreach($request->auditor_id as $auditorId)
            {
                UserAssignedAudit::create(['audit_id'=> $request->audit_id, 'user_id'=> $auditorId]);
            }
            Audit::where('id', $request->audit_id)->update(['status' => Audit::AUDIT_STATUS_AUDITOR_ASSIGNED]);

            DB::commit();

            return response()->json(['success'=> 'Auditor assigned successfully']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'assigning', 'Auditor');
        }
    }


    public function draftReview(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
                        ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
                        // ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                        ->where('department_id', Auth::user()->department_id)
                        ->latest()
                        ->get();

        return view('admin.draft-review')->with(['audits' => $audits]);
    }


    public function draftAnswerDetails(Request $request, Audit $audit)
    {
        $audit->load(['objections' => fn($q) => $q->with([
            'mcaApprover' => fn($q) => $q->first()?->append('full_name'),
            'answeredBy' => fn($q) => $q->first()?->append('full_name')
        ])]);

        $innerHtml = '
                <div class="mb-3 row">
                    <div class="col-md-6 mt-3">
                        <label class="col-form-label" for="hmm_no">HMM No.</label>
                        <input name="hmm_no" class="form-control" value="'.$audit->audit_no.'" readonly type="text">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="col-form-label" for="date">Date.</label>
                        <input name="date" class="form-control" readonly type="date" value="'.$audit->date.'">
                    </div>
                </div>';

        foreach($audit->objections as $key => $objection)
        {
            $isEditable = $objection->status > 3 ? 'readonly' : '';

            $innerHtml .= '
                <hr class="my-2">
                <input type="hidden" name="objection_id[]" value="'.$objection->id.'">
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="objection_'.$key.'">Objection</label>
                    <textarea name="objection_'.$key.'" id="objection_'.$key.'" class="form-control" readonly cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->objection.'</textarea>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="compliance_'.$key.'">Compliance <span class="text-danger">*</span></label>
                    <textarea name="compliance_'.$key.'" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->answer.'</textarea>
                    <span class="text-danger is-invalid compliance_'.$key.'_err"></span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="remark_'.$key.'">Remark <span class="text-danger">*</span></label>
                    <input type="text" name="remark_'.$key.'" readonly value="'.$objection->remark.'" class="form-control">
                    <span class="text-danger is-invalid remark_'.$key.'_err"></span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="status_'.$key.'">Status</label>
                    <input type="text" name="status_'.$key.'" class="form-control" value="'.$objection?->status_name.'" readonly>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="officer_detail'.$key.'">Officer Detail</label>
                    <input type="text" name="officer_detail'.$key.'" class="form-control" value="'.$objection?->answeredBy?->full_name.'" readonly>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="action_'.$key.'">Approve/Reject</label>
                    <select name="action_'.$key.'" readonly class="form-control">
                        <option value="">Action</option>
                        <option value="1" '.($objection->status == 2 ? "selected" : "").'>Approve</option>
                        <option value="2" '.($objection->status == 3 ? "selected" : "").'>Reject</option>
                    </select>
                    <span class="text-danger is-invalid action_'.$key.'_err"></span>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="action_remark_'.$key.'">Approve/Reject Remark</label>
                    <textarea name="action_remark_'.$key.'" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->auditor_remark.'</textarea>
                    <span class="text-danger is-invalid action_'.$key.'_err"></span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="mca_action_'.$key.'">MCA Action</label>
                    <select name="mca_action_'.$key.'" '.$isEditable.' class="form-control">
                        <option value="">Action</option>
                        <option value="1" '.($objection->status == 4 ? "selected" : "").'>Approve</option>
                        <option value="2" '.($objection->status == 5 ? "selected" : "").'>Reject</option>
                    </select>
                    <span class="text-danger is-invalid mca_action_'.$key.'_err"></span>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="mca_action_remark_'.$key.'">MCA Remark</label>
                    <textarea name="mca_action_remark_'.$key.'" '.$isEditable.' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->mca_remark.'</textarea>
                    <span class="text-danger is-invalid mca_action_'.$key.'_err"></span>
                </div>
                <hr class="my-2">';
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

        for($i=0; $i<count($request->objection_id); $i++)
        {
            if($request->{'mca_action_'.$i})
            {
                $fieldArray['compliance_' . $i] = 'required';
                $fieldArray['remark_' . $i] = 'required';
                $fieldArray['mca_action_remark_' . $i] = 'required';
                $messageArray['compliance_' . $i . '.required'] = 'Please type compliance';
                $messageArray['remark_' . $i . '.required'] = 'Please type remark';
                $messageArray['mca_action_remark_' . $i . '.required'] = 'Please type approve/reject remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try
        {
            DB::beginTransaction();
            // $audit->update([ 'status' => Audit::AUDIT_STATUS_AUDITOR_APPROVED_COMPLIANCE ]);

            for($i=0; $i<count($request->objection_id); $i++)
            {
                if($request->{'mca_action_'.$i})
                {
                    $actionParamName = 'mca_action_'.$i;
                    $actionRemarkParamName = 'mca_action_remark_'.$i;
                    AuditObjection::where(['id' => $request->objection_id[$i]])
                            ->update([
                                'status' => $request->{$actionParamName} == 1 ? AuditObjection::OBJECTION_STATUS_MCA_APPROVED : AuditObjection::OBJECTION_STATUS_MCA_REJECTED,
                                'mca_remark' => $request->{$actionRemarkParamName},
                                'approved_by_mca' => Auth::user()->id
                            ]);
                }
            }
            DB::commit();

            return response()->json(['success'=> 'Action successfully']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'taking', 'action');
        }
    }
}
