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

        return view('admin.assigned-audit-list')->with(['audits' => $audits]);
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

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('admin.create-objection')->with([
            'audits' => $audits,
            'zones' => $zones,
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
            $auditObjections = AuditObjection::with(['audit', 'auditType', 'zone', 'severity'])
                ->where('audit_id', $request->audit_id)
                // ->when(Auth::user()->hasRole('Department'), function ($q) {
                //     $q->where('mca_action_status', 2);
                // })
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
                                <th>HMM No.</th>
                                <th>Audit Type</th>
                                <th>Severity</th>
                                <th>Zone</th>';

                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '
                    <th>DYMCA Status</th>
                    <th>DYMCA Remark</th>
                    <th>MCA Status</th>
                    <th>MCA Remark</th>';
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
                                <td>' . $auditObjection?->objection_no . '</td>
                                <td>' . $auditObjection?->auditType?->name . '</td>
                                <td>' . $auditObjection?->severity?->name . '</td>
                                <td>' . $auditObjection?->zone?->name . '</td>';
                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '
                    <th>' . (($auditObjection->dymca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->dymca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : "-")) . '</th>
                    <th>' . (($auditObjection->dymca_remark) ? $auditObjection->dymca_remark : '-') . '</th>
                    <th>' . (($auditObjection->mca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->mca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : (($auditObjection->mca_status == 3) ? '<span class="badge bg-primary">Forward to Department</span>' : '-'))) . '</th>
                    <th>' . (($auditObjection->mca_remark) ? $auditObjection->mca_remark : '-') . '</th>';
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
        // dd($request->all());
        try {
            DB::beginTransaction();
            $audit = Audit::where('id', $request->audit_id)->first();

            // $auditStatus = $audit->status;

            $audit->update([
                'obj_date' => $request->date,
                'obj_subject' => $request->subject,
                // 'status' => ($auditStatus > 6) ? $auditStatus : 6,
            ]);

            $document = null;
            if ($request->hasFile('documents')) {
                $document = $request->documents->store('auditor-program-audit');
            }

            $arrData = [
                'user_id' => Auth::user()->id,
                'audit_id' => $audit->id,
                'objection_no' => $request->objection_no,
                'entry_date' => date('Y-m-d', strtotime($request->entry_date)),
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
                'document' => $document,
                'sub_unit' => $request->sub_unit,
                'draft_description' => $request->description,
                'dymca_status' => null,
                'dymca_remark' => null,
                'mca_status' => null,
                'mca_remark' => null
            ];

            if ($request->isDrafSave == 0) {
                $arrData = array_merge($arrData, ['description' => $request->description, 'is_draft_send' => 1]);
            }

            if (isset($request->audit_objection_id) && $request->audit_objection_id != "" && $request->audit_objection_id) {
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

    public function changeObjectionStatus(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA')) {

                DB::beginTransaction();

                if (Auth::user()->hasRole('MCA')) {
                    $roleStatus = "mca_status";
                    $roleRemark = "mca_remark";
                    $prevStatus = 11;
                    $currentStatus = 12;
                } else {
                    $roleStatus = "dymca_status";
                    $roleRemark = "dymca_remark";
                    $prevStatus = 10;
                    $currentStatus = 11;
                }
                try {
                    if (isset($request->audit_department_answer_id) && count($request->audit_department_answer_id) > 0) {
                        for ($i = 0; $i < count($request->audit_department_answer_id); $i++) {

                            if (Auth::user()->hasRole('MCA') && $request->department_mca_status_id[$i]) {
                                AuditDepartmentAnswer::where('id', $request->audit_department_answer_id[$i])->update([
                                    'department_mca_status' => $request->department_mca_status[$i],
                                    'department_mca_remark' => $request->department_mca_remark[$i]
                                ]);
                                if ($request->department_mca_status_id[$i]) {
                                    $prevStatus = 8;
                                    $currentStatus = 9;
                                } else {
                                    $prevStatus = 11;
                                    $currentStatus = 12;
                                }
                            } else {
                                AuditDepartmentAnswer::where('id', $request->audit_department_answer_id[$i])->update([
                                    $roleStatus => $request->$roleStatus[$i],
                                    $roleRemark => $request->$roleRemark[$i]
                                ]);
                            }
                        }
                    }

                    $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                    Audit::where('id', $request->audit_id)->update([
                        'status' => ($auditStatus > $prevStatus) ? $auditStatus : $currentStatus
                    ]);

                    DB::commit();
                    return response()->json(['success' => 'Objection approve successfully']);
                } catch (\Exception $e) {
                    DB::rollback();

                    return response()->json(['error' => 'Something went wrong!']);
                }
            } else if (Auth::user()->hasRole('Department')) {
                // AuditDepartmentAnswer
                DB::beginTransaction();
                try {

                    if (isset($request->remark) && is_array($request->remark) && count($request->remark) > 0) {
                        for ($i = 0; $i < count($request->remark); $i++) {
                            $file = null;
                            if ($request->hasFile('files') && isset($request->file('files')[$i])) {
                                $file = $request->file('files')[$i]->store('department-answer-file');
                            }
                            $auditDepartmentAnswer = new AuditDepartmentAnswer;
                            $auditDepartmentAnswer->audit_objection_id = $request->audit_objection_id;
                            $auditDepartmentAnswer->audit_id = $request->audit_id;
                            $auditDepartmentAnswer->remark = $request->remark[$i];
                            $auditDepartmentAnswer->file = $file;
                            $auditDepartmentAnswer->save();
                        }
                    }

                    $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                    Audit::where('id', $request->audit_id)->update([
                        'status' => ($auditStatus > 6) ? $auditStatus : 7
                    ]);

                    DB::commit();
                    return response()->json(['success' => 'Document uploaded successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    response()->json(['error' => 'Something went wrong!']);
                }
            } else if (Auth::user()->hasRole('Department HOD')) {
                DB::beginTransaction();
                try {
                    if (isset($request->audit_department_answer_id) && count($request->audit_department_answer_id) > 0) {
                        for ($i = 0; $i < count($request->audit_department_answer_id); $i++) {

                            AuditDepartmentAnswer::where('id', $request->audit_department_answer_id[$i])->update([
                                'department_hod_status' => $request->department_hod_status[$i],
                                'department_hod_remark' => $request->department_hod_remark[$i]
                            ]);
                        }
                    }

                    $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                    Audit::where('id', $request->audit_id)->update([
                        'status' => ($auditStatus > 7) ? $auditStatus : 8
                    ]);

                    DB::commit();
                    return response()->json(['success' => 'Answer updated successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    response()->json(['error' => 'Something went wrong!']);
                }
            } else if (Auth::user()->hasRole('Auditor')) {
                DB::beginTransaction();
                try {
                    if (isset($request->audit_department_answer_id) && count($request->audit_department_answer_id) > 0) {
                        for ($i = 0; $i < count($request->audit_department_answer_id); $i++) {

                            AuditDepartmentAnswer::where('id', $request->audit_department_answer_id[$i])->update([
                                'auditor_status' => $request->auditor_status[$i],
                                'auditor_remark' => $request->auditor_remark[$i]
                            ]);
                        }
                    }

                    $auditStatus = Audit::where('id', $request->audit_id)->value('status');

                    Audit::where('id', $request->audit_id)->update([
                        'status' => ($auditStatus > 9) ? $auditStatus : 10
                    ]);

                    DB::commit();
                    return response()->json(['success' => 'Answer updated successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    response()->json(['error' => 'Something went wrong!']);
                }
            } else {
                response()->json(['error' => 'Something went wrong!']);
            }
        }
    }


    public function answeredQuestions(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
            ->where('status', '>=', 9)
            ->whereHas('assignedAuditors', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('admin.answered-questions')->with([
            'audits' => $audits,
            'departments' => $departments,
            'zones' => $zones,
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
