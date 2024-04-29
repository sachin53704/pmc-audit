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


    public static function booted()
    {
        static::created(function (self $department)
        {
            if(Auth::check())
            {
                $department->update([
                    'created_by'=> Auth::user()->id,
                ]);
            }
        });
        static::updated(function (self $department)
        {
            if(Auth::check())
            {
                $department->update([
                    'updated_by'=> Auth::user()->id,
                ]);
            }
        });
        static::deleting(function (self $department)
        {
            if(Auth::check())
            {
                $department->update([
                    'deleted_by'=> Auth::user()->id,
                ]);
            }
        });
    }
}
