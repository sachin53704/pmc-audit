<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParaAudit extends Model
{
    use HasFactory;

    protected $fillable = ['audit_id', 'draft_description', 'description', 'is_draft_send', 'dymca_status', 'dymca_remark', 'mca_status', 'mca_remark'];

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id', 'id');
    }
}
