<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLoan extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'account_no',
        'loan_amount',
        'interest_rate',
        'interest',
        'total',
        'balance',
        'due',
        'per_installment',
        'trx_id',
        'status',
        'documents',
        'loan_period',
        'notes',
        'date',
        'paid_interest',
        'paid_loan',
        'user_id'
    ];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function loanCollections()
    {
        return $this->hasMany(DailyCollection::class,'loan_id','id');
    }

    public static function totalCounts()
    {
        return DailyLoan::count();
    }

    public static function totalBalance()
    {
        return DailyLoan::sum('balance');
    }

    public static function totalPaid()
    {
        return DailyLoan::sum('paid_loan');
    }

    public static function totalPaidInterest()
    {
        return DailyLoan::sum('paid_interest');
    }
}
