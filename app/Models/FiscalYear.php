<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FiscalYear extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'from_year', 'to_year', 'created_by', 'updated_by', 'deleted_by'];


    public static function booted()
    {
        static::created(function (self $fiscalYear)
        {
            if(Auth::check())
            {
                $fiscalYear->update([
                    'created_by'=> Auth::user()->id,
                ]);
            }
        });
        static::updated(function (self $fiscalYear)
        {
            if(Auth::check())
            {
                $fiscalYear->update([
                    'updated_by'=> Auth::user()->id,
                ]);
            }
        });
        static::deleting(function (self $fiscalYear)
        {
            if(Auth::check())
            {
                $fiscalYear->update([
                    'deleted_by'=> Auth::user()->id,
                ]);
            }
        });
    }
}
