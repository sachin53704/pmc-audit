<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardNo extends Model
{
    use HasFactory;

    protected $fillable = ['foreign_id', 'letter', 'outward_no', 'table'];
}
