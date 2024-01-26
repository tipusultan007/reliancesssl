<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_date',
        'salary_month',
        'working_days',
        'present_days',
        'absence_days',
        'leave_days',
        'basic_salary',
        'payable_basic',
        'present_bonus',
        'tea_allowance',
        'mobile_bill',
        'other_bill',
        'net_salary',
        'trx_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
