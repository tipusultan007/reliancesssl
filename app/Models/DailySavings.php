<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySavings extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'account_no',
        'deposit',
        'withdraw',
        'profit',
        'bonus',
        'total',
        'status',
        'date',
        'notes'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function totalCounts()
    {
        return DailySavings::count();
    }

    public static function totalBalance()
    {
        return DailySavings::sum('total');
    }

    public static function totalDeposit()
    {
        return DailySavings::sum('deposit');
    }

    public static function totalWithdraw()
    {
        return DailySavings::sum('withdraw');
    }

}
