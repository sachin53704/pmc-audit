<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\AssignAuditorRequest;
use App\Http\Requests\Admin\StoreAuditRequest;
use App\Http\Requests\Admin\UpdateAuditRequest;
use App\Models\Audit;
use App\Models\Department;
use App\Models\User;
use App\Models\UserAssignedAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClerkAuditController extends Controller
{
    public function index()
    {
        $departments = Department::get();
        $audits = Audit::latest()->get();

        return view('admin.clerk-audits')->with(['audits'=> $audits, 'departments' => $departments]);
    }


    public function create()
    {
        //
    }


    public function store(StoreAuditRequest $request)
    {
        try
        {
            $input = $request->validated();
            $input['file_path'] = 'storage/file/'.$request->file->store('', 'file');
            $input['audit_no'] = Audit::generateAuditNo();

            Audit::create( Arr::only( $input, Audit::getFillables() ) );

            return response()->json(['success'=> 'Audit uploaded successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'uploading', 'Audit file');
        }
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Audit $audit)
    {
        $fileHtml = '
            <a  class="btn btn-primary btn-md px-2 mt-2" href="'.asset($audit->file_path).'" target="_blank" >View File</a>
        ';

        $departments = Department::get();
        $departmentHtml = '<span>
                <option value="">--Select Department --</option>';
                foreach($departments as $department):
                    $is_select = $department->id == $audit->department_id ? "selected" : "";
                    $departmentHtml .= '<option value="'.$department->id.'" '.$is_select.'>'.$department->name.'</option>';
                endforeach;
        $departmentHtml .= '</span>';

        $response = [
            'result' => 1,
            'audit' => $audit,
            'departmentHtml' => $departmentHtml,
            'fileHtml' => $fileHtml,
        ];

        return $response;
    }


    public function update(UpdateAuditRequest $request, Audit $audit)
    {
        try
        {
            $input = $request->validated();
            if($request->file)
            {
                $input['file_path'] = $request->file ? 'storage/file/'.$request->file->store('', 'file') : $audit->file_path;
                if(Storage::disk('file')->exists($audit->file_path))
                    Storage::disk('file')->delete($audit->file_path);
            }
            if( $audit->reject_reason != null )
            {
                $input['status'] = 1;
            }
            $audit->update( Arr::only( $input, Audit::getFillables() ) );

            return response()->json(['success'=> 'Audit file updated successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'updating', 'Audit file');
        }
    }


    public function destroy(Audit $audit)
    {
        try
        {
            $audit->delete();

            return response()->json(['success'=> 'Audit file deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Audit file');
        }
    }

}
