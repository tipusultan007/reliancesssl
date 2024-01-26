<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySavingsClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_saving_id',
        'withdraw',
        'profit',
        'date',
        'member_id',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
