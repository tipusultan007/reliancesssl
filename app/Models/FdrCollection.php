<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FdrCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_id',
        'account_no',
        'fdr_withdraw',
        'fdr_balance',
        'profit',
        'profit_installments',
        'date',
        'notes',
        'trx_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fdr()
    {
        return $this->belongsTo(Fdr::class,'account_no','account_no');
    }
}
