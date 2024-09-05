<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Http\Requests\ZoneRequest;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::select('id', 'name', 'status')->get();

        return view('master.zone')->with([
            'zones' => $zones
        ]);
    }

    public function store(ZoneRequest $request)
    {
        try {
            if ($request->ajax()) {
                $zone = Zone::create($request->all());

                if ($zone) {
                    return response()->json(['success' => 'Zone created successfully!']);
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
                'zone' => Zone::find($id)
            ]);
        }
    }

    public function update(ZoneRequest $request, $id)
    {
        try {
            if ($request->ajax()) {
                $zone = Zone::find($id);

                $zone->update($request->all());

                if ($zone) {
                    return response()->json(['success' => 'Zone updated successfully!']);
                }
            }
        } catch (\Exception $e) {
            return $this->respondWithAjax($e);
        }
    }
}
