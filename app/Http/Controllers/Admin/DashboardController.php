<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
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
            $totalAuditCount = Audit::count();
            $approvedAuditCount = Audit::where(['status' => Audit::AUDIT_STATUS_APPROVED])->count();
            $rejectedAuditCount = Audit::where(['status' => Audit::AUDIT_STATUS_REJECTED])->count();

            return view('admin.dashboard.clerk')->with([
                        'totalAuditCount' => $totalAuditCount,
                        'approvedAuditCount' => $approvedAuditCount,
                        'rejectedAuditCount' => $rejectedAuditCount
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
