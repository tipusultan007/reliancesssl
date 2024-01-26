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
}
