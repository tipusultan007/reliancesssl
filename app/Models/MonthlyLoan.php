<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'account_no',
        'loan_amount',
        'interest_rate',
        'balance',
        'trx_id',
        'status',
        'documents',
        'notes',
        'date',
        'user_id',
        'paid_loan',
        'paid_interest',

    ];

    protected $appends = ['remain_balance'];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanCollections()
    {
        return $this->hasMany(MonthlyCollection::class,'loan_id','id');
    }

    public static function totalCounts()
    {
        return MonthlyLoan::count();
    }

    public static function totalBalance()
    {
        return MonthlyLoan::sum('balance');
    }

    public static function totalPaid()
    {
        return MonthlyLoan::sum('paid_loan');
    }

    public static function totalPaidInterest()
    {
        return MonthlyLoan::sum('paid_interest');
    }

    public function getTotalPaidInterestAttribute()
    {
        return $this->loanCollections()->sum('monthly_interest');
    }

    public function getExtraInterestAttribute()
    {
        return $this->loanCollections()->sum('extra_interest');
    }

    public function getRemainBalanceAttribute()
    {
        $balance = $this->loan_amount - $this->paidLoan();

        return $balance;
    }

    public function getTotalPaidLoanAttribute()
    {
        return $this->paidLoan();
    }
    public function paidLoan()
    {
        return $this->loanCollections()->sum('loan_installment');
    }
}
