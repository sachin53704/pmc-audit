<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'auditor_no',
        'email',
        'mobile',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getFullNameAttribute()
    {
        return ($this->first_name) . ($this->middle_name ? ' ' . $this->middle_name : '') . ($this->last_name ? ' ' . $this->last_name : '');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function diaries()
    {
        return $this->hasMany(Diary::class, 'user_id', 'id');
    }


    // public static function booted()
    // {
    //     static::creating(function (self $user)
    //     {
    //         if(Auth::check())
    //         {
    //             $user->update([
    //                 'created_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    //     static::creating(function (self $user)
    //     {
    //         if(Auth::check())
    //         {
    //             $user->update([
    //                 'updated_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    //     static::deleting(function (self $user)
    //     {
    //         if(Auth::check())
    //         {
    //             $user->update([
    //                 'deleted_by'=> Auth::user()->id,
    //             ]);
    //         }
    //     });
    // }
}
