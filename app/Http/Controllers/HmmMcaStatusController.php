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

class HmmMcaStatusController extends Controller
{
    public function getHmmMCAData(Request $request)
    {
        $user = Auth::user();

        $audits = Audit::query()
            ->when(Auth::user()->hasRole('MCA'), function ($q) {
                $q->whereHas('objections', function ($q) {
                    $q->where('dymca_status', 1);
                });
            })
            ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                $q->whereHas('objections', function ($q) {
                    $q->whereNull('mca_status')

                        ->when(function ($q) {
                            $q->whereNull('dymca_status')
                                ->orWhere('dymca_status', 2);
                        });
                });
            })
            ->latest()
            ->get();


        $departments = Department::select('id', 'name')->get();

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('hmm.status')->with([
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
                AuditObjection::where('id', $request->audit_objection_id)
                    ->update([
                        'mca_status' => $request->mca_status,
                        'mca_remark' => $request->mca_remark,
                    ]);

                return response()->json(['success' => 'Objection status updated  successful']);
            } else {
                AuditObjection::where('id', $request->audit_objection_id)
                    ->update([
                        'dymca_status' => $request->dymca_status,
                        'dymca_remark' => $request->dymca_remark,
                    ]);

                return response()->json(['success' => 'Objection status updated  successful']);
            }
        }
    }

    public function getAssignObjection(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['audit', 'auditType', 'zone', 'severity'])
                ->where('audit_id', $request->audit_id)
                ->when(Auth::user()->hasRole('MCA'), function ($q) {
                    $q->where('dymca_status', 1);
                })
                ->get();

            $objectionHtml = "";
            if (count($auditObjections) > 0) {
                $objectionHtml .= '<div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>HMM No</th>
                                <th>Auditor Para No</th>
                                <th>Audit Type</th>
                                <th>Severity</th>
                                <th>Zone</th>';

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
                                <td>' . $auditObjection?->audit?->audit_no . '</td>
                                <td>' . $auditObjection?->objection_no . '</td>
                                <td>' . $auditObjection?->auditType?->name . '</td>
                                <td>' . $auditObjection?->severity?->name . '</td>
                                <td>' . $auditObjection?->zone?->name . '</td>';
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
