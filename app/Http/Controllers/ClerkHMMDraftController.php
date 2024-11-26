<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Audit;
use App\Models\AuditObjection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClerkHMMDraftController extends Controller
{
    public function sendObjection(Request $request)
    {
        $departments = Department::where('is_audit', 0)->get();

        $audits = [];
        if (isset($request->department) && $request->department != "") {
            $audits = AuditObjection::query()->with(['department'])->where('mca_status', 1)
                ->where('is_objection_send', 0)->withWhereHas('audit', function ($q) {
                    $q->where('status', '>=', 6);
                })
                ->where('status', '3')
                ->whereNull('hmm_draft_number')
                ->where('department_id', $request->department)
                ->latest()->get();
        }

        return view('program-audit.clerk.send-objection')->with([
            'audits' => $audits,
            'departments' => $departments
        ]);
    }

    public function getNotSendObjection(Request $request)
    {
        if ($request->ajax()) {
            $auditObjections = AuditObjection::with(['audit', 'department'])
                ->where('audit_id', $request->audit_id)
                ->where('is_objection_send', 0)
                ->where('status', '>=', $request->status)
                ->where('mca_status', 1)
                ->get();

            $audit = Audit::find($request->audit_id);

            return response()->json([
                'auditObjections' => $auditObjections,
                'department' => $audit->department_id,
                'departmentName' => $audit->department?->name,
            ]);
        }
    }


    public function storeSendObjection(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->id)) {
                DB::beginTransaction();
                try {
                    if (isset($request->id)) {
                        $auditId = AuditObjection::where('id', $request->id[0])->value('audit_id');
                        $audit = Audit::with(['from', 'to', 'department'])->find($auditId);
                        $name = $this->generatePdf($audit);
                        $time = time();
                        for ($i = 0; $i < count($request->id); $i++) {
                            $auditObjection = AuditObjection::find($request->id[$i]);
                            $auditObjection->hmm_draft_number = $time;
                            $auditObjection->clerk_send_hmm_draft_letter = $name;
                            $auditObjection->is_objection_send = 1;
                            if ($auditObjection->status < 4) {
                                $auditObjection->status = 4;
                            }
                            $auditObjection->save();
                        }
                    }

                    DB::commit();
                    return response()->json(['success' => 'Hmm draft send successful']);
                } catch (\Exception $e) {
                    Log::info($e);
                    DB::rollback();
                    return response()->json(['error' => 'Something went wrong']);
                }
            }
            return response()->json(['error' => 'Select atleast one objection']);
        }
    }

    public function generatePdf($audit)
    {
        $pdf = PDF::loadView('letter.2', compact('audit'));

        $name = 'letter/' . $audit->department?->name . "" . now() . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }

    public function viewObjection(Request $request)
    {
        if ($request->ajax()) {
            $auditObjection = AuditObjection::with(['department', 'zone', 'from', 'to', 'severity', 'auditType', 'auditParaCategory'])->where('id', $request->id)->first();

            return response()->json([
                'auditObjection' => $auditObjection
            ]);
        }
    }


    public function clerkSendHmmDraft(Request $request)
    {

        $audits = AuditObjection::query()->with(['department'])->where('mca_status', 1)
            ->where('is_objection_send', 1)->withWhereHas('audit', function ($q) {
                $q->where('status', '>=', 6);
            })
            ->where('status', '4')
            ->whereNotNull('hmm_draft_number')
            ->when(Auth::user()->hasRole('DY MCA'), function ($q) {
                $q->whereNull('hmm_draft_dymca_status');
            })->when(Auth::user()->hasRole('MCA'), function ($q) {
                $q->whereNull('hmm_draft_mca_status')
                    ->where('hmm_draft_dymca_status', 1);
            })
            ->latest()
            ->get();

        $audits = $audits->groupBy('hmm_draft_number');

        return view('program-audit.mca.send-objection')->with([
            'audits' => $audits
        ]);
    }


    public function updateClerkSendHmmDraft(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('DY MCA')) {
                AuditObjection::where('hmm_draft_number', $request->hmm_draft_number)
                    ->update([
                        'hmm_draft_dymca_status' => $request->hmm_draft_dymca_status,
                        'hmm_draft_dymca_remark' => $request->hmm_draft_dymca_remark,
                    ]);

                return response()->json(['success' => 'Hmm draft approve successfully']);
            } elseif (Auth::user()->hasRole('MCA')) {
                $auditId = AuditObjection::where('hmm_draft_number', $request->hmm_draft_number)->value('audit_id');
                $audit = Audit::with(['from', 'to', 'department'])->find($auditId);
                $name = $this->generateFinalPdf($audit);


                AuditObjection::where('hmm_draft_number', $request->hmm_draft_number)
                    ->update([
                        'hmm_draft_mca_status' => $request->hmm_draft_dymca_status,
                        'hmm_draft_mca_remark' => $request->hmm_draft_dymca_remark,
                        'hmm_draft_letter' => $name
                    ]);
                return response()->json(['success' => 'Hmm draft approve successfully']);
            } else {
                return response()->json(['error' => 'Something went wrong']);
            }
        }
    }

    public function generateFinalPdf($audit)
    {
        $pdf = PDF::loadView('letter.4', compact('audit'));

        $name = 'letter/' . $audit->department?->name . "" . now() . '.pdf';

        Storage::put($name, $pdf->output());
        return $name;
    }
}
