<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diary;
use App\Http\Requests\DiaryRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkingDay;
use App\Models\Department;

class DiaryController extends Controller
{
    public function index()
    {
        $diaries = Diary::with(['user'])->when(Auth::user()->hasRole('AG Audit') || Auth::user()->hasRole('Auditor'), function ($q) {
            return $q->where('user_id', Auth::user()->id);
        })->get();

        $departments = Department::where('is_audit', 0)->get();

        $workingDays = WorkingDay::select('id', 'name', 'status')->get();

        return view('admin.diary.index')->with([
            'diaries' => $diaries,
            'departments' => $departments,
            'workingDays' => $workingDays
        ]);
    }

    public function store(DiaryRequest $request)
    {
        try {
            if ($request->ajax()) {
                $request['user_id'] = Auth::user()->id;
                $diary = Diary::create($request->all());

                if ($diary) {
                    return response()->json(['success' => 'Diary created successfully!']);
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
                'diary' => Diary::find($id)
            ]);
        }
    }

    public function update(DiaryRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $diary = Diary::find($id);
                $request['dymca_status'] = null;
                $request['mca_status'] = null;
                $diary->update($request->all());

                if ($diary) {
                    return response()->json(['success' => 'Diary updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $diary = Diary::find($id);

                if ($diary->delete()) {
                    return response()->json(['success' => 'Diary removed successfully!']);
                }
            } catch (\Exception $e) {
                return $this->respondWithAjax($e);
            }
        }
    }


    public function diaryStatus(Request $request)
    {
        if ($request->ajax()) {
            $diary = Diary::find($request->id);
            if (Auth::user()->hasRole('MCA')) {
                $diary->mca_status = $request->status;
            }

            if (Auth::user()->hasRole('DY MCA')) {
                $diary->dymca_status = $request->status;
            }

            if ($diary->save()) {
                return response()->json(['success' => 'Diary status updated successfully!']);
            } else {
                return response()->json(['success' => 'Something went wrong!']);
            }
        }
    }
}
