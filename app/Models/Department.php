<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Department extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_audit', 'created_by', 'updated_by', 'deleted_by'];

    public function audit()
    {
        return $this->hasMany(Audit::class, 'department_id', 'id');
    }


    public function auditObjections()
    {
        return $this->hasManyThrough(AuditObjection::class, Audit::class,);
    }

    public function auditObjection()
    {
        return $this->hasMany(AuditObjection::class);
    }


    // public static function booted()
    // {
    //     static::creating(function (self $department)
    //     {
    //         if(Auth::check())
    //         {
    //             $department->update([
    //                 'created_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    //     static::updating(function (self $department)
    //     {
    //         if(Auth::check())
    //         {
    //             $department->update([
    //                 'updated_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    //     static::deleting(function (self $department)
    //     {
    //         if(Auth::check())
    //         {
    //             $department->update([
    //                 'deleted_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    // }
}
