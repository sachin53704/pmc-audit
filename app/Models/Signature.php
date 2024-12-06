<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'image'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
