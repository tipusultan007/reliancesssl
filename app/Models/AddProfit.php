<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddProfit extends Model
{
    use HasFactory;

    protected $fillable = ['account_no','type','amount','description','date','year_month'];

    public function daily()
    {
        return $this->belongsTo(DailySavings::class,'account_no','account_no');
    }

    public function monthly()
    {
        return $this->belongsTo(MonthlySaving::class,'account_no','account_no');
    }
}
