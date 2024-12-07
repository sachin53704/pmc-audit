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
        $fiscalYears = FiscalYear::latest()->get();

        return view('master.fiscal_years')->with(['fiscalYears' => $fiscalYears]);
    }

    public function store(StoreFiscalYearRequest $request)
    {
        try {
            $fromFinancialYear = FiscalYear::whereDate('from_year', '<=', date('Y-m-d', strtotime($request->from_year)))
                ->whereDate('to_year', '>=', date('Y-m-d', strtotime($request->from_year)))
                ->exists();

            $toFinancialYear = FiscalYear::whereDate('from_year', '<=', date('Y-m-d', strtotime($request->to_year)))
                ->whereDate('to_year', '>=', date('Y-m-d', strtotime($request->to_year)))
                ->exists();

            if ($fromFinancialYear || $toFinancialYear) {
                return response()->json([
                    'error' => 'Financial year already exists'
                ]);
            }

            if ($request->status) {
                FiscalYear::where('status', 1)->update(['status' => 0]);
            }

            DB::beginTransaction();
            $request['from_year'] = date('Y-m-d', strtotime($request->from_year));
            $request['to_year'] = date('Y-m-d', strtotime($request->to_year));
            $request['name'] = date('y', strtotime($request->from_year)) . '-' . date('y', strtotime($request->to_year));

            FiscalYear::create($request->all());
            DB::commit();

            return response()->json(['success' => 'Financial year created successfully!']);
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
    public function update(UpdateFiscalYearRequest $request, $id)
    {
        try {
            // Format dates once
            $fromYear = date('Y-m-d', strtotime($request->from_year));
            $toYear = date('Y-m-d', strtotime($request->to_year));

            // Check for overlapping financial years
            $isOverlap = FiscalYear::where(function ($query) use ($fromYear, $toYear) {
                $query->whereDate('from_year', '<=', $toYear)
                    ->whereDate('to_year', '>=', $fromYear);
            })
                ->where('id', '!=', $request->edit_model_id)
                ->exists();

            if ($isOverlap) {
                return response()->json(['error' => 'Financial year already exists'], 422);
            }

            // Handle active status toggle
            if ($request->status) {
                FiscalYear::where('status', 1)->update(['status' => 0]);
            }

            // Update financial year
            DB::beginTransaction();

            $financialYear = FiscalYear::findOrFail($id);
            $financialYear->update([
                'from_year' => $fromYear,
                'to_year' => $toYear,
                'name' => date('y', strtotime($fromYear)) . '-' . date('y', strtotime($toYear)),
                'status' => $request->status ?? $financialYear->status,
            ]);

            DB::commit();

            return response()->json(['success' => 'Financial Year updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback on error
            return response()->json(['error' => 'An error occurred while updating the Financial Year', 'details' => $e->getMessage()], 500);
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
