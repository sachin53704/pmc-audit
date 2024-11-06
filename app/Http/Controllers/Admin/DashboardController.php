<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Receipt;
use App\Models\SubPaymentReceipt;
use App\Models\SubReceipt;
use App\Models\PaymentReceipt;
use App\Models\UserAssignedAudit;
use App\Models\PendingAuditObjection;
use App\Models\AuditObjection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PDF;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $userRole = $user->roles()->get()[0];

        if ($userRole->name == "Clerk") {

            $audits = AuditObjection::query()->with(['department'])->where('mca_status', 1)
                ->where('is_objection_send', 0)->withWhereHas('audit', function ($q) {
                    $q->where('status', '>=', 6);
                })
                ->where('status', '3')
                ->whereNull('hmm_draft_number')
                ->latest()->get();

            return view('dashboard.clerk')->with([
                'audits' => $audits,
            ]);
        } elseif ($userRole->name == "MCA" || $userRole->name == "DY MCA") {
            $hmms = AuditObjection::query()->with('department')->withWhereHas('audit', function ($q) {
                $q->where('status', '>=', 5);
            })->when(Auth::user()->hasRole('MCA'), function ($q) {
                $q->whereNull('mca_status')
                    ->where('dymca_status', 1)
                    ->where('is_draft_send', 1);
            })->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                $q->whereNull('dymca_status')
                    ->where('is_draft_send', 1);
            })
                ->latest()
                ->get();

            $hmmDrafts = AuditObjection::query()->with(['department'])->where('mca_status', 1)
                ->where('is_objection_send', 1)->withWhereHas('audit', function ($q) {
                    $q->where('status', '>=', 6);
                })
                ->where('status', '4')
                ->whereNotNull('hmm_draft_number')
                ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                    $q->whereNull('hmm_draft_mca_status');
                })->when(Auth::user()->hasRole('MCA'), function ($q) {
                    $q->where('hmm_draft_dymca_status', 1)
                        ->whereNull('hmm_draft_mca_status');
                })
                ->latest()->get();

            $status = 0;
            if (Auth::user()->hasRole('MCA')) {
                $status = 9;
            } elseif (Auth::user()->hasRole('DY MCA')) {
                $status = 11;
            }
            $compliances = AuditObjection::with(['department', 'audit'])
                ->whereHas('audit', function ($q) use ($status) {
                    $q->where('status', '>=', $status);
                })
                ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                    $q->where('status', '>=', 9)
                        ->whereNull('dymca_final_status');
                })
                ->when(Auth::user()->hasRole('MCA'), function ($q) {
                    $q->where('status', '>=', 7)
                        ->whereNull('department_mca_second_status');
                })
                ->get();

            $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
                ->where('is_objection_completed', 0)
                ->when(Auth::user()->hasRole(['MCA']), function ($q) {
                    $q->where('status', '>=', 2)->whereNull('department_mca_second_status');
                })
                ->when(Auth::user()->hasRole(['DY MCA']), function ($q) {
                    $q->where('status', '>=', 4)->whereNull('dymca_final_status');
                })
                ->get();


            $columnName = strtolower(str_replace(' ', '_', $userRole->name));

            $pendingReceipts = Receipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 0);
            })->count();
            $approvedReceipts = Receipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 1);
            })->count();
            $rejectedReceipts = Receipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 2);
            })->count();

            $pendingPaymentReceipts = PaymentReceipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 0);
            })->count();
            $approvedPaymentReceipts = PaymentReceipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 1);
            })->count();
            $rejectedPaymentReceipts = PaymentReceipt::whereHas('subreceipts', function ($q) use ($columnName) {
                $q->where($columnName . '_status', 2);
            })->count();


            return view('dashboard.mca')->with([
                'hmms' => $hmms,
                'hmmDrafts' => $hmmDrafts,
                'compliances' => $compliances,
                'pendingAuditObjections' => $pendingAuditObjections,
                'pendingReceipts' => $pendingReceipts,
                'approvedReceipts' => $approvedReceipts,
                'rejectedReceipts' => $rejectedReceipts,
                'pendingPaymentReceipts' => $pendingPaymentReceipts,
                'approvedPaymentReceipts' => $approvedPaymentReceipts,
                'rejectedPaymentReceipts' => $rejectedPaymentReceipts,
            ]);
        } elseif ($userRole->name == "Department") {

            $hmmObjections = AuditObjection::with(['department', 'audit'])
                ->whereHas('audit', function ($q) {
                    $q->where('status', '>=', 7)
                        ->where('department_id', Auth::user()->department_id);
                })->where('is_department_hod_forward', 1)
                ->whereNull('compliance_submit_date')
                ->where('status', 5)
                ->get();

            $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
                ->where('is_objection_completed', 0)->where('status', '>=', 1)
                ->whereHas('auditObjection', function ($q) {
                    $q->where('department_id', Auth::user()->department_id);
                })
                ->get();

            $pendingReceipts = '';
            $approvedReceipts = '';
            $rejectedReceipts = '';
            $pendingPaymentReceipts = '';
            $approvedPaymentReceipts = '';
            $rejectedPaymentReceipts = '';

            if ($user->department_id == 1) {
                $pendingReceipts = SubReceipt::where('dy_auditor_status', 0)->distinct('receipt_id')->count();
                $approvedReceipts = SubReceipt::where('dy_auditor_status', 1)->distinct('receipt_id')->count();
                $rejectedReceipts = SubReceipt::where('dy_auditor_status', 2)->distinct('receipt_id')->count();

                $pendingPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 0)->distinct('payment_receipt_id')->count();
                $approvedPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 1)->distinct('payment_receipt_id')->count();
                $rejectedPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 2)->distinct('payment_receipt_id')->count();
            }

            return view('dashboard.department')->with([
                'user' => $user,
                'hmmObjections' => $hmmObjections,
                'pendingAuditObjections' => $pendingAuditObjections,
                'pendingReceipts' => $pendingReceipts,
                'approvedReceipts' => $approvedReceipts,
                'rejectedReceipts' => $rejectedReceipts,
                'pendingPaymentReceipts' => $pendingPaymentReceipts,
                'approvedPaymentReceipts' => $approvedPaymentReceipts,
                'rejectedPaymentReceipts' => $rejectedPaymentReceipts,
            ]);
        } elseif ($userRole->name == "Department HOD") {

            $hmms = AuditObjection::query()->with(['audit', 'department'])
                ->where('is_draft_send', 1)
                ->where('is_department_hod_forward', 0)
                ->where('hmm_draft_mca_status', 1)
                ->where('department_id', Auth::user()->department_id)
                ->latest()
                ->get();

            $compliances = AuditObjection::with(['department', 'audit'])
                ->whereHas('audit', function ($q) {
                    $q->where('status', '>=', 8);
                })
                ->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where('is_department_draft_save', 0)
                        ->whereNotNull('department_remark')
                        ->where('status', '>=', 6)
                        ->where('department_id', Auth::user()->department_id);
                })
                ->get();

            $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
                ->where('is_objection_completed', 0)->where('status', '>=', 1)->whereNull('department_hod_final_status')
                ->whereHas('auditObjection', function ($q) {
                    $q->where('department_id', Auth::user()->department_id);
                })
                ->get();

            return view('dashboard.department-hod')->with([
                'user' => $user,
                'hmms' => $hmms,
                'compliances' => $compliances,
                'pendingAuditObjections' => $pendingAuditObjections,
            ]);
        } elseif ($userRole->name == "Auditor") {
            $complianceObjections = AuditObjection::with(['department', 'audit'])
                ->whereHas('audit', function ($q) {
                    $q->where('status', '>=', 9)
                        ->whereHas('assignedAuditors', function ($q) {
                            $q->where('user_id', Auth::user()->id);
                        });
                })
                ->where('user_id', Auth::user()->id)->where('status', '>=', 8)
                ->whereNull('auditor_status')
                ->where('department_mca_second_status', 1)
                ->get();

            $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
                ->where('is_objection_completed', 0)
                ->whereNull('auditor_status')
                ->where('department_mca_second_status', 1)
                ->get();


            return view('dashboard.auditor')->with([
                'complianceObjections' => $complianceObjections,
                'pendingAuditObjections' => $pendingAuditObjections
            ]);
        } elseif ($userRole->name == "DY Auditor") {
            $pendingReceipts = SubReceipt::where('dy_auditor_status', 0)->distinct('receipt_id')->count();
            $approvedReceipts = SubReceipt::where('dy_auditor_status', 1)->distinct('receipt_id')->count();
            $rejectedReceipts = SubReceipt::where('dy_auditor_status', 2)->distinct('receipt_id')->count();

            $pendingPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 0)->distinct('payment_receipt_id')->count();
            $approvedPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 1)->distinct('payment_receipt_id')->count();
            $rejectedPaymentReceipts = SubPaymentReceipt::where('dy_auditor_status', 2)->distinct('payment_receipt_id')->count();

            return view('dashboard.dy-auditor')->with([
                'pendingReceipts' => $pendingReceipts,
                'approvedReceipts' => $approvedReceipts,
                'rejectedReceipts' => $rejectedReceipts,
                'pendingPaymentReceipts' => $pendingPaymentReceipts,
                'approvedPaymentReceipts' => $approvedPaymentReceipts,
                'rejectedPaymentReceipts' => $rejectedPaymentReceipts,
            ]);
        }

        return view('admin.dashboard');
    }

    public function changeThemeMode()
    {
        $mode = request()->cookie('theme-mode');

        if ($mode == 'dark')
            Cookie::queue('theme-mode', 'light', 43800);
        else
            Cookie::queue('theme-mode', 'dark', 43800);

        return true;
    }

    public function pdf()
    {
        $data = [
            'foo' => 'bar'
        ];

        $pdf = PDF::loadView('letter.4', $data);

        return $pdf->stream('document.pdf');
    }
}
