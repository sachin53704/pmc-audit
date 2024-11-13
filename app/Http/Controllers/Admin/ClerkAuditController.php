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
use Illuminate\Support\Str;
use App\Models\FiscalYear;
use PDF;

class ClerkAuditController extends Controller
{
    public function index()
    {
        $departments = Department::get();
        $audits = Audit::latest()->get();
        $financialYears = FiscalYear::get();

        return view('program-audit.clerk.upload-program-audit')->with([
            'audits' => $audits,
            'departments' => $departments,
            'financialYears' => $financialYears
        ]);
    }


    public function create()
    {
        //
    }


    public function store(StoreAuditRequest $request)
    {
        try {
            DB::beginTransaction();
            $request['file_path'] = 'file';
            $request['dymca_status'] = 1;
            $request['audit_no'] = Audit::generateAuditNo();
            $request['audit_start_date'] = date('Y-m-d', strtotime($request->audit_start_date));

            $audit = Audit::create($request->all());

            $audits = Audit::with(['from', 'to', 'department'])->find($audit->id);
            $name = $this->generatePdf($audits);
            $audits->file_path = $name;
            $audits->save();
            DB::commit();
            return response()->json(['success' => 'Audit uploaded successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithAjax($e, 'uploading', 'Audit file');
        }
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Audit $audit)
    {
        $response = [
            'result' => 1,
            'audit' => $audit,
        ];

        return $response;
    }


    public function update(UpdateAuditRequest $request, Audit $audit)
    {
        try {

            $request['status'] = 1;
            $request['dymca_status'] = 1;
            $request['mca_status'] = null;
            $request['audit_start_date'] = date('Y-m-d', strtotime($request->audit_start_date));
            $audit->update($request->all());

            $audits = Audit::with(['from', 'to', 'department'])->find($audit->id);
            if (Storage::disk('public')->exists($audits->file_path)) {
                Storage::disk('public')->delete($audits->file_path);
            }

            $name = $this->generatePdf($audits);
            $audits->file_path = $name;
            $audits->save();

            return response()->json(['success' => 'Audit file updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Audit file');
        }
    }


    public function destroy(Audit $audit)
    {
        try {
            $audit->delete();

            return response()->json(['success' => 'Audit file deleted successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'deleting', 'Audit file');
        }
    }


    public function generatePdf($audit)
    {
        $pdf = PDF::loadView('letter.1', compact('audit'));

        $name = 'letter/' . Str::random(60) . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }
}
