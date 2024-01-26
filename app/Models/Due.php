<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'account_no',
        'due',
        'remain'
    ];
}
