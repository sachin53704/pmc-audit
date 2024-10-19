<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Audit;
use App\Models\ParaAudit;
use App\Models\AuditObjection;

class ParaAuditController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $audits = Audit::query()
            ->when(Auth::user()->hasRole('Auditor'), function ($q) use ($user) {
                $q->whereHas('assignedAuditors', fn($q) => $q->where('user_id', $user->id));
            })
            ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                $q->whereHas('paraAudit', function ($q) {
                    $q->where('is_draft_send', 1)
                        ->orWhere(function ($q) {
                            $q->where('dymca_status', 0)
                                ->orWhereNull('dymca_status');
                        });
                });
            })
            ->when(Auth::user()->hasRole('MCA'), function ($q) {
                $q->whereHas('paraAudit', function ($q) {
                    $q->where('is_draft_send', 1)
                        ->where('dymca_status', 1);
                });
            })
            ->where('status', '>=', 13)
            ->with(['paraAudit'])
            ->latest()
            ->get();

        return view('para-audit.index')->with([
            'audits' => $audits
        ]);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $paraAudits = AuditObjection::where('audit_id', $request->audit_id)->where('mca_final_status', 1)->get();

            $html = "";

            foreach ($paraAudits as $paraAudit) {
                $html .= $paraAudit->description;
                $html .= "<table class='table'>
                    <thead>
                        <tr>
                            <th>HMM No.</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>" . $paraAudit->objection_no . "</td>
                            <td>" . date('d-m-Y', strtotime($paraAudit->entry_date)) . "</td>
                        </tr>
                    </tbody>
                </table><br><br>";
            }

            return response()->json([
                'html' => $html,
                'id' => $request->audit_id
            ]);
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'draft_description' => $request->description,
                'audit_id' => $request->audit_id
            ];

            if ($request->isDrafSave == 0) {
                $data = array_merge($data, ['description' => $request->description, 'is_draft_send' => 1]);
            }
            ParaAudit::create($data);

            if ($request->isDrafSave == 0) {
                return response()->json(['success' => 'Para Audit draft save successfully']);
            } else {
                return response()->json(['success' => 'Para Audit created successfully']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $audit = ParaAudit::where('id', $request->id)
                ->when(Auth::user()->hasRole('Auditor'), function ($q) {
                    $q->select('id', 'draft_description as description', 'mca_status', 'mca_remark', 'dymca_status', 'dymca_remark');
                })
                ->when(Auth::user()->hasRole(['MCA', 'DY MCA']), function ($q) {
                    $q->select('id', 'description as description', 'mca_status', 'mca_remark', 'dymca_status', 'dymca_remark');
                })
                ->first();

            return response()->json([
                'audit' => $audit,
            ]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            if (isset($request->statusApprove) && $request->statusApprove) {
                if (Auth::user()->hasRole('MCA')) {
                    $status = 'mca_status';
                    $statusRemark = 'mca_remark';
                } else {
                    $status = 'dymca_status';
                    $statusRemark = 'dymca_remark';
                }

                ParaAudit::where('id', $request->id)->update([
                    $status => $request->$status,
                    $statusRemark => $request->$statusRemark,
                ]);

                return response()->json(['success' => 'Para Audit status updated successfully']);
            } else {
                $data = [
                    'draft_description' => $request->description,
                ];

                if ($request->isDrafSave == 0) {
                    $data = array_merge($data, ['description' => $request->description, 'is_draft_send' => 1, 'dymca_status' => null, 'dymca_remark' => null, 'mca_status' => null, 'mca_status' => null]);
                }
                ParaAudit::where('id', $request->id)->update($data);

                if ($request->isDrafSave == 0) {
                    return response()->json(['success' => 'Para Audit draft updated successfully']);
                } else {
                    return response()->json(['success' => 'Para Audit updated successfully']);
                }
            }
        }
    }
}
