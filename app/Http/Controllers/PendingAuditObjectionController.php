<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingAuditObjection;
use App\Models\AuditObjection;
use App\Models\Department;
use App\Models\Zone;
use App\Models\FiscalYear;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\Audit;
use App\Models\AuditParaCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class PendingAuditObjectionController extends Controller
{
    public function pendingAuditObjection()
    {
        $pendingAuditObjections = PendingAuditObjection::with(['auditObjection.department'])
            ->where('is_objection_completed', 0)
            ->when(Auth::user()->hasRole(['Department', 'Department HOD']), function ($q) {
                $q->where('status', '>=', 1)->whereHas('auditObjection', function ($q) {
                    $q->where('department_id', Auth::user()->department_id);
                });
            })
            ->when(Auth::user()->hasRole(['MCA']), function ($q) {
                $q->where('status', '>=', 2);
            })
            ->when(Auth::user()->hasRole(['Auditor']), function ($q) {
                $q->where('status', '>=', 3);
            })
            ->when(Auth::user()->hasRole(['DY MCA']), function ($q) {
                $q->where('status', '>=', 4);
            })
            ->when(Auth::user()->hasRole(['Clerk']), function ($q) {
                $q->where('status', '>=', 44);
            })
            ->get();

        $departments = Department::select('id', 'name')->get();

        $zones = Zone::where('status', 1)->select('id', 'name')->get();

        $fiscalYears = FiscalYear::select('id', 'name')->get();

        $auditTypes = AuditType::where('status', 1)->select('id', 'name')->get();

        $severities = Severity::where('status', 1)->select('id', 'name')->get();

        $auditParaCategory = AuditParaCategory::where('status', 1)->select('id', 'name', 'is_amount')->get();

        return view('program-audit.pending-objection.index')->with([
            'pendingAuditObjections' => $pendingAuditObjections,
            'departments' => $departments,
            'zones' => $zones,
            'fiscalYears' => $fiscalYears,
            'auditTypes' => $auditTypes,
            'severities' => $severities,
            'auditParaCategory' => $auditParaCategory,
        ]);
    }

    public function viewPendingObjection(Request $request)
    {
        if ($request->ajax()) {
            $audit = PendingAuditObjection::with(['auditObjection.audit'])
                ->where('id', $request->id)->first();

            return response()->json([
                'audit' => $audit
            ]);
        }
    }

    public function changePendingObjectionStatus(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole(['Department'])) {
                $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                $files = $pendingAuditObjection->department_file;
                if ($request->hasFile('department_files')) {
                    if (Storage::exists('public/' . $pendingAuditObjection->department_file)) {
                        Storage::delete('public/' . $pendingAuditObjection->department_file);
                    }
                    $files = $request->department_files->store('pending-objection');
                }
                $pendingAuditObjection->department_file = $files;



                if ($request->is_draft_save) {
                    $pendingAuditObjection->department_draft_remark = $request->department_remark;
                } else {
                    if ($pendingAuditObjection->status < 1) {
                        $pendingAuditObjection->status = 1;
                    }

                    $auditObjection = AuditObjection::find($pendingAuditObjection->audit_objection_id);
                    $audits = Audit::with(['from', 'to', 'department'])->find($auditObjection->audit_id);
                    $name = $this->generateFinalPdf($audits);

                    $pendingAuditObjection->department_draft_remark = $request->department_remark;
                    $pendingAuditObjection->department_remark = $request->department_remark;
                    $pendingAuditObjection->department_letter = $name;
                }
                if ($pendingAuditObjection->save()) {
                    if ($request->is_draft_save) {
                        return response()->json(['success' => 'Compliance draft save successfully']);
                    } else {
                        return response()->json(['success' => 'Compliance submitted successfully']);
                    }
                }
            } elseif (Auth::user()->hasRole(['Department HOD'])) {
                $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                $pendingAuditObjection->department_hod_final_status = $request->department_hod_final_status;
                $pendingAuditObjection->department_hod_final_remark = $request->department_hod_final_remark;

                if ($request->department_hod_final_status == "1" && $pendingAuditObjection->status < 2) {
                    $pendingAuditObjection->status = 2;
                }

                $pendingAuditObjection->save();

                if ($request->department_hod_final_status == "1") {
                    return response()->json(['success' => 'Compliance approve successfully']);
                } else {
                    return response()->json(['success' => 'Compliance rejected successfully']);
                }
            } elseif (Auth::user()->hasRole(['MCA'])) {
                if (isset($request->department_mca_second_status)) {
                    $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                    $pendingAuditObjection->department_mca_second_status = $request->department_mca_second_status;
                    $pendingAuditObjection->department_mca_second_remark = $request->department_mca_second_remark;

                    if ($pendingAuditObjection->status < 3) {
                        $pendingAuditObjection->status = 3;
                    }

                    $pendingAuditObjection->save();

                    return response()->json(['success' => 'Objection forwarded to auditor successfully']);
                } else {
                    $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                    $pendingAuditObjection->mca_final_status = $request->mca_final_status;
                    $pendingAuditObjection->mca_final_remark = $request->mca_final_remark;
                    $pendingAuditObjection->is_objection_completed = 1;

                    if ($pendingAuditObjection->status < 6) {
                        $pendingAuditObjection->status = 6;
                    }

                    $pendingAuditObjection->save();

                    if ($pendingAuditObjection->pending_sub_unit > 0) {
                        $auditObjection = AuditObjection::find($pendingAuditObjection->audit_objection_id);
                        $audits = Audit::with(['from', 'to', 'department'])->find($auditObjection->audit_id);
                        $name = $this->generatePdf($audits);

                        PendingAuditObjection::create([
                            'audit_objection_id' => $pendingAuditObjection->audit_objection_id,
                            'sub_unit' => $pendingAuditObjection->pending_sub_unit,
                            'pending_description' => $pendingAuditObjection->auditor_draft_description,
                            'status' => 1,
                            'hmm_draft_letter' => $name,
                            'parent_id' => $pendingAuditObjection->id
                        ]);
                    }

                    return response()->json(['success' => 'Objection approve successfully']);
                }


                return response()->json(['success' => 'Compliance forward to auditor successfully']);
            } elseif (Auth::user()->hasRole(['DY MCA'])) {
                $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                $pendingAuditObjection->dymca_final_status = $request->dymca_final_status;
                $pendingAuditObjection->dymca_final_remark = $request->dymca_final_remark;
                if ($pendingAuditObjection->status < 5) {
                    $pendingAuditObjection->status = 5;
                }
                $pendingAuditObjection->save();

                return response()->json(['success' => 'Objection approve successfully']);
            } elseif (Auth::user()->hasRole(['Auditor'])) {

                try {
                    DB::beginTransaction();
                    $pendingAuditObjection = PendingAuditObjection::find($request->pending_audit_objection_id);
                    $pendingAuditObjection->auditor_description = $request->auditor_description;
                    $pendingAuditObjection->completed_sub_unit = $request->completed_sub_unit;
                    $pendingAuditObjection->pending_sub_unit = $request->pending_sub_unit;
                    $pendingAuditObjection->auditor_remark = $request->auditor_remark;
                    $pendingAuditObjection->auditor_status = $request->auditor_status;

                    if ($request->is_draft_save) {
                        $pendingAuditObjection->auditor_draft_description = $request->auditor_description;
                    } else {
                        $pendingAuditObjection->auditor_draft_description = $request->auditor_description;
                        $pendingAuditObjection->auditor_description = $request->auditor_description;
                        if ($pendingAuditObjection->status < 4) {
                            $pendingAuditObjection->status = 4;
                        }
                    }
                    $pendingAuditObjection->save();

                    AuditObjection::where('id', $pendingAuditObjection->audit_objection_id)->update([
                        'completed_sub_unit' => $request->completed_sub_unit,
                        'pending_sub_unit' => $request->pending_sub_unit,
                    ]);

                    DB::commit();

                    return response()->json(['success' => 'Objection updated successfully']);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong please try later']);
                }
            }
        }
    }

    public function generatePdf($audit)
    {
        $pdf = PDF::loadView('letter.4', compact('audit'));

        $name = 'letter/' . Str::random(60) . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }

    public function generateFinalPdf($audit)
    {
        $pdf = PDF::loadView('letter.3', compact('audit'));

        $name = 'letter/' . Str::random(60) . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }
}
