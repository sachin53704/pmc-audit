<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\SendDepartmentLetterRequest;
use App\Models\Audit;
use App\Models\AuditObjection;
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
                        ->where('status', Audit::AUDIT_STATUS_AUDITOR_ASSIGNED)
                        ->latest()
                        ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                        ->get();

        return view('admin.assigned-audit-list')->with(['audits' => $audits]);
    }


    public function getAuditInfo(Request $request)
    {
        $audit = Audit::query()
                        ->when($request->relations, fn($q) => $q->with($request->relations))
                        ->with('department')
                        ->where('id', $request->audit_id)->first();

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
                        ->where('status', Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT)
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
                    'objection_no' => $request->{$objNoParamName}
                ],[
                    'audit_id' => $audit->id,
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
}
