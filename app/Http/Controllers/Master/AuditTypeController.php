<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditType;
use App\Http\Requests\AuditTypeRequest;

class AuditTypeController extends Controller
{
    public function index()
    {
        $auditTypes = AuditType::select('id', 'name', 'status')->get();

        return view('master.audit-type')->with([
            'auditTypes' => $auditTypes
        ]);
    }

    public function store(AuditTypeRequest $request)
    {
        try {
            if ($request->ajax()) {
                $auditType = AuditType::create($request->all());

                if ($auditType) {
                    return response()->json(['success' => 'Audit type created successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            return response()->json([
                'auditType' => AuditType::find($id)
            ]);
        }
    }

    public function update(AuditTypeRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $auditType = AuditType::find($id);

                $auditType->update($request->all());

                if ($auditType) {
                    return response()->json(['success' => 'Audit type updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
