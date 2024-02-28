<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySaving extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'account_no',
        'deposit',
        'monthly_amount',
        'duration',
        'withdraw',
        'profit',
        'total',
        'status',
        'date',
        'notes',
        'bonus'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loans()
    {
        return $this->hasMany(MonthlyLoan::class,'account_no','account_no');
    }

    public function monthlyCollections()
    {
        return $this->hasMany(MonthlyCollection::class,'account_no','account_no');
    }

    public static function totalCounts()
    {
        return MonthlySaving::count();
    }

    public static function totalBalance()
    {
        return MonthlySaving::sum('total');
    }

    public static function totalDeposit()
    {
        return MonthlySaving::sum('deposit');
    }

    public static function totalWithdraw()
    {
        return MonthlySaving::sum('withdraw');
    }
    public function getTotalLoanProvideAttribute()
    {
       return $this->loans()->sum('loan_amount');
    }

    public function getTotalPaidLoanAttribute()
    {
        return $this->monthlyCollections()->whereNotNull('loan_id')->sum('loan_installment');
    }

    public function getTotalPaidInterestAttribute()
    {
        return $this->monthlyCollections()->sum('monthly_interest');
    }

    public function getExtraInterestAttribute()
    {
        return $this->monthlyCollections()->sum('extra_interest');
    }

    public function getRemainLoanAttribute()
    {
        return $this->total_loan_provide - $this->total_paid_loan;
    }
}
