<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'amount',
        'date',
        'description',
        'user_id',
        'trx_id',
        'account_no',
    ];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class,'category_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
