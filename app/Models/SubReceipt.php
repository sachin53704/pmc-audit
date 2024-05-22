<?php

namespace App\Models;

use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubReceipt extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['receipt_id', 'receipt_detail', 'amount', 'file', 'status', 'dy_auditor_remark', 'dy_mca_remark', 'mca_remark', 'created_by', 'updated_by', 'deleted_by'];
}
