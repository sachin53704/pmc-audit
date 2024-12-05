<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index()
    {
        $signatures = Signature::all();

        return view('master.signature')->with([
            'signatures' => $signatures
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:signatures,name',
            'images' => 'required',
            'status' => 'required'
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
                'name' => "required|unique:signatures,name,$id,id",
                'images' => 'nullable',
                'status' => 'required'
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
