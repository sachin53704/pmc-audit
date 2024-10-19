<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Audit;
use App\Models\Department;
use App\Models\Zone;
use App\Models\FiscalYear;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\AuditParaCategory;
use App\Models\AuditObjection;
use Illuminate\Support\Facades\DB;

class HmmMcaStatusController extends Controller
{
    public function getHmmMCAData(Request $request)
    {
        $audits = Audit::query()
            ->when(Auth::user()->hasRole('MCA'), function ($q) {
                $q->whereHas('objections', function ($q) {
                    $q->where('dymca_status', 1)
                        ->where('is_draft_send', 1);
                });
            })
            ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                $q->whereHas('objections', function ($q) {
                    $q->whereNull('mca_status')

                        ->when(function ($q) {
                            $q->where('is_draft_send', 1)->whereNull('dymca_status')
                                ->orWhere('dymca_status', 2);
                        });
                });
            })
            ->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                $q->whereHas('objections', function ($q) {
                    $q->where('is_draft_send', 1);
                })
                    ->where('department_id', Auth::user()->department_id);
            })
            ->latest()
            ->get();

        $departments = Department::select('id', 'name')->get();

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('mca.hmm.status')->with([
            'audits' => $audits,
            'zones' => $zones,
            'departments' => $departments,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }

    public function storeHmmMCAData(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());
            if (Auth::user()->hasRole('MCA')) {
                try {
                    DB::beginTransaction();
                    $auditObjection = AuditObjection::find($request->audit_objection_id);
                    $auditObjection->mca_status = $request->mca_status;
                    $auditObjection->mca_remark = $request->mca_remark;
                    if ($auditObjection->status < 3 && $request->mca_status) {
                        $auditObjection->status = 3;
                    }
                    $auditObjection->save();

                    if ($request->mca_status == 1) {
                        $audit = Audit::find($request->audit_id);
                        if ($audit->status <= 6) {
                            $audit->status = 6;
                            $audit->save();
                        }
                    }
                    DB::commit();
                    return response()->json(['success' => 'Objection status updated successful']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong!']);
                }
            } elseif (Auth::user()->hasRole('DY MCA')) {
                try {
                    DB::beginTransaction();
                    $auditObjection = AuditObjection::find($request->audit_objection_id);
                    $auditObjection->dymca_status = $request->dymca_status;
                    $auditObjection->dymca_remark = $request->dymca_remark;
                    if ($auditObjection->status < 2 && $request->dymca_status) {
                        $auditObjection->status = 2;
                    }
                    $auditObjection->save();
                    DB::commit();
                    return response()->json(['success' => 'Objection status updated successful']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong!']);
                }
            } else {
                AuditObjection::where('id', $request->audit_objection_id)
                    ->update([
                        'is_department_hod_forward' => $request->is_department_hod_forward,
                        'department_hod_remark' => $request->department_hod_remark,
                    ]);

                $audit = Audit::find($request->audit_id);

                if ($audit->status <= 7) {
                    $audit->status = 7;
                    $audit->save();
                }

                return response()->json(['success' => 'Objection forward ro department successful']);
            }
        }
    }

    public function getAssignObjection(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['audit', 'auditType', 'department', 'severity'])
                ->where('audit_id', $request->audit_id)
                ->when(Auth::user()->hasRole('MCA'), function ($q) {
                    $q->where('dymca_status', 1);
                })
                ->when(Auth::user()->hasRole('Department HOD'), function ($q) {
                    $q->where('is_objection_send', 1);
                })
                ->get();

            $objectionHtml = "";
            if (count($auditObjections) > 0) {
                $objectionHtml .= '<div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Department</th>
                                <th>HMM No.</th>
                                <th>Subject</th>';

                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA') || Auth::user()->hasRole('Auditor')) {
                    $objectionHtml .= '
                    <th>DYMCA Status</th>
                    <th>DYMCA Remark</th>
                    <th>MCA Status</th>
                    <th>MCA Remark</th>';
                }

                $objectionHtml .= '<th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
            }

            $count = 1;
            foreach ($auditObjections as $auditObjection) {
                $objectionHtml .= '<tr>
                                <td>' . $count++ . '</td>
                                <td>' . $auditObjection?->department?->name . '</td>
                                <td>' . $auditObjection?->objection_no . '</td>
                                <td>' . $auditObjection?->subject . '</td>';
                if (Auth::user()->hasRole('MCA') || Auth::user()->hasRole('DY MCA')) {
                    $objectionHtml .= '
                    <th>' . (($auditObjection->dymca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->dymca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : "-")) . '</th>
                    <th>' . (($auditObjection->dymca_remark) ? $auditObjection->dymca_remark : '-') . '</th>
                    <th>' . (($auditObjection->mca_status == 1) ? '<span class="badge bg-success">Approve</span>' : (($auditObjection->mca_status == 2) ? '<span class="badge bg-warning">Forward To Auditor</span>' : (($auditObjection->mca_status == 3) ? '<span class="badge bg-primary">Forward to Department</span>' : '-'))) . '</th>
                    <th>' . (($auditObjection->mca_remark) ? $auditObjection->mca_remark : '-') . '</th>';
                }
                $objectionHtml .= '<td><button type="button" class="btn btn-sm btn-primary viewObjection" data-id="' . $auditObjection?->id . '">View Objection</button></td>
                            </tr>';
            }

            if (count($auditObjections) > 0) {
                $objectionHtml .= '</tbody>
                        </table>
                    </div>';
            }

            $audit = Audit::find($request->audit_id);

            return response()->json([
                'objectionHtml' => $objectionHtml,
                'department' => $audit->department_id,
                'departmentName' => $audit->department?->name,
            ]);
        }
    }
}
