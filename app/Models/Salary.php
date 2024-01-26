<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary',
        'date',
        'month_year',
        'notes',
        'expense_category_id',
        'trx_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
