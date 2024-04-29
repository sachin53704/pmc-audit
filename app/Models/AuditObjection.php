<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditObjection extends BaseModel
{
    use HasFactory, SoftDeletes;

    const OBJECTION_STATUS_PENDING = 1;
    const OBJECTION_STATUS_AUDITOR_APPROVED = 2;
    const OBJECTION_STATUS_AUDITOR_REJECTED = 3;
    const OBJECTION_STATUS_MCA_APPROVED = 4;
    const OBJECTION_STATUS_MCA_REJECTED = 5;

    protected $fillable = ['audit_id', 'objection_no', 'objection', 'answer', 'remark', 'status', 'approved_by'];
    protected $appends = [ 'status_name' ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }



    public function getStatusNameAttribute()
    {
        $statusName = collect(config('default_data.objection_status'));
        return $statusName->where('id', $this->status)->first()['name'];
    }
}
