<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditParaCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_amount', 'status'];
}
