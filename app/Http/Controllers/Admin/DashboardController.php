<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\UserAssignedAudit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $userRole = $user->roles()->get()[0];

        if($userRole->name == "Clerk")
        {
            $totalAuditCount = Audit::where('department_id', $user->department_id)->count();
            $approvedAuditCount = Audit::where('department_id', $user->department_id)->where(['status' => Audit::AUDIT_STATUS_APPROVED])->count();
            $rejectedAuditCount = Audit::where('department_id', $user->department_id)->where(['status' => Audit::AUDIT_STATUS_REJECTED])->count();

            return view('admin.dashboard.clerk')->with([
                        'totalAuditCount' => $totalAuditCount,
                        'approvedAuditCount' => $approvedAuditCount,
                        'rejectedAuditCount' => $rejectedAuditCount
                    ]);
        }
        elseif($userRole->name == "MCA" || $userRole->name == "DY MCA")
        {
            $pendingAuditCount = Audit::where(['status' => Audit::AUDIT_STATUS_PENDING])->count();
            $approvedAuditCount = Audit::where(['status' => Audit::AUDIT_STATUS_APPROVED])->count();
            $rejectedAuditCount = Audit::where(['status' => Audit::AUDIT_STATUS_REJECTED])->count();
            $draftAuditCount = Audit::where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)->count();

            return view('admin.dashboard.mca')->with([
                        'pendingAuditCount' => $pendingAuditCount,
                        'approvedAuditCount' => $approvedAuditCount,
                        'rejectedAuditCount' => $rejectedAuditCount,
                        'draftAuditCount' => $draftAuditCount
                    ]);
        }
        elseif($userRole->name == "Department")
        {
            $totalDepartmentLetters = Audit::where('department_id', $user->department_id)->whereNot('dl_file_path', null)->count();

            return view('admin.dashboard.department')->with([
                        'totalDepartmentLetters' => $totalDepartmentLetters
                    ]);
        }
        elseif($userRole->name == "Auditor")
        {
            $totalAssignedAudits = UserAssignedAudit::where('user_id', $user->id)->count();
            $totalHmmList = Audit::query()
                            ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))
                            ->where('status', '>=', Audit::AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT)->count();
            $totalAnsweredQuestions = Audit::query()
                        ->where('status', Audit::AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE)
                        ->whereHas('assignedAuditors', fn ($q) => $q->where('user_id', $user->id))->count();

            return view('admin.dashboard.auditor')->with([
                        'totalAssignedAudits' => $totalAssignedAudits,
                        'totalHmmList' => $totalHmmList,
                        'totalAnsweredQuestions' => $totalAnsweredQuestions,
                    ]);
        }

        return view('admin.dashboard');
    }

    public function changeThemeMode()
    {
        $mode = request()->cookie('theme-mode');

        if($mode == 'dark')
            Cookie::queue('theme-mode', 'light', 43800);
        else
            Cookie::queue('theme-mode', 'dark', 43800);

        return true;
    }
}
