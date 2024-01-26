<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'loan_id',
        'account_no',
        'trx_id',
        'monthly_amount',
        'monthly_interest',
        'extra_interest',
        'loan_installment',
        'monthly_installments',
        'interest_installments',
        'balance',
        'late_fee',
        'due',
        'due_return',
        'user_id',
        'date',
        'monthly_saving_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loan()
    {
        return $this->belongsTo(MonthlyLoan::class);
    }

    public function monthlySaving()
    {
        return $this->belongsTo(MonthlySaving::class,'monthly_saving_id','id');
    }
}
