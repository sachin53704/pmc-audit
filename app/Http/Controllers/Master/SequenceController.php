<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sequence;
use App\Models\Department;
use App\Models\FiscalYear;
use App\Http\Requests\SequenceRequest;

class SequenceController extends Controller
{
    public function index()
    {
        $sequences = Sequence::with(['department', 'financialYear'])->get();

        $departments = Department::select('id', 'name')->where('is_audit', 0)->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        return view('master.sequence')->with([
            'sequences' => $sequences,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears
        ]);
    }

    public function store(SequenceRequest $request)
    {
        try {
            if ($request->ajax()) {
                if ($request->status) {
                    Sequence::whereNotNull('created_at')->update(['status' => 0]);
                }
                $sequence = Sequence::create($request->all());

                if ($sequence) {
                    return response()->json(['success' => 'Sequence master created successfully!']);
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
                'sequence' => Sequence::find($id)
            ]);
        }
    }

    public function update(SequenceRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                if ($request->status) {
                    Sequence::whereNotNull('created_at')->update(['status' => 0]);
                }
                $sequence = Sequence::find($id);

                $sequence->update($request->all());

                if ($sequence) {
                    return response()->json(['success' => 'Sequence master updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
