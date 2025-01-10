<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\SendDepartmentLetterRequest;
use App\Models\Audit;
use App\Models\AuditObjection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\Zone;
use App\Models\FiscalYear;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\AuditParaCategory;
use App\Models\AuditDepartmentAnswer;
use App\Http\Requests\AddObjectionRequest;
use App\Models\AuditObjectionMcaStatus;
use App\Models\Sequence;
use App\Models\PendingAuditObjection;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Signature;
use PDF;
use App\Models\Setting;
use App\Models\OutwardNo;

class AuditorAuditController extends Controller
{
    public function assignedAuditList()
    {
        $user = Auth::user();

        $audits = Audit::query()
            ->where('status', '>=', Audit::AUDIT_STATUS_AUDITOR_ASSIGNED)
            ->whereHas('assignedAuditors', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('program-audit.auditor.assigned-audit-list')->with(['audits' => $audits]);
    }


    public function getAuditInfo(Request $request)
    {
        $audit = Audit::query()
            ->when($request->relations, fn($q) => $q->with([$request->relations => function ($q) {
                $q->where('user_id', Auth::user()->id);
            }]))
            ->with(['department'])
            ->where('id', $request->audit_id)->first();

        $audit->obj_date = Carbon::parse($audit->obj_date)->format('Y-m-d');

        $response = [
            'result' => 1,
            'audit' => $audit,
        ];

        return $response;
    }


    public function sendLetter(SendDepartmentLetterRequest $request)
    {
        set_time_limit(0);
        try {
            $audit = Audit::with('department')->where('id', $request->audit_id)->first();
            DB::beginTransaction();

            $filPath = 'storage/file/' . $request->letter_file->store('', 'file');
            $audit->update(['dl_description' => $request->description, 'dl_file_path' => $filPath, 'status' => Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT]);

            DB::commit();

            return response()->json(['success' => 'Letter successfully sent to department']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'sending letter to', 'department');
        }
    }


    public function createObjection(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
            ->whereHas('assignedAuditors', fn($q) => $q->where('user_id', $user->id))
            ->where('status', '>=', 5)
            ->latest()
            ->get();

        $departments = Department::where('is_audit', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('program-audit.auditor.create-objection')->with([
            'audits' => $audits,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }

    public function getAssignObjection(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['department', 'audit', 'severity'])
                ->where('audit_id', $request->audit_id)
                ->when(isset($request->is_objection_send_request), function ($q) {
                    $q->where('is_objection_send', 1);
                })
                ->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where('is_department_draft_save', 1);
                })
                ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->get();

            $objectionHtml = "";
            if (count($auditObjections) > 0) {
                $objectionHtml .= '<div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Department</th>
                                <th>HMM No.</th>
                                <th>Subject</th>
                                <th>Compliance Submit Date</th>';

                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '
                    <th>DYMCA Status</th>
                    <th>DYMCA Remark</th>
                    <th>MCA Status</th>
                    <th>MCA Remark</th>';
                }
                if (Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '<th>Is Draft</th>';
                }
                $objectionHtml .= '<th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
            }

            $count = 1;
            foreach ($auditObjections as $auditObjection) {
                $objectionHtml .= '<tr>
                                <td>' . $count++ . '</td>
                                <td>' . $auditObjection?->department?->name . '</td>
                                <td>' . $auditObjection?->objection_no . '</td>
                                <td>' . $auditObjection?->subject . '</td>
                                <td>' . (($auditObjection?->compliance_submit_date) ? date('d-m-Y', strtotime($auditObjection?->compliance_submit_date)) : '-') . '</td>';
                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '
                    <th>' . (($auditObjection->dymca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->dymca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : "-")) . '</th>
                    <th>' . (($auditObjection->dymca_remark) ? $auditObjection->dymca_remark : '-') . '</th>
                    <th>' . (($auditObjection->mca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->mca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : (($auditObjection->mca_status == 3) ? '<span class="badge bg-primary">Forward to Department</span>' : '-'))) . '</th>
                    <th>' . (($auditObjection->mca_remark) ? $auditObjection->mca_remark : '-') . '</th>';
                }
                if (Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= ($auditObjection->is_draft_save) ? '<td><span class="badge bg-success">Yes</span></td>' : '<td><span class="badge bg-danger">No</span></td>';
                }
                $objectionHtml .= '<td><button type="button" class="btn btn-sm btn-primary viewObjection" data-id="' . $auditObjection?->id . '">View Objection</button></td>
                            </tr>';
            }

            if (count($auditObjections) > 0) {
                $objectionHtml .= '</tbody>
                        </table>
                    </div>';
            }

            $audit = Audit::find($request->audit_id);

            return response()->json([
                'objectionHtml' => $objectionHtml,
                'department' => $audit->department_id,
                'departmentName' => $audit->department?->name,
            ]);
        }
    }


    public function storeObjection(AddObjectionRequest $request)
    {
        set_time_limit(0);
        try {
            DB::beginTransaction();
            $audit = Audit::where('id', $request->audit_id)->first();

            $audit->update([
                'obj_date' => $request->date,
                'obj_subject' => $request->subject,
            ]);



            $arrData = [
                'user_id' => Auth::user()->id,
                'audit_id' => $audit->id,
                // 'objection_no' => $request->objection_no,
                'entry_date' => date('Y-m-d'),
                'department_id' => $request->department_id,
                'zone_id' => $request->zone_id,
                'from_year' => $request->from_year,
                'to_year' => $request->to_year,
                'audit_type_id' => $request->audit_type_id,
                'severity_id' => $request->severity_id,
                'audit_para_category_id' => $request->audit_para_category_id,
                'amount' => $request->amount,
                'subject' => $request->subject,
                'work_name' => $request->work_name,
                'contractor_name' => $request->contractor_name,
                'sub_unit' => $request->sub_unit,
                'draft_description' => $request->description,
                'dymca_status' => null,
                'dymca_remark' => null,
                'mca_status' => null,
                'mca_remark' => null
            ];

            if ($request->isDrafSave == 0) {
                $arrData = array_merge($arrData, ['description' => $request->description, 'is_draft_save' => 0, 'is_draft_send' => 1]);
            } else {
                $arrData = array_merge($arrData, ['is_draft_save' => 1]);
            }

            if (isset($request->audit_objection_id) && $request->audit_objection_id != "" && $request->audit_objection_id) {


                if ($request->hasFile('documents')) {
                    $document = $request->documents->store('auditor-program-audit');
                    $arrData = array_merge($arrData, ['document' => $document]);
                }

                AuditObjection::updateOrCreate([
                    'id' => $request->audit_objection_id
                ], $arrData);
                DB::commit();
                if ($request->isDrafSave) {
                    return response()->json(['success' => 'Objection draft updated successfully']);
                } else {
                    return response()->json(['success' => 'Objection updated successfully']);
                }
            } else {

                // code for hmm no
                $hmmNo = Sequence::with(['department', 'financialYear'])->where('status', 1)->where('department_id', $request->department_id)->first();

                $sequenceNo = $hmmNo->department?->initial . "" . $hmmNo->financialYear?->name . "" . str_pad($hmmNo->serial_no, 5, "0", STR_PAD_LEFT);
                $arrData = array_merge($arrData, ['objection_no' => $sequenceNo]);
                Sequence::where('id', $hmmNo->id)->increment('serial_no', 1);

                if ($request->hasFile('documents')) {
                    $document = $request->documents->store('auditor-program-audit');
                    $arrData = array_merge($arrData, ['document' => $document]);
                }

                AuditObjection::create($arrData);
                DB::commit();
                if ($request->isDrafSave) {
                    return response()->json(['success' => 'Objection draft created successfully']);
                } else {
                    return response()->json(['success' => 'Objection created successfully']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'creating', 'objection');
        }
    }

    public function generatePdf($audit, $signature, $outwardNo)
    {
        $pdf = PDF::loadView('letter.3', compact('audit', 'signature', 'outwardNo'));

        $name = 'letter/' . $audit->department?->name . "_letter_" . date('d_m_Y_H_i_s') . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }

    public function changeObjectionStatus(Request $request)
    {
        set_time_limit(0);
        if ($request->ajax()) {
            if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA')) {
                try {
                    DB::beginTransaction();
                    if (Auth::user()->hasRole('MCA')) {

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

                        if (isset($request->department_mca_second_status) && $request->department_mca_second_status != "") {

                            $auditObjection = AuditObjection::find($request->audit_objection_id);
                            $auditObjection->department_mca_second_status = $request->department_mca_second_status;
                            $auditObjection->department_mca_second_remark = $request->department_mca_second_remark;
                            if ($auditObjection->status < 8 && $auditObjection->department_hod_final_status) {
                                $auditObjection->status = 8;
                            }
                            $auditObjection->save();

                            $prevStatus = 9;
                            $currentStatus = 10;

                            $this->changeAuditStatus($request, $prevStatus, $currentStatus);
                            DB::commit();
                            return response()->json(['success' => 'Objection forward to auditor successfully']);
                        } else if (isset($request->mca_final_status) && $request->mca_final_status != "") {
                            $auditObjection = AuditObjection::find($request->audit_objection_id);
                            $auditObjection->mca_final_status = $request->mca_final_status;
                            $auditObjection->mca_final_remark = $request->mca_final_remark;
                            if ($auditObjection->status < 11) {
                                $auditObjection->status = 11;
                            }
                            $auditObjection->save();


                            $prevStatus = 12;
                            $currentStatus = 13;

                            $audits = Audit::with(['from', 'to', 'department'])->find($auditObjection->audit_id);


                            $this->changeAuditStatus($request, $prevStatus, $currentStatus);
                            $signature = Signature::whereNull('department_id')->value('image');
                            $outwardNo = Setting::where('name', 'outward_no')->value('value');
                            $name = $this->generatePdf($audits, $signature, $outwardNo);
                            if ($auditObjection->pending_sub_unit > 0) {


                                Setting::where('name', 'outward_no')->increment('value', 1);
                                OutwardNo::create([
                                    'outward_no' => $outwardNo,
                                    'department_id' => $audits->department_id,
                                    'subject' => "सन " . $audits->from->name . " ते " . $audits->from->name . " या कालावधीतील अंतर्गत लेखा परीक्षण अहवालातील आक्षेपांची पूर्तता करून अनुपालन अहवाल सादर करण्याबाबत."
                                ]);


                                PendingAuditObjection::create([
                                    'audit_objection_id' => $auditObjection->id,
                                    'sub_unit' => $auditObjection->pending_sub_unit,
                                    'pending_description' => $auditObjection->auditor_draft_description,
                                    'hmm_draft_letter' => $name,
                                    'status' => 1,
                                    'ask_pending_auditor_remark' => $auditObjection->auditor_remark,
                                    'ask_pending_auditor_status' => $auditObjection->auditor_status,
                                ]);
                            }

                            // send mail code
                            $userdepartment = User::where('department_id', $audits->department_id)->whereNotNull('email')->pluck('email')->toArray();

                            $userdepartment = User::where('department_id', $audits->department_id)->whereNotNull('email')->pluck('email')->toArray();
                            $auditor = User::whereHas('userAssignAudit', function ($q) use ($request) {
                                $q->where('audit_id', $request->audit_id);
                            })->pluck('email')->toArray();
                            $mca = User::whereHas('roles', function ($q) {
                                $q->whereIn('name', ['MCA', 'DY MCA']);
                            })->pluck('email')->toArray();

                            $receiver_list = array_merge($userdepartment, $auditor, $mca);
                            $pdfName = basename($name);
                            Mail::send('program-audit.mca.hmm.send-mail', ['body' => 'MCA Approve the Auditor Compliance Objection'], function ($message) use ($receiver_list, $pdfName) {
                                $message->from(config('details.from'), config('details.from'));
                                $message->to($receiver_list);
                                $message->subject('MCA Approve the Auditor Compliance Objection');

                                $message->attach(storage_path('app/public/letter/' . $pdfName), [
                                    'as' => $pdfName, // Rename the file if needed
                                    'mime' => 'application/pdf', // Define the MIME type
                                ]);
                            });
                            // end of send mail code


                            DB::commit();
                            return response()->json(['success' => 'Objection approve successfully']);
                        }
                    } else {

                        $validator = Validator::make($request->all(), [
                            'dymca_final_status' => 'required',
                        ], [
                            'dymca_final_status.required' => 'Please select status',
                        ]);

                        if ($validator->fails()) {
                            return response()->json(['errors' => $validator->errors()], 422);
                        }

                        $auditObjection = AuditObjection::find($request->audit_objection_id);
                        $auditObjection->dymca_final_status = $request->dymca_final_status;
                        $auditObjection->dymca_final_remark = $request->dymca_final_remark;
                        if ($auditObjection->status < 10) {
                            $auditObjection->status = 10;
                        }
                        $auditObjection->save();


                        $prevStatus = 11;
                        $currentStatus = 12;
                        $this->changeAuditStatus($request, $prevStatus, $currentStatus);
                        DB::commit();
                        return response()->json(['success' => 'Objection approve successfully']);
                    }
                    return response()->json(['error' => 'Something went wrong please try later!']);
                } catch (\Exception $e) {
                    \Log::error('Failed to freeze pension:', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong!']);
                }
            } else if (Auth::user()->hasRole('Department HOD')) {

                $validator = Validator::make($request->all(), [
                    'department_hod_final_status' => 'required',
                ], [
                    'department_hod_final_status.required' => 'Please select status',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                DB::beginTransaction();
                try {
                    $auditObjection = AuditObjection::find($request->audit_objection_id);
                    $auditObjection->department_hod_final_status = $request->department_hod_final_status;
                    $auditObjection->department_hod_final_remark = $request->department_hod_final_remark;
                    if ($auditObjection->status < 7 && $auditObjection->department_hod_final_status) {
                        $auditObjection->status = 7;
                    }
                    $auditObjection->save();

                    if ($request->department_hod_final_status == "1") {
                        $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                        Audit::where('id', $request->audit_id)->update([
                            'status' => ($auditStatus > 8) ? $auditStatus : 9
                        ]);
                        $audit = Audit::find($request->audit_id);
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
                        $name = $auditObjection->department_letter;
                        $pdfName = basename($name);
                        Mail::send('program-audit.mca.hmm.send-mail', ['body' => 'Department Hod Approve the department compliance'], function ($message) use ($receiver_list, $pdfName) {
                            $message->from(config('details.from'), config('details.from'));
                            $message->to($receiver_list);
                            $message->subject('Department Compliance');
                            $message->attach(storage_path('app/public/letter/' . $pdfName), [
                                'as' => $pdfName, // Rename the file if needed
                                'mime' => 'application/pdf', // Define the MIME type
                            ]);
                        });
                        // end of send mail code

                        DB::commit();
                        return response()->json(['success' => 'Objection approve successfully']);
                    }

                    DB::commit();
                    return response()->json(['success' => 'Objection rejected successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    \Log::info($e);
                    response()->json(['error' => 'Something went wrong!']);
                }
            } else if (Auth::user()->hasRole('Auditor')) {

                $validator = Validator::make($request->all(), [
                    'auditor_status' => 'required',
                    'auditor_description' => 'required',
                    'completed_sub_unit' => 'required',
                    'pending_sub_unit' => 'required',
                    'auditor_remark' => 'required',
                ], [
                    'auditor_status.required' => 'Please select status',
                    'auditor_description.required' => 'Please enter description',
                    'completed_sub_unit.required' => 'Please enter completed objection',
                    'pending_sub_unit.required' => 'Please enter pending objection',
                    'auditor_remark.required' => 'Please enter remark',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                DB::beginTransaction();
                try {
                    $auditObjection = AuditObjection::find($request->audit_objection_id);
                    $auditObjection->auditor_status = $request->auditor_status;
                    $auditObjection->auditor_remark = $request->auditor_remark;
                    $auditObjection->is_draft_save = $request->is_draft_save;
                    $auditObjection->completed_sub_unit = $request->completed_sub_unit;
                    $auditObjection->pending_sub_unit = $request->pending_sub_unit;
                    $auditObjection->auditor_draft_description = $request->auditor_description;

                    if (!$request->is_draft_save) {
                        if ($auditObjection->status < 9) {
                            $auditObjection->status = 9;
                        }
                        $auditObjection->auditor_description = $request->auditor_description;
                    }


                    $auditObjection->save();

                    if (!$request->is_draft_save) {
                        $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                        Audit::where('id', $request->audit_id)->update([
                            'status' => ($auditStatus > 10) ? $auditStatus : 11
                        ]);
                        DB::commit();
                        if ($request->auditor_status) {
                            return response()->json(['success' => 'Compliance send for proposal to approve or delete successfully']);
                        } else {
                            return response()->json(['success' => 'Compliance send for proposal to convert para successfully']);
                        }
                    }


                    DB::commit();
                    return response()->json(['success' => 'Compliance save in draft']);
                } catch (\Exception $e) {
                    DB::rollback();
                    response()->json(['error' => 'Something went wrong!']);
                }
            } else {
                response()->json(['error' => 'Something went wrong!']);
            }
        }
    }

    public function changeAuditStatus($request, $prevStatus, $currentStatus)
    {
        $auditStatus = Audit::where('id', $request->audit_id)->value('status');

        Audit::where('id', $request->audit_id)->update([
            'status' => ($auditStatus > $prevStatus) ? $auditStatus : $currentStatus
        ]);

        return true;
    }


    public function pendingAnsweredQuestions(Request $request)
    {
        $audits = AuditObjection::with(['department', 'audit'])
            ->whereHas('audit', function ($q) {
                $q->where('status', '>=', 9)
                    ->whereHas('assignedAuditors', function ($q) {
                        $q->where('user_id', Auth::user()->id);
                    });
            })
            ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                $q->where('user_id', Auth::user()->id)->where('status', '>=', 8)
                    ->whereNull('auditor_status');
            })
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('admin.pending-answered-questions')->with([
            'audits' => $audits,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory
        ]);
    }

    public function approveAnsweredQuestions(Request $request)
    {
        $audits = AuditObjection::with(['department', 'audit'])
            ->whereHas('audit', function ($q) {
                $q->where('status', '>=', 9)
                    ->whereHas('assignedAuditors', function ($q) {
                        $q->where('user_id', Auth::user()->id);
                    });
            })
            ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                $q->where('user_id', Auth::user()->id)->where('status', '>=', 8)
                    ->whereNotNull('auditor_status');
            })
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('admin.approved-answered-questions')->with([
            'audits' => $audits,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory
        ]);
    }


    public function answerDetails(Request $request, Audit $audit)
    {
        $audit->load(['objections' => fn($q) => $q->where('user_id', Auth::user()->id)->with([
            'auditorApprover' => fn($q) => $q->first()?->append('full_name'),
            'answeredBy' => fn($q) => $q->first()?->append('full_name')
        ])]);



        return "";;
    }


    public function approveAnswer(Request $request, Audit $audit)
    {
        set_time_limit(0);
        $fieldArray['objection_id'] = 'required';
        $messageArray['objection_id.required'] = 'Objection no not found';

        for ($i = 0; $i < count($request->objection_id); $i++) {
            if ($request->{'action_' . $i}) {
                $fieldArray['compliance_' . $i] = 'required';
                $fieldArray['remark_' . $i] = 'required';
                $fieldArray['action_remark_' . $i] = 'required';
                $messageArray['compliance_' . $i . '.required'] = 'Please type compliance';
                $messageArray['remark_' . $i . '.required'] = 'Please type remark';
                $messageArray['action_remark_' . $i . '.required'] = 'Please type approve/reject remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try {
            DB::beginTransaction();

            for ($i = 0; $i < count($request->objection_id); $i++) {
                if ($request->{'action_' . $i}) {
                    $actionParamName = 'action_' . $i;
                    $actionRemarkParamName = 'action_remark_' . $i;
                    AuditObjection::where(['id' => $request->objection_id[$i]])
                        ->update([
                            'status' => $request->{$actionParamName} == 1 ? AuditObjection::OBJECTION_STATUS_AUDITOR_APPROVED : AuditObjection::OBJECTION_STATUS_AUDITOR_REJECTED,
                            'auditor_action_status' => $request->{$actionParamName},
                            'auditor_remark' => $request->{$actionRemarkParamName},
                            'approved_by_auditor' => Auth::user()->id
                        ]);
                }
            }
            DB::commit();

            return response()->json(['success' => 'Action successfully']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'taking', 'action');
        }
    }
}
