<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Severity;
use App\Http\Requests\SeverityRequest;

class SeverityController extends Controller
{
    public function index()
    {
        $severity = Severity::select('id', 'name', 'status')->get();

        return view('master.severity')->with([
            'severity' => $severity
        ]);
    }

    public function store(SeverityRequest $request)
    {
        try {
            if ($request->ajax()) {
                $severity = Severity::create($request->all());

                if ($severity) {
                    return response()->json(['success' => 'Severity created successfully!']);
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
                'severity' => Severity::find($id)
            ]);
        }
    }

    public function update(SeverityRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $severity = Severity::find($id);

                $severity->update($request->all());

                if ($severity) {
                    return response()->json(['success' => 'Severity updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
