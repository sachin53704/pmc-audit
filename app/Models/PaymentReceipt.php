<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PaymentReceipt extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;


    protected $fillable = ['user_id', 'description', 'from_date', 'to_date', 'amount', 'file', 'status', 'created_by', 'updated_by', 'deleted_by'];

    protected $appends = ['status_name'];


    public function getStatusNameAttribute()
    {
        $statusName = collect([['id'=>1, 'name'=>'pending'], ['id'=>2, 'name'=>'approved'], ['id'=>3, 'name'=>'rejected']]);
        return $statusName->where('id', $this->status)->first()['name'];
    }


    public function subreceipts()
    {
        return $this->hasMany(SubPaymentReceipt::class);
    }



    public static function booted()
    {
        static::created(function (PaymentReceipt $object)
        {
            if(Auth::check())
            {
                self::where('id', $object->id)->update([
                    'created_by'=> Auth::user()->id,
                ]);
            }
        });
        static::updated(function (PaymentReceipt $object)
        {
            if(Auth::check())
            {
                self::where('id', $object->id)->update([
                    'updated_by'=> Auth::user()->id,
                ]);
            }
        });
        static::deleting(function (PaymentReceipt $object)
        {
            if(Auth::check())
            {
                self::where('id', $object->id)->update([
                    'deleted_by'=> Auth::user()->id,
                ]);
            }
        });
    }
}
