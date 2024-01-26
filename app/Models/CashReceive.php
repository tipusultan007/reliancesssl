<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'member_id',
        'user_id',
        'trx_id',
        'category_id',
        'amount',
        'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CashReceiveCategory::class,'category_id');
    }
}
