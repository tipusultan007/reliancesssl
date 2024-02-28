<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'loan_id',
        'member_id',
        'user_id',
        'date',
        'deposit',
        'withdraw',
        'loan_installment',
        'interest',
        'loan_return',
        'loan_balance',
        'late_fee',
        'trx_id',
        'notes',
        'total',
        'extra_interest',
        'daily_saving_id'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }


}
