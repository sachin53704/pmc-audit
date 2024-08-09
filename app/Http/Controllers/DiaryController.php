<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diary;
use App\Http\Requests\DiaryRequest;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{
    public function index()
    {
        $diaries = Diary::with(['user'])->when(Auth::user()->hasRole('AG Audit') || Auth::user()->hasRole('Auditor'), function ($q) {
            return $q->where('user_id', Auth::user()->id);
        })->get();

        return view('admin.diary.index')->with([
            'diaries' => $diaries
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
}
