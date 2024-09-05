<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditParaCategory;
use App\Http\Requests\AuditParaCategoryRequest;

class AuditParaCategoryController extends Controller
{
    public function index()
    {
        $auditParaCategory = AuditParaCategory::select('id', 'name', 'is_amount', 'status')->get();

        return view('master.audit-para-category')->with([
            'auditParaCategory' => $auditParaCategory
        ]);
    }

    public function store(AuditParaCategoryRequest $request)
    {
        try {
            if ($request->ajax()) {
                $auditParaCategory = AuditParaCategory::create($request->all());

                if ($auditParaCategory) {
                    return response()->json(['success' => 'Audit para category created successfully!']);
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
                'auditParaCategory' => AuditParaCategory::find($id)
            ]);
        }
    }

    public function update(AuditParaCategoryRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $auditParaCategory = AuditParaCategory::find($id);

                $auditParaCategory->update($request->all());

                if ($auditParaCategory) {
                    return response()->json(['success' => 'Audit para category updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
