<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\AssignAuditorRequest;
use App\Models\Audit;
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
}
