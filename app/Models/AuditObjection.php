<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AuditType;
use App\Models\Severity;

class AuditObjection extends BaseModel
{
    use HasFactory, SoftDeletes;

    const OBJECTION_STATUS_PENDING = 1;
    const OBJECTION_STATUS_AUDITOR_APPROVED = 2;
    const OBJECTION_STATUS_AUDITOR_REJECTED = 3;
    const OBJECTION_STATUS_MCA_APPROVED = 4;
    const OBJECTION_STATUS_MCA_REJECTED = 5;

    protected $fillable = ['user_id', 'audit_id', 'objection_no', 'entry_date', 'department_id', 'from_year', 'to_year', 'audit_type_id', 'severity_id', 'audit_para_category_id', 'amount', 'subject', 'document', 'sub_unit', 'description', 'draft_description', 'is_draft_send', 'is_department_draft_save', 'compliance_submit_date', 'status', 'is_objection_send', 'is_department_hod_forward', 'department_hod_remark', 'is_draft_save', 'dymca_status', 'dymca_remark', 'mca_status', 'mca_remark', 'department_file', 'department_remark', 'department_draft_remark', 'department_hod_final_status', 'department_hod_final_remark', 'department_mca_second_status', 'department_mca_second_remark', 'auditor_status', 'auditor_remark', 'dymca_final_status', 'dymca_final_remark', 'mca_final_status', 'mca_final_remark', 'completed_sub_unit', 'pending_sub_unit', 'submit_compliance', 'audit_compliance_from_date', 'audit_compliance_to_date'];


    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function auditType()
    {
        return $this->belongsTo(AuditType::class, 'audit_type_id', 'id');
    }

    public function auditParaCategory()
    {
        return $this->belongsTo(AuditParaCategory::class, 'audit_para_category_id', 'id');
    }


    public function severity()
    {
        return $this->belongsTo(Severity::class, 'severity_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function from()
    {
        return $this->belongsTo(FiscalYear::class, 'from_year', 'id');
    }

    public function to()
    {
        return $this->belongsTo(FiscalYear::class, 'to_year', 'id');
    }
}
