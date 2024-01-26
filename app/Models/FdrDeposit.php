<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FdrDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'fdr_id',
        'amount',
        'remain',
        'balance',
        'date',
        'profit_rate',
        'member_id',
        'trx_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fdr()
    {
        return $this->belongsTo(Fdr::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function withdraws()
    {
        return $this->hasMany(FdrWithdraw::class);
    }

    public function profits()
    {
        return $this->hasMany(FdrCollection::class);
    }
}
