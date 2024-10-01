<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends BaseModel
{
    use HasFactory, SoftDeletes;

    const AUDIT_STATUS_PENDING = 1;
    const AUDIT_STATUS_APPROVED = 2;
    const AUDIT_STATUS_REJECTED = 3;
    const AUDIT_STATUS_AUDITOR_ASSIGNED = 4;
    const AUDIT_STATUS_LETTER_SENT_TO_DEPARTMENT = 5;
    const AUDIT_STATUS_AUDITOR_ADDED_OBJECTION = 6;
    const AUDIT_STATUS_DEPARTMENT_ADDED_COMPLIANCE = 7;
    // BELOW STATUSES ARE NOT IN USE
    // const AUDIT_STATUS_AUDITOR_APPROVED_COMPLIANCE = 8;
    // const AUDIT_STATUS_AUDITOR_REJECTED_COMPLIANCE = 9;
    // const AUDIT_STATUS_MCA_APPROVED_COMPLIANCE = 10;
    // const AUDIT_STATUS_MCA_REJECTED_COMPLIANCE = 11;

    protected $fillable = ['department_id', 'audit_no', 'date', 'description', 'remark', 'file_path', 'status', 'reject_reason', 'dl_description', 'dl_file_path', 'obj_date', 'obj_subject', 'dymca_status', 'dymca_remark', 'mca_status', 'mca_remark'];

    protected $appends = ['status_name'];



    public function getStatusNameAttribute()
    {
        $statusName = collect(config('default_data.audit_status'));
        // return $statusName->where('id', $this->status)->first()['name'];
    }



    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedAuditors()
    {
        return $this->hasMany(UserAssignedAudit::class, 'audit_id', 'id');
    }

    public function objections()
    {
        return $this->hasMany(AuditObjection::class);
    }

    public function paraAudits()
    {
        return $this->hasMany(ParaAudit::class, 'audit_id', 'id');
    }

    public function paraAudit()
    {
        return $this->hasOne(ParaAudit::class, 'audit_id', 'id');
    }







    public static function generateAuditNo()
    {
        $auditNo = '';

        do {
            $auditNo = 'PMC' . date('m') . date('d') . sprintf("%05d", mt_rand(10000, 99999));
        } while (self::where('audit_no', $auditNo)->exists());

        return $auditNo;
    }
}
