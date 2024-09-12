<?php

namespace App\Http\Controllers;

use App\Models\AuditObjection;
use Illuminate\Http\Request;
use PDF;
use App\Models\Department;

class ReportController extends Controller
{
    public function getAuditParaSummaryReport(Request $request)
    {
        $departments = Department::select('id', 'name')->get();

        $reports = Department::when(isset($request->department) && $request->department != "", function ($q) use ($request) {
            $q->where('id', $request->department);
        })->withCount([
            'auditObjection as approved_para' => fn($q) => $q->where('mca_action_status', 1)->when(isset($request->from) && $request->from != "", function ($search) use ($request) {
                $search->where('entry_date', '>=', date('Y-m-d', strtotime($request->from)));
            })->when(isset($request->to) && $request->to != "", function ($search) use ($request) {
                $search->where('entry_date', '<=', date('Y-m-d', strtotime($request->to)));
            }),
            'auditObjection as pending_para' => fn($q) => $q->where('mca_action_status', '!=', 1)->when(isset($request->from) && $request->from != "", function ($search) use ($request) {
                $search->where('entry_date', '>=', date('Y-m-d', strtotime($request->from)));
            })->when(isset($request->to) && $request->to != "", function ($search) use ($request) {
                $search->where('entry_date', '<=', date('Y-m-d', strtotime($request->to)));
            }),
        ])
            ->withSum(['auditObjection as approved_subunit' => function ($q) use ($request) {
                $q->where('mca_action_status', 1)->when(isset($request->from) && $request->from != "", function ($search) use ($request) {
                    $search->where('entry_date', '>=', date('Y-m-d', strtotime($request->from)));
                })->when(isset($request->to) && $request->to != "", function ($search) use ($request) {
                    $search->where('entry_date', '<=', date('Y-m-d', strtotime($request->to)));
                });
            }], 'sub_unit')
            ->withSum(['auditObjection as pending_subunit' => function ($q) use ($request) {
                $q->where('mca_action_status', '!=', 1)->when(isset($request->from) && $request->from != "", function ($search) use ($request) {
                    $search->where('entry_date', '>=', date('Y-m-d', strtotime($request->from)));
                })->when(isset($request->to) && $request->to != "", function ($search) use ($request) {
                    $search->where('entry_date', '<=', date('Y-m-d', strtotime($request->to)));
                });
            }], 'sub_unit')->get();


        if (isset($request->pdf) && $request->pdf == "Yes") {

            $department = "All";
            if (isset($request->department) && $request->department != "") {
                $department = Department::where('id', $request->department)->value('name');
            }
            $pdf = PDF::loadView('report.audit-para-summary.pdf', compact('reports', 'department'));

            return $pdf->stream('audit-para-summary.pdf');
        } else {
            return view('report.audit-para-summary.index')->with([
                'departments' => $departments,
                'reports' => $reports
            ]);
        }
    }

    public function departmentWiseReport(Request $request)
    {
        $departments = Department::select('id', 'name')->get();

        $reports = AuditObjection::with(['department', 'auditParaCategory'])
            ->when(isset($request->department) && $request->department != "", function ($q) use ($request) {
                $q->where('department_id', $request->department);
            })->when(isset($request->from) && $request->from != "", function ($search) use ($request) {
                $search->where('entry_date', '>=', date('Y-m-d', strtotime($request->from)));
            })->when(isset($request->to) && $request->to != "", function ($search) use ($request) {
                $search->where('entry_date', '<=', date('Y-m-d', strtotime($request->to)));
            })->get();

        if (isset($request->pdf) && $request->pdf == "Yes") {

            $department = "All";
            if (isset($request->department) && $request->department != "") {
                $department = Department::where('id', $request->department)->value('name');
            }
            $pdf = PDF::loadView('report.department-report.pdf', compact('reports', 'department'));

            return $pdf->stream('department-report.pdf');
        } else {
            return view('report.department-report.index')->with([
                'departments' => $departments,
                'reports' => $reports
            ]);
        }
    }
}
