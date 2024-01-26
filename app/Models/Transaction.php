<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
     protected $fillable = [
         'account_no',
         'transaction_category_id',
         'trx_id',
         'member_id',
         'date',
         'amount',
         'user_id',
         'type'
     ];

     public function category()
     {
         return $this->belongsTo(TransactionCategory::class);
     }
     public function member()
     {
         return $this->belongsTo(Member::class);
     }

}
