<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditObjection;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class ObjectionAjaxController extends Controller
{
    public function viewAuditorObjections(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['department', 'audit'])
                ->where('audit_id', $request->audit_id)
                ->when(Auth::user()->hasRole('Department'), function ($q) {
                    $q->where('is_objection_send', 1)
                        ->where('status', '>=', 5);
                })
                ->when(Auth::user()->hasRole('DY MCA'), function ($q) use ($request) {
                    if (isset($request->status)) {
                        $q->where('status', '>=', $request->status);
                    } else {
                        $q->where('status', '>=', 9);
                    }
                })
                ->when(Auth::user()->hasRole('MCA'), function ($q) use ($request) {
                    if (isset($request->status)) {
                        $q->where('status', '>=', $request->status);
                    } else {
                        $q->where('status', '>=', 7);
                    }
                })
                ->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where('is_department_draft_save', 0)
                        ->whereNotNull('department_remark')
                        ->where('status', '>=', 6);
                })
                ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                    $q->where('user_id', Auth::user()->id);
                    if (isset($request->status)) {
                        $q->where('status', '>=', 1);
                    } else {
                        $q->where('status', '>=', 8);
                    }
                })
                ->get();

            $audit = Audit::find($request->audit_id);
            return response()->json([
                'auditObjections' => $auditObjections,
                'department' => $audit->department_id,
                'departmentName' => $audit->department?->name
            ]);
        }
    }

    public function getDymcaSendObjections(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['department'])
                ->where('audit_id', $request->audit_id)
                ->where('is_objection_send', 0)
                ->where('status', '>=', 4)
                ->get();

            return response()->json([
                'auditObjections' => $auditObjections
            ]);
        }
    }
}
