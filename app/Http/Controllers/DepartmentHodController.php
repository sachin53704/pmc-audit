<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Audit;
use App\Models\AuditObjection;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class DepartmentHodController extends Controller
{
    public function forwardObjectionToDepartment(Request $request)
    {
        $audits = Audit::query()->whereHas('objections', function ($q) {
            $q->where('is_draft_send', 1)
                ->where('is_department_hod_forward', 0);
        })->where('department_id', Auth::user()->department_id)->latest()
            ->get();

        return view('department-hod.forward-objection')->with([
            'audits' => $audits
        ]);
    }

    public function storeForwardObjectionToDepartment(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->id)) {
                DB::beginTransaction();
                try {
                    if (isset($request->id)) {
                        for ($i = 0; $i < count($request->id); $i++) {
                            $auditObjection = AuditObjection::find($request->id[$i]);
                            $auditObjection->is_department_hod_forward = 1;
                            if ($auditObjection->status < 5) {
                                $auditObjection->status = 5;
                            }
                            $auditObjection->save();
                        }
                    }

                    $audit = Audit::find($request->audit_id);

                    if ($audit->status <= 7) {
                        $audit->status = 7;
                        $audit->save();
                    }

                    // send mail code
                    $userdepartment = User::where('department_id', $audit->department_id)->whereNotNull('email')->pluck('email')->toArray();

                    $userdepartment = User::where('department_id', $audit->department_id)->whereNotNull('email')->pluck('email')->toArray();
                    $auditor = User::whereHas('userAssignAudit', function ($q) use ($request) {
                        $q->where('audit_id', $request->audit_id);
                    })->pluck('email')->toArray();
                    $mca = User::whereHas('roles', function ($q) {
                        $q->whereIn('name', ['MCA', 'DY MCA']);
                    })->pluck('email')->toArray();

                    $receiver_list = array_merge($userdepartment, $auditor, $mca);

                    Mail::send('mca.hmm.send-mail', ['body' => 'Body goes here'], function ($message) use ($receiver_list) {
                        $message->from('from@example.com', 'Your Name');
                        $message->to($receiver_list);
                        $message->subject('Hello');
                    });
                    // end of send mail code

                    DB::commit();
                    return response()->json(['success' => 'Objection send successful']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong']);
                }
            }
            return response()->json(['error' => 'Select atleast one objection']);
        }
    }

    public function viewForwardObjectionToDepartment(Request $request)
    {
        $objection = AuditObjection::with(['department', 'from', 'to', 'zone', 'auditType', 'severity', 'auditParaCategory'])->where('id', $request->id)->first();

        $pdf = PDF::loadView('department-hod.pdf', compact('objection'));

        return $pdf->stream('para-current-status.pdf');
    }

    // save department compliance
    public function saveDepartmentCompliance(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            try {
                DB::beginTransaction();

                $auditObjection = AuditObjection::find($request->audit_objection_id);

                $auditObjection->department_draft_remark = $request->department_remark;
                if ($request->is_draft_save == 1) {
                    $auditObjection->is_department_draft_save = 1;
                } else {
                    $auditObjection->is_department_draft_save = 0;
                    $auditObjection->compliance_submit_date = now();
                    $auditObjection->department_remark = $request->department_remark;
                }

                if ($auditObjection->status < 6) {
                    $auditObjection->status = 6;
                }

                $auditObjection->department_hod_final_status = null;
                $auditObjection->department_mca_second_status = null;
                $auditObjection->auditor_status = null;
                $auditObjection->dymca_final_status = null;
                $auditObjection->mca_final_status = null;
                if ($request->hasFile('department_files')) {
                    if (Storage::exists('public/' . $auditObjection->department_file)) {
                        Storage::delete('public/' . $auditObjection->department_file);
                    }
                    $file = $request->department_files->store('department');

                    $auditObjection->department_file = $file;
                }

                $auditObjection->save();

                if ($request->is_draft_save == 0) {
                    $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                    Audit::where('id', $request->audit_id)->update([
                        'status' => ($auditStatus > 7) ? $auditStatus : 8
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => 'Compliance Submited Successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'error' => 'Something went wrong'
                ]);
            }
        }
    }
}
