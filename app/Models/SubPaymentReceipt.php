<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubPaymentReceipt extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 1;
    const STATUS_DY_AUDITOR_APPROVED = 2;
    const STATUS_DY_AUDITOR_REJECTED = 3;
    const STATUS_DY_MCA_APPROVED = 4;
    const STATUS_DY_MCA_REJECTED = 5;
    const STATUS_MCA_APPROVED = 6;
    const STATUS_MCA_REJECTED = 7;


    protected $fillable = [
        'receipt_id',
        'receipt_detail',
        'amount',
        'file',
        'status',
        'dy_auditor_remark',
        'dy_auditor_status',
        'action_by_dy_auditor',
        'dy_mca_remark',
        'dy_mca_status',
        'action_by_dy_mca',
        'mca_remark',
        'mca_status',
        'action_by_dy_mca',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
