<?php

namespace App\Http\Controllers\Admin\Masters;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Masters\StoreFiscalYearRequest;
use App\Http\Requests\Admin\Masters\UpdateFiscalYearRequest;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class FiscalYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fiscal_years = FiscalYear::latest()->get();

        return view('master.fiscal_years')->with(['fiscal_years' => $fiscal_years]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFiscalYearRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            FiscalYear::create(Arr::only($input, FiscalYear::getFillables()));
            DB::commit();

            return response()->json(['success' => 'Financial Year created successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'creating', 'Financial Year');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FiscalYear $fiscal_year)
    {
        if ($fiscal_year) {
            $response = [
                'result' => 1,
                'fiscal_year' => $fiscal_year,
            ];
        } else {
            $response = ['result' => 0];
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFiscalYearRequest $request, FiscalYear $fiscal_year)
    {
        try {
            DB::beginTransaction();
            $input = $request->validated();
            $fiscal_year->update(Arr::only($input, FiscalYear::getFillables()));
            DB::commit();

            return response()->json(['success' => 'Financial Year updated successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Financial Year');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FiscalYear $fiscal_year)
    {
        try {
            DB::beginTransaction();
            $fiscal_year->delete();
            DB::commit();

            return response()->json(['success' => 'Financial Year deleted successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'deleting', 'Financial Year');
        }
    }
}
