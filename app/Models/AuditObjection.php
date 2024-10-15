<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AuditType;
use App\Models\Zone;
use App\Models\Severity;
use App\Models\AuditDepartmentAnswer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditObjection extends BaseModel
{
    use HasFactory, SoftDeletes;

    const OBJECTION_STATUS_PENDING = 1;
    const OBJECTION_STATUS_AUDITOR_APPROVED = 2;
    const OBJECTION_STATUS_AUDITOR_REJECTED = 3;
    const OBJECTION_STATUS_MCA_APPROVED = 4;
    const OBJECTION_STATUS_MCA_REJECTED = 5;

    protected $fillable = ['user_id', 'audit_id', 'objection_no', 'entry_date', 'department_id', 'zone_id', 'from_year', 'to_year', 'audit_type_id', 'severity_id', 'audit_para_category_id', 'amount', 'subject', 'document', 'sub_unit', 'description', 'draft_description', 'is_draft_send', 'status', 'mca_status', 'mca_remark', 'auditor_status', 'auditor_remark', 'dymca_status', 'dymca_remark', 'is_draft_save', 'is_objection_send', 'is_department_hod_forward', 'department_hod_remark'];
    protected $appends = ['status_name'];

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

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
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

    public function answeredBy()
    {
        return $this->belongsTo(User::class, 'answered_by', 'id');
    }

    public function mcaApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_mca', 'id');
    }

    public function auditorApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_auditor', 'id');
    }

    public function auditDepartmentAnswers()
    {
        return $this->hasMany(AuditDepartmentAnswer::class, 'audit_objection_id', 'id');
    }

    public function from()
    {
        return $this->belongsTo(FiscalYear::class, 'from_year', 'id');
    }

    public function to()
    {
        return $this->belongsTo(FiscalYear::class, 'to_year', 'id');
    }



    public function getStatusNameAttribute()
    {
        $statusName = collect(config('default_data.objection_status'));
        return $statusName->where('id', $this->status)->first()['name'];
    }
}
