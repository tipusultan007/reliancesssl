<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fdr extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'member_id',
        'fdr_amount',
        'fdr_balance',
        'profit',
        'date',
        'trx_id',
        'user_id',
        'status',
        'notes',
        'type'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fdrCollection()
    {
        return $this->hasMany(FdrCollection::class,'account_no','account_no');
    }

    public function deposits()
    {
        return $this->hasMany(FdrDeposit::class);
    }
    public function withdraws()
    {
        return $this->hasMany(FdrWithdraw::class);
    }

    public static function totalCount()
    {
        return Fdr::count();
    }

    public static function fdrBalance()
    {
        return Fdr::sum('fdr_balance');
    }

    public function nominee()
    {
        return $this->hasOne(Nominee::class,'account_no','account_no');
    }
}
