<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SignatureController extends Controller
{
    public function edit(Request $request){
        $signature = Setting::where([
            'name' => 'signature',
            'is_type' => 2
        ])->value('value');

        return view('master.signature')->with([
            'signature' => $signature
        ]);
    }

    public function update(Request $request){

        if($request->ajax()){
            if($request->hasFile('file')){
                $file = $request->file->store('signature');

                Setting::updateOrCreate([
                    'name' => 'signature'
                ], [
                    'name' => 'signature',
                    'value' => $file,
                    'is_type' => 2
                ]);
            }

            return response()->json([
                'success' => 'Signature Updated Successfully'
            ]);
        }

    }
}
