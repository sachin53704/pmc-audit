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

class AuditorAuditController extends Controller
{
    public function assignedAuditList()
    {
        $user = Auth::user();

        $audits = Audit::query()
                        ->where('status', '>=', Audit::AUDIT_STATUS_AUDITOR_ASSIGNED)
                        ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                        ->latest()
                        ->get();

        return view('admin.assigned-audit-list')->with(['audits' => $audits]);
    }


    public function getAuditInfo(Request $request)
    {
        $audit = Audit::query()
                        ->when($request->relations, fn($q) => $q->with($request->relations))
                        ->with('department')
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
        try
        {
            $audit = Audit::with('department')->where('id', $request->audit_id)->first();
            DB::beginTransaction();

            $filPath = 'storage/file/'.$request->letter_file->store('', 'file');
            $audit->update(['dl_description' => $request->description, 'dl_file_path' => $filPath, 'status' => Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT]);

            DB::commit();

            return response()->json(['success'=> 'Letter successfully sent to department']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'sending letter to', 'department');
        }
    }


    public function createObjection(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
                        ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                        ->where('status', '>=', Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT)
                        ->latest()
                        ->get();

        return view('admin.create-objection')->with(['audits' => $audits]);
    }


    public function storeObjection(Request $request)
    {
        $fieldArray['audit_id'] = 'required';
        $fieldArray['date'] = 'required';
        $fieldArray['subject'] = 'required';
        $messageArray['audit_id.required'] = 'Please enter audit id';
        $messageArray['date.required'] = 'Please enter date';
        $messageArray['subject.required'] = 'Please enter subject';

        for($i=0; $i<$request->question_count; $i++)
        {
            $fieldArray['objection_' . $i] = 'required';
            $fieldArray['objection_no_' . $i] = 'required';
            $messageArray['objection_' . $i . '.required'] = 'Please type objection';
            $messageArray['objection_no_' . $i . '.required'] = 'Objection no is missing';
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        try
        {
            DB::beginTransaction();
            $audit = Audit::where('id', $request->audit_id)->first();

            $audit->update([
                'obj_date' => $request->date,
                'obj_subject' => $request->subject,
                'status' => Audit::AUDIT_STATUS_AUDITOR_ADDED_OBJECTION,
            ]);

            for($i=0; $i<$request->question_count; $i++)
            {
                $reqParamName = 'objection_'.$i;
                $objNoParamName = 'objection_no_'.$i;

                AuditObjection::updateOrCreate([
                    'objection_no' => $request->{$objNoParamName},
                    'audit_id' => $audit->id,
                ],[
                    'objection' => $request->{$reqParamName},
                ]);
            }
            DB::commit();

            return response()->json(['success'=> 'Objection created successfully']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'objection');
        }
    }


    public function answeredQuestions(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
                        ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
                        ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                        ->latest()
                        ->get();

        return view('admin.answered-questions')->with(['audits' => $audits]);
    }


    public function answerDetails(Request $request, Audit $audit)
    {
        $audit->load(['objections' => fn($q) => $q->with([
            'auditorApprover' => fn($q) => $q->first()?->append('full_name'),
            'answeredBy' => fn($q) => $q->first()?->append('full_name')
        ])]);

        $isEditable = Auth::user()->hasRole(['Auditor']) ? 'readonly' : '';

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
            $isAuditorReadonly = (($objection->auditor_action_status == 1 && ($objection->mca_action_status == 0 || $objection->mca_action_status == 1)) ? "readonly" : "");
            $innerHtml .= '
            <div class="row custm-card">
                <input type="hidden" name="objection_id[]" value="'.$objection->id.'">
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="objection_'.$key.'">(Objection '.$objection->objection_no.')</label>
                    <textarea name="objection_'.$key.'" id="objection_'.$key.'" class="form-control" readonly cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->objection.'</textarea>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="compliance_'.$key.'">Compliance <span class="text-danger">*</span></label>
                    <textarea name="compliance_'.$key.'" '.$isEditable.' class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->answer.'</textarea>
                    <span class="text-danger is-invalid compliance_'.$key.'_err"></span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="remark_'.$key.'">Remark <span class="text-danger">*</span></label>
                    <input type="text" name="remark_'.$key.'" '.$isEditable.' value="'.$objection->remark.'" class="form-control">
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
                    <label class="col-form-label" for="action_'.$key.'">Auditor Action</label>
                    <select name="action_'.$key.'" class="form-control" '.$isAuditorReadonly.'>
                        <option value="">Action</option>
                        <option value="1" '.($objection->auditor_action_status == 1 ? "selected" : "").'>Approve</option>
                        <option value="2" '.($objection->auditor_action_status == 2 ? "selected" : "").'>Reject</option>
                    </select>
                    <span class="text-danger is-invalid action_'.$key.'_err"></span>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="action_remark_'.$key.'">Auditor Remark</label>
                    <textarea name="action_remark_'.$key.'" class="form-control" '.$isAuditorReadonly.' cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->auditor_remark.'</textarea>
                    <span class="text-danger is-invalid action_'.$key.'_err"></span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="col-form-label" for="mca_action_'.$key.'">MCA Action</label>
                    <select name="mca_action_'.$key.'" readonly class="form-control">
                        <option value="">Action</option>
                        <option value="1" '.($objection->mca_action_status == 1 ? "selected" : "").'>Approve</option>
                        <option value="2" '.($objection->mca_action_status == 2 ? "selected" : "").'>Reject</option>
                    </select>
                    <span class="text-danger is-invalid mca_action_'.$key.'_err"></span>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="col-form-label" for="mca_action_remark_'.$key.'">MCA Remark</label>
                    <textarea name="mca_action_remark_'.$key.'" readonly class="form-control" cols="10" rows="5" style="max-height: 120px; min-height: 120px">'.$objection->mca_remark.'</textarea>
                    <span class="text-danger is-invalid mca_action_'.$key.'_err"></span>
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


    public function approveAnswer(Request $request, Audit $audit)
    {
        $fieldArray['objection_id'] = 'required';
        $messageArray['objection_id.required'] = 'Objection no not found';

        for($i=0; $i<count($request->objection_id); $i++)
        {
            if($request->{'action_'.$i})
            {
                $fieldArray['compliance_' . $i] = 'required';
                $fieldArray['remark_' . $i] = 'required';
                $fieldArray['action_remark_' . $i] = 'required';
                $messageArray['compliance_' . $i . '.required'] = 'Please type compliance';
                $messageArray['remark_' . $i . '.required'] = 'Please type remark';
                $messageArray['action_remark_' . $i . '.required'] = 'Please type approve/reject remark';
            }
        }
        $validator = Validator::make($request->all(), $fieldArray, $messageArray);

        if($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);


        try
        {
            DB::beginTransaction();

            for($i=0; $i<count($request->objection_id); $i++)
            {
                if($request->{'action_'.$i})
                {
                    $actionParamName = 'action_'.$i;
                    $actionRemarkParamName = 'action_remark_'.$i;
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

            return response()->json(['success'=> 'Action successfully']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'taking', 'action');
        }
    }
}
