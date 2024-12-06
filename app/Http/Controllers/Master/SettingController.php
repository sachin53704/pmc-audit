<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\SettingRequest;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::select('id', 'value', 'name')->get();

        return view('master.setting')->with([
            'settings' => $settings
        ]);
    }

    public function store(SettingRequest $request)
    {
        if ($request->ajax()) {
            try {
                Setting::create($request->all());

                return response()->json(['success' => 'Setting created successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => 'Something went wrong, please try again later']);
            }
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $setting = Setting::find($id);

            return response()->json([
                'status' => 200,
                'setting' => $setting
            ]);
        }
    }

    public function update(SettingRequest $request, $id)
    {
        if ($request->ajax()) {
            try {
                $setting = Setting::find($id);

                $setting->update($request->all());

                return response()->json([
                    'success' => 'Setting updated successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Something went wrong, please try again'
                ]);
            }
        }
    }
}
