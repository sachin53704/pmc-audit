<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_audit', 0)->get();

        $signatures = Signature::with('department')->get();

        return view('master.signature')->with([
            'signatures' => $signatures,
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|unique:signatures,department_id',
            'images' => 'required'
        ], [
            'department_id.unique' => 'Signature Already present for this department',
            'images.required' => 'Please select image'
        ]);

        if ($request->hasFile('images')) {
            $request['image'] = $request->images->store('signature');
        }

        Signature::create($request->all());

        return response()->json([
            'success' => 'Signature created successfully'
        ]);
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            return response()->json([
                'signature' => Signature::find($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $request->validate([
                'department_id' => "nullable|unique:signatures,department_id,$id,id",
                'images' => 'nullable'
            ], [
                'department_id.unique' => 'Signature Already present for this department',
                'images.required' => 'Please select image'
            ]);

            $signature = Signature::find($id);

            if ($request->hasFile('images')) {
                if ($signature->image && Storage::exists($signature->image)) {
                    Storage::delete($signature->image);
                }

                $request['image'] = $request->images->store('signature');
            }

            $signature->update($request->all());

            return response()->json([
                'success' => 'Signature Updated Successfully'
            ]);
        }
    }
}
