<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'loan_type',
        'account_no',
        'bank_name',
        'branch_name',
        'account_holder',
        'bank_ac_number',
        'cheque_number',
        'documents',
        ];
}
