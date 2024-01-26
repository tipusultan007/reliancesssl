<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'member_id',
        'total_deposited',
        'profit',
        'bonus',
        'loan_balance',
        'due_interest',
        'late_fee',
        'service_charge',
        'date',
        'trx_id',
        'depositor_owing',
        'organization_owing',
        'type',
        'loan_id',
        'user_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
