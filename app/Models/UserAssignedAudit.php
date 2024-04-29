<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAssignedAudit extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'audit_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
