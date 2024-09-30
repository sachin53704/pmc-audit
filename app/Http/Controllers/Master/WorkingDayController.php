<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingDay;
use App\Http\Requests\WorkingDayRequest;
use App\Models\Department;

class WorkingDayController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_audit', 0)->get();

        $workingDays = WorkingDay::select('id', 'name', 'status')->get();

        return view('master.working-day')->with([
            'departments' => $departments,
            'workingDays' => $workingDays,
        ]);
    }

    public function store(WorkingDayRequest $request)
    {
        try {
            if ($request->ajax()) {
                $workingDay = WorkingDay::create($request->all());

                if ($workingDay) {
                    return response()->json(['success' => 'Working day created successfully!']);
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
                'workingDay' => WorkingDay::find($id)
            ]);
        }
    }

    public function update(WorkingDayRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $workingDay = WorkingDay::find($id);

                $workingDay->update($request->all());

                if ($workingDay) {
                    return response()->json(['success' => 'Working day updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
