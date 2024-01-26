<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FdrWithdrawDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'fdr_withdraw_id',
        'fdr_deposit_id',
        'amount',
        'trx_id'
    ];

}
