<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingAuditObjection;
use App\Models\AuditObjection;
use App\Models\Department;
use App\Models\Zone;
use App\Models\FiscalYear;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\Audit;
use App\Models\AuditParaCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Models\OutwardNo;

class PendingAuditObjectionController extends Controller
{
    public function pendingAuditObjection()
    {
        $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
            ->where('is_objection_completed', 0)
            ->when(Auth::user()->hasRole(['Department', 'Department HOD']), function ($q) {
                $q->whereHas('auditObjection', function ($q) {
                    $q->where('status', '>=', 1)->where('department_id', Auth::user()->department_id);
                })->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where(function ($q) {
                        $q->whereNull('department_hod_final_status')
                            ->orWhere('department_hod_final_status', 2);
                    });
                })->when(Auth::user()->hasRole('Department'), function ($q) {
                    $q->whereNull('department_draft_remark');
                });
            })
            ->when(Auth::user()->hasRole(['MCA']), function ($q) {
                $q->where('status', '>=', 2);
            })
            ->when(Auth::user()->hasRole(['Auditor']), function ($q) {
                $q->where('status', '>=', 3);
            })
            ->when(Auth::user()->hasRole(['DY MCA']), function ($q) {
                $q->where('status', '>=', 4);
            })
            ->when(Auth::user()->hasRole(['Clerk']), function ($q) {
                $q->where('status', '>=', 44);
            })
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('program-audit.pending-objection.index')->with([
            'pendingAuditObjections' => $pendingAuditObjections,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }


    public function approvePendingAuditObjection()
    {
        $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
            ->where('is_objection_completed', 0)
            ->when(Auth::user()->hasRole(['Department', 'Department HOD']), function ($q) {
                $q->whereHas('auditObjection', function ($q) {
                    $q->where('status', '>=', 1)->where('department_id', Auth::user()->department_id);
                })->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where('department_hod_final_status', 1);
                })->when(Auth::user()->hasRole('Department'), function ($q) {
                    $q->whereNotNull('department_draft_remark');
                });
            })
            ->when(Auth::user()->hasRole(['MCA']), function ($q) {
                $q->where('status', '>=', 2);
            })
            ->when(Auth::user()->hasRole(['Auditor']), function ($q) {
                $q->where('status', '>=', 3);
            })
            ->when(Auth::user()->hasRole(['DY MCA']), function ($q) {
                $q->where('status', '>=', 4);
            })
            ->when(Auth::user()->hasRole(['Clerk']), function ($q) {
                $q->where('status', '>=', 44);
            })
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('program-audit.pending-objection.approve')->with([
            'pendingAuditObjections' => $pendingAuditObjections,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }

    public function viewPendingObjection(Request $request)
    {
        if ($request->ajax()) {
            $audit = PendingAuditObjection::with(['auditObjection.audit'])
                ->where('id', $request->id)->first();

            return response()->json([
                'audit' => $audit
            ]);
        }
    }

    public function changePendingObjectionStatus(Request $request)
    {
        set_time_limit(0);

        if ($request->ajax()) {
            if (Auth::user()->hasRole(['Department'])) {

                if (!$request->is_draft_save) {

                    $request->validate([
                        'department_files' => 'required_if:departmentCompliaceFile,1',
                        'department_remark' => 'required',
                        'submit_compliance' => 'required',
                    ], [
                        'department_files.required_if' => 'Please select compliance file',
                        'department_remark.required' => 'Please enter compliance description',
                        'submit_compliance.required' => 'Please enter submitted compliance',
                    ]);
                }

                $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                $files = $pendingAuditObjection->department_file;
                if ($request->hasFile('department_files')) {
                    if (Storage::exists('public/' . $pendingAuditObjection->department_file)) {
                        Storage::delete('public/' . $pendingAuditObjection->department_file);
                    }
                    $files = $request->department_files->store('pending-objection');
                }
                $pendingAuditObjection->department_file = $files;
                $pendingAuditObjection->submit_compliance = $request->submit_compliance;



                if ($request->is_draft_save) {
                    $pendingAuditObjection->department_draft_remark = $request->department_remark;
                } else {
                    if ($pendingAuditObjection->status < 1) {
                        $pendingAuditObjection->status = 1;
                    }

                    $auditObjection = AuditObjection::find($pendingAuditObjection->audit_objection_id);
                    $audits = Audit::with(['from', 'to', 'department'])->find($auditObjection->audit_id);
                    $signature = Signature::where('department_id', $audits->department_id)->value('image');
                    $name = $this->generateFinalPdf($audits, $signature);

                    $pendingAuditObjection->department_draft_remark = $request->department_remark;
                    $pendingAuditObjection->department_remark = $request->department_remark;
                    $pendingAuditObjection->department_letter = $name;
                }
                if ($pendingAuditObjection->save()) {
                    if ($request->is_draft_save) {
                        return response()->json(['success' => 'Compliance draft save successfully']);
                    } else {
                        return response()->json(['success' => 'Compliance submitted successfully']);
                    }
                }
            } elseif (Auth::user()->hasRole(['Department HOD'])) {

                $validator = Validator::make($request->all(), [
                    'department_hod_final_status' => 'required',
                ], [
                    'department_hod_final_status.required' => 'Please select status',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $pendingAuditObjection = PendingAuditObjection::with(['auditObjection'])->find($request->pending_audit_objection_id);
                $pendingAuditObjection->department_hod_final_status = $request->department_hod_final_status;
                $pendingAuditObjection->department_hod_final_remark = $request->department_hod_final_remark;

                if ($request->department_hod_final_status == "1" && $pendingAuditObjection->status < 2) {
                    $pendingAuditObjection->status = 2;
                }

                $pendingAuditObjection->save();

                if ($request->department_hod_final_status == "1") {
                    // send mail code
                    if ($pendingAuditObjection?->auditObjection->department_id) {
                        $departmentId = $pendingAuditObjection?->auditObjection->department_id;
                        $userdepartment = User::where('department_id', $departmentId)->whereNotNull('email')->pluck('email')->toArray();

                        $userdepartment = User::where('department_id', $departmentId)->whereNotNull('email')->pluck('email')->toArray();
                        $auditor = User::whereHas('userAssignAudit', function ($q) use ($pendingAuditObjection) {
                            $q->where('audit_id', $pendingAuditObjection?->auditObjection->audit_id);
                        })->pluck('email')->toArray();
                        $mca = User::whereHas('roles', function ($q) {
                            $q->whereIn('name', ['MCA', 'DY MCA']);
                        })->pluck('email')->toArray();

                        $receiver_list = array_merge($userdepartment, $auditor, $mca);

                        $pdfName = basename($pendingAuditObjection->department_letter);
                        Mail::send('program-audit.mca.hmm.send-mail', ['body' => 'Approve Pending Compliace Objection by department HOD'], function ($message) use ($receiver_list, $pdfName) {
                            $message->from(config('details.from'), config('details.from'));
                            $message->to($receiver_list);
                            $message->subject('Approve Pending Compliace Objection');

                            $message->attach(storage_path('app/public/letter/' . $pdfName), [
                                'as' => $pdfName, // Rename the file if needed
                                'mime' => 'application/pdf', // Define the MIME type
                            ]);
                        });
                    }
                    // end of send mail code


                    return response()->json(['success' => 'Compliance approve successfully']);
                } else {
                    return response()->json(['success' => 'Compliance rejected successfully']);
                }
            } elseif (Auth::user()->hasRole(['MCA'])) {
                if ($request->has('department_mca_second_status')) {
                    $validator = Validator::make($request->all(), [
                        'department_mca_second_status' => 'required',
                    ], [
                        'department_mca_second_status.required' => 'Please select status',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }
                }

                if ($request->has('mca_final_status')) {
                    $validator = Validator::make($request->all(), [
                        'mca_final_status' => 'required',
                    ], [
                        'mca_final_status.required' => 'Please select status',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }
                }
                if (isset($request->department_mca_second_status)) {
                    $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                    $pendingAuditObjection->department_mca_second_status = $request->department_mca_second_status;
                    $pendingAuditObjection->department_mca_second_remark = $request->department_mca_second_remark;

                    if ($pendingAuditObjection->status < 3) {
                        $pendingAuditObjection->status = 3;
                    }

                    $pendingAuditObjection->save();

                    return response()->json(['success' => 'Objection forwarded to auditor successfully']);
                } else {
                    $pendingAuditObjection = PendingAuditObjection::with(['auditObjection'])->find($request->pending_audit_objection_id);
                    $pendingAuditObjection->mca_final_status = $request->mca_final_status;
                    $pendingAuditObjection->mca_final_remark = $request->mca_final_remark;
                    $pendingAuditObjection->is_objection_completed = 1;

                    if ($pendingAuditObjection->status < 6) {
                        $pendingAuditObjection->status = 6;
                    }

                    $pendingAuditObjection->save();

                    if ($pendingAuditObjection->pending_sub_unit > 0) {
                        $auditObjection = AuditObjection::find($pendingAuditObjection->audit_objection_id);
                        $audits = Audit::with(['from', 'to', 'department'])->find($auditObjection->audit_id);
                        $signature = Signature::whereNull('department_id')->value('image');
                        $outwardNo = Setting::where('name', 'outward_no')->value('value');

                        $name = $this->generatePdf($audits, $signature, $outwardNo);
                        Setting::where('name', 'outward_no')->increment('value', 1);
                        OutwardNo::create([
                            'outward_no' => $outwardNo,
                            'department_id' => $audits->department_id,
                            'subject' => "सन " . $audits->from->name . " ते " . $audits->from->name . " या कालावधीतील अंतर्गत लेखा परीक्षण अहवालातील आक्षेपांची पूर्तता करून अनुपालन अहवाल सादर करण्याबाबत."
                        ]);

                        // send mail code
                        if ($pendingAuditObjection?->auditObjection->department_id) {
                            $departmentId = $pendingAuditObjection?->auditObjection->department_id;
                            $userdepartment = User::where('department_id', $departmentId)->whereNotNull('email')->pluck('email')->toArray();

                            $userdepartment = User::where('department_id', $departmentId)->whereNotNull('email')->pluck('email')->toArray();
                            $auditor = User::whereHas('userAssignAudit', function ($q) use ($pendingAuditObjection) {
                                $q->where('audit_id', $pendingAuditObjection?->auditObjection->audit_id);
                            })->pluck('email')->toArray();
                            $mca = User::whereHas('roles', function ($q) {
                                $q->whereIn('name', ['MCA', 'DY MCA']);
                            })->pluck('email')->toArray();

                            $receiver_list = array_merge($userdepartment, $auditor, $mca);

                            $pdfName = basename($pendingAuditObjection->department_letter);
                            Mail::send('program-audit.mca.hmm.send-mail', ['body' => 'Approve Pending Compliace Objection by department HOD'], function ($message) use ($receiver_list, $pdfName) {
                                $message->from(config('details.from'), config('details.from'));
                                $message->to($receiver_list);
                                $message->subject('Approve Pending Compliace Objection');

                                $message->attach(storage_path('app/public/letter/' . $pdfName), [
                                    'as' => $pdfName, // Rename the file if needed
                                    'mime' => 'application/pdf', // Define the MIME type
                                ]);
                            });
                        }
                        // end of send mail code

                        PendingAuditObjection::create([
                            'audit_objection_id' => $pendingAuditObjection->audit_objection_id,
                            'sub_unit' => $pendingAuditObjection->pending_sub_unit,
                            'pending_description' => $pendingAuditObjection->auditor_draft_description,
                            'status' => 1,
                            'hmm_draft_letter' => $name,
                            'parent_id' => $pendingAuditObjection->id,
                            'ask_pending_auditor_remark' => $pendingAuditObjection->auditor_remark,
                            'ask_pending_auditor_status' => $pendingAuditObjection->auditor_status,
                        ]);
                    }

                    return response()->json(['success' => 'Objection approve successfully']);
                }


                return response()->json(['success' => 'Compliance forward to auditor successfully']);
            } elseif (Auth::user()->hasRole(['DY MCA'])) {
                if ($request->has('dymca_final_status')) {
                    $validator = Validator::make($request->all(), [
                        'dymca_final_status' => 'required',
                    ], [
                        'dymca_final_status.required' => 'Please select status',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 422);
                    }
                }
                $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                $pendingAuditObjection->dymca_final_status = $request->dymca_final_status;
                $pendingAuditObjection->dymca_final_remark = $request->dymca_final_remark;
                if ($pendingAuditObjection->status < 5) {
                    $pendingAuditObjection->status = 5;
                }
                $pendingAuditObjection->save();

                return response()->json(['success' => 'Objection approve successfully']);
            } elseif (Auth::user()->hasRole(['Auditor'])) {

                if (!$request->is_draft_save) {
                    $validator = Validator::make($request->all(), [
                        'auditor_description' => 'required',
                        'completed_sub_unit' => 'required',
                        'pending_sub_unit' => 'required',
                        'auditor_remark' => 'required',
                        'auditor_status' => 'required',
                    ], [
                        'auditor_description.required' => 'Please enter description',
                        'completed_sub_unit.required' => 'Please enter completed objection',
                        'pending_sub_unit.required' => 'Please enter pending objection',
                        'auditor_remark.required' => 'Please enter remark',
                        'auditor_status.required' => 'Please select status',
                    ]);
                }

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                try {
                    DB::beginTransaction();
                    $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                    $pendingAuditObjection->auditor_description = $request->auditor_description;
                    $pendingAuditObjection->completed_sub_unit = $request->completed_sub_unit;
                    $pendingAuditObjection->pending_sub_unit = $request->pending_sub_unit;
                    $pendingAuditObjection->auditor_remark = $request->auditor_remark;
                    $pendingAuditObjection->auditor_status = $request->auditor_status;

                    if ($request->is_draft_save) {
                        $pendingAuditObjection->auditor_draft_description = $request->auditor_description;
                    } else {
                        $pendingAuditObjection->auditor_draft_description = $request->auditor_description;
                        $pendingAuditObjection->auditor_description = $request->auditor_description;
                        if ($pendingAuditObjection->status < 4) {
                            $pendingAuditObjection->status = 4;
                        }
                    }
                    $pendingAuditObjection->save();

                    AuditObjection::where('id', $pendingAuditObjection->audit_objection_id)->update([
                        'completed_sub_unit' => $request->completed_sub_unit,
                        'pending_sub_unit' => $request->pending_sub_unit,
                        'submit_compliance' => $pendingAuditObjection->submit_compliance
                    ]);

                    DB::commit();

                    return response()->json(['success' => 'Objection updated successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong please try later']);
                }
            }
        }
    }

    public function generatePdf($audit, $signature, $outwardNo)
    {
        $pdf = PDF::loadView('letter.4', compact('audit', 'signature', 'outwardNo'));

        $name = 'letter/' . $audit->department?->name . "_letter_" . date('d_m_Y_H_i_s') . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }

    public function generateFinalPdf($audit, $signature)
    {
        $pdf = PDF::loadView('letter.3', compact('audit', 'signature'));

        $name = 'letter/' . $audit->department?->name . "_letter_" . date('d_m_Y_H_i_s') . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }

    public function viewObjectionPdf($type, $column, $id)
    {
        if ($type == "1") {
            $data = AuditObjection::with(['department', 'from', 'to'])->where('id', $id)->first();
            $name = $data?->department->name;
            $objectionNo = $data->objection_no;
            $entryDate = date('d-m-Y', strtotime($data->entry_date));
            $department = $data->department->name;

            $from = $data->from->name;
            $to = $data->to->name;
        } else {
            $data = PendingAuditObjection::with(['auditObjection.department', 'auditObjection.from', 'auditObjection.to'])->where('id', $id)->first();
            $name = $data->auditObjection?->department->name;
            $objectionNo = $data->auditObjection->objection_no;
            $entryDate = date('d-m-Y', strtotime($data->auditObjection->entry_date));
            $department = $data->auditObjection->department->name;
            $from = $data->auditObjection->from->name;
            $to = $data->auditObjection->to->name;
        }
        $name = $name . "_auditor_status_" . date('d-m-Y');

        // return $data;
        return view('pdf.document')->with([
            'data' => $data,
            'column' => $column,
            'name' => $name,
            'objectionNo' => $objectionNo,
            'entryDate' => $entryDate,
            'department' => $department,
            'from' => $from,
            'to' => $to,
        ]);

        $pdf = PDF::loadView('pdf.document', compact('data', 'column', 'name', 'objectionNo', 'entryDate', 'department', 'from', 'to'));

        return $pdf->stream($name . '.pdf');
    }
}
