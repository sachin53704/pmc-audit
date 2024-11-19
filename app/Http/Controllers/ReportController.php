<?php

namespace App\Http\Controllers;

use App\Models\AuditObjection;
use Illuminate\Http\Request;
use PDF;
use App\Models\Department;
use App\Models\PendingAuditObjection;
use App\Models\ParaAudit;

class ReportController extends Controller
{
    public function getAuditParaSummaryReport(Request $request)
    {
        $departments = Department::select('id', 'name')->where('is_audit', 0)->get();

        $reports = PendingAuditObjection::withWhereHas('auditObjection', function ($q) use ($request) {
            $q->when(isset($request->department) && $request->department != "", function ($q) use ($request) {
                $q->where('department_id', $request->department);
            })->when(isset($request->from) && $request->from != "", function ($q) use ($request) {
                $q->where('from_year', '>=', date('Y-m-d', strtotime($request->from)));
            })->when(isset($request->to) && $request->to != "", function ($q) use ($request) {
                $q->where('to_year', '<=', date('Y-m-d', strtotime($request->to)));
            })->with('audit', 'department', 'user');
        })->where('is_objection_completed', 0)->get();

        if (isset($request->pdf) && $request->pdf == "Yes") {

            $department = "All";
            if (isset($request->department) && $request->department != "") {
                $department = Department::where('id', $request->department)->value('name');
            }
            $pdf = PDF::loadView('report.audit-para-summary.pdf', compact('reports', 'department'));

            return $pdf->stream('para-current-status.pdf');
        } else {
            return view('report.audit-para-summary.index')->with([
                'departments' => $departments,
                'reports' => $reports
            ]);
        }
    }

    public function finalReport(Request $request)
    {
        $departments = Department::select('id', 'name')->where('is_audit', 0)->get();


        if (isset($request->pdf) && $request->pdf == "Yes") {

            // $reports = ParaAudit::with(['audit.department'])
            //     ->when(isset($request->department) && $request->department != "", function ($q) use ($request) {
            //         $q->whereHas('audit', function ($q) use ($request) {
            //             $q->where('department_id', $request->department);
            //         });
            //     })
            //     ->get();

            $reports = PendingAuditObjection::whereHas('auditObjection.audit.department', function ($q) use ($request) {
                $q->when(isset($request->department) && $request->department != "", function ($q) use ($request) {
                    $q->where('department_id', $request->department);
                })->when(isset($request->from) && $request->from != "", function ($q) use ($request) {
                    $q->where('from_year', '>=', date('Y-m-d', strtotime($request->from)));
                })->when(isset($request->to) && $request->to != "", function ($q) use ($request) {
                    $q->where('to_year', '<=', date('Y-m-d', strtotime($request->to)));
                });
            })->where('is_objection_completed', 0)->get();

            $department = "All";
            if (isset($request->department) && $request->department != "") {
                $department = Department::where('id', $request->department)->value('name');
            }
            $pdf = PDF::loadView('report.final-report.pdf', compact('reports', 'department'));

            return $pdf->stream('final-report.pdf');
        } else {
            return view('report.final-report.index')->with([
                'departments' => $departments,
            ]);
        }
    }

    public function paraCurrentStatusReport(Request $request)
    {
        $departments = Department::where('is_audit', 0)->get();

        $reports = [];
        if (isset($request->department) && $request->department != "") {
            $auditObjections = AuditObjection::when(isset($request->department) && $request->department != "", function ($q) use ($request) {
                $q->when(isset($request->department) && $request->department != "all", function ($q) use ($request) {
                    $q->where('audit_objections.department_id', $request->department);
                });
            })->when(isset($request->from) && $request->from != "", function ($q) use ($request) {
                $q->where('audit_objections.entry_date', '>=', $request->from);
            })->when(isset($request->to) && $request->to != "", function ($q) use ($request) {
                $q->where('audit_objections.entry_date', '<=', $request->to);
            })
                ->leftJoin('fiscal_years', 'fiscal_years.id', '=', 'audit_objections.from_year')
                ->leftJoin('departments', 'departments.id', '=', 'audit_objections.department_id')
                ->select('departments.name as dept_name', 'fiscal_years.name as from_year', 'audit_objections.sub_unit', 'audit_objections.completed_sub_unit', 'audit_objections.pending_sub_unit')
                ->get();

            $reports = $auditObjections->groupBy('dept_name');
        }

        if (isset($request->pdf) && $request->pdf == "Yes") {

            $department = "All";
            if (isset($request->department) && $request->department != "") {
                $department = Department::where('id', $request->department)->value('name');
            }
            $pdf = PDF::loadView('report.para-current-status.pdf', compact('reports', 'department'));

            return $pdf->stream('audit-para-summary.pdf');
        } else {
            return view('report.para-current-status.index')->with([
                'departments' => $departments,
                'reports' => $reports
            ]);
        }
    }
}
