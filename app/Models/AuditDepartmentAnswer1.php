<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditDepartmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['audit_id', 'audit_objection_id', 'file', 'remark', 'auditor_status', 'auditor_remark', 'dymca_status', 'dymca_remark', 'mca_status', 'mca_remark'];
}
