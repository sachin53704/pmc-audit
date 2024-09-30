<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'department_id', 'working_day_id', 'work', 'date', 'dymca_status', 'mca_status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function workingDay()
    {
        return $this->belongsTo(WorkingDay::class, 'working_day_id', 'id');
    }
}
