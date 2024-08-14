<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\AuditObjection;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function finalReport(Request $request)
    {
        $audits = Audit::query()
            ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
            ->withCount([
                'objections as approved' => fn($q) => $q->where('status', 4),
                'objections as unapproved' => fn($q) => $q->where('status', 5),
            ])
            // ->where('department_id', Auth::user()->department_id)
            ->latest()
            ->get();

        return view('admin.report.final-report')->with(['audits' => $audits]);
    }


    public function paraAuditReport(Request $request)
    {
        $audits = Audit::query()
            ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                $q->withCount([
                    'objections as unapproved' => fn($q) => $q->where('auditor_action_status', 2)->where('user_id', Auth::user()->id),
                ]);
            })->when(Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'), function ($q) {
                $q->withCount([
                    'objections as unapproved' => fn($q) => $q->where('status', 5),
                ]);
            })
            // ->where('department_id', Auth::user()->department_id)
            ->latest()
            ->get();

        return view('admin.report.para-audit-report')->with(['audits' => $audits]);
    }

    public function complienceAnswerReport(Request $request)
    {
        $audits = Audit::query()
            ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                $q->withCount([
                    'objections as approved' => fn($q) => $q->where('auditor_action_status', 1)->where('user_id', Auth::user()->id),
                ]);
            })->when(Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'), function ($q) {
                $q->withCount([
                    'objections as approved' => fn($q) => $q->where('status', 4),
                ]);
            })
            // ->where('department_id', Auth::user()->department_id)
            ->latest()
            ->get();

        return view('admin.report.complience-answer-report')->with(['audits' => $audits]);
    }

    public function getResponseQuestion(Request $request)
    {
        if ($request->ajax()) {
            $objection = AuditObjection::with(['user'])
                ->when(Auth::user()->hasRole('Auditor'), function ($q) use ($request) {
                    $q->where([
                        'audit_id' => $request->id,
                        'auditor_action_status' => $request->status
                    ]);
                })->when(Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'), function ($q) use ($request) {
                    $q->where([
                        'audit_id' => $request->id,
                        'status' => 4
                    ]);
                })->get();

            return response()->json([
                'objection' => $objection
            ]);
        }
    }

    public function getUnanswerQuestion(Request $request)
    {
        if ($request->ajax()) {
            $objection = AuditObjection::with(['user'])
                ->when(Auth::user()->hasRole('Auditor'), function ($q) use ($request) {
                    $q->where([
                        'audit_id' => $request->id,
                        'auditor_action_status' => $request->status
                    ]);
                })->when(Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'), function ($q) use ($request) {
                    $q->where([
                        'audit_id' => $request->id,
                        'status' => 5
                    ]);
                })->get();

            return response()->json([
                'objection' => $objection
            ]);
        }
    }

    public function departmentWiseProgramAudit(Request $request)
    {

        $objections = Department::when(Auth::user()->hasRole('Auditor'), function ($q) use ($request) {
            $q->withCount([
                'auditObjections as unapproved' => fn($q) => $q->whereNull('answer')->where('user_id', Auth::user()->id)->where('auditor_action_status', 0),
                'auditObjections as approved' => fn($q) => $q->where('auditor_action_status', 1)->where('user_id', Auth::user()->id),
            ])->whereHas('audit', fn($q) => $q->where('department_id', Auth::user()->department_id));
        })->when(Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'), function ($q) use ($request) {
            $q->withCount([
                'auditObjections as approved' => fn($q) => $q->where('status', 4),
                'auditObjections as unapproved' => fn($q) => $q->whereNull('answer')->where('status', 5),
            ]);
        })->get();

        return view('admin.report.department-report')->with([
            'objections' => $objections
        ]);
    }
}
