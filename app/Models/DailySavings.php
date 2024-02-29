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


    protected $appends = ['total_balance'];
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

    public function collections()
    {
        return $this->hasMany(DailyCollection::class,'account_no','account_no');
    }

    public function getTotalDepositAttribute()
    {
        return $this->collections()->sum('deposit');
    }

    public function getTotalWithdrawAttribute()
    {
        return $this->collections()->sum('withdraw');
    }
    public function profits()
    {
        return $this->hasMany(AddProfit::class,'account_no','account_no');
    }
    public function getTotalProfitAttribute()
    {
        return $this->profits()->sum('amount');
    }

    public function getTotalBalanceAttribute()
    {
        return $this->total_deposit + $this->total_profit - $this->total_withdraw;
    }
}
