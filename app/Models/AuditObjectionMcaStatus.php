<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditObjectionMcaStatus extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'audit_objection_id', 'status', 'mca_remark'];
}
