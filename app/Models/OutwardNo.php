<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardNo extends Model
{
    use HasFactory;

    protected $fillable = ['outward_no', 'department_id', 'subject'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
