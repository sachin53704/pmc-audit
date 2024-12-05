<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingAuditObjection extends Model
{
    use HasFactory;

    // protected $fillable = ['parent_id', 'audit_objection_id', 'sub_unit', 'completed_sub_unit', 'pending_sub_unit', 'pending_description', 'status', 'department_draft_remark', 'department_remark', 'department_file', 'department_hod_final_status', 'department_hod_final_remark', 'department_mca_second_status', 'department_mca_second_remark', 'auditor_status', 'auditor_description', 'auditor_draft_description', 'auditor_remark', 'dymca_final_status', 'dymca_final_remark', 'mca_final_status', 'mca_final_remark', 'is_objection_completed', 'submit_compliance', 'ask_pending_auditor_remark', 'ask_pending_auditor_status', 'hmm_draft_letter'];

    protected $fillable = ['audit_objection_id', 'parent_id', 'sub_unit', 'completed_sub_unit', 'submit_compliance', 'pending_sub_unit', 'pending_description', 'ask_pending_auditor_remark', 'ask_pending_auditor_status', 'hmm_draft_letter', 'status', 'department_remark', 'department_draft_remark', 'department_file', 'department_letter', 'department_hod_final_status', 'department_hod_final_remark', 'department_mca_second_status', 'department_mca_second_remark', 'auditor_status', 'auditor_description', 'auditor_draft_description', 'auditor_remark', 'dymca_final_status', 'dymca_final_remark', 'mca_final_status', 'mca_final_remark', 'is_objection_completed'];

    public function auditObjection()
    {
        return $this->belongsTo(AuditObjection::class, 'audit_objection_id', 'id');
    }
}
