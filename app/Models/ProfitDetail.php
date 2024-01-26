<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'fdr_collection_id',
        'fdr_deposit_id',
        'amount',
        'installments',
        'trx_id'
    ];
}
