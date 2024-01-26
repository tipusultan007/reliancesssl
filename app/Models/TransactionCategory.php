<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'income_category_id',
        'expense_category_id',
        'type'
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
