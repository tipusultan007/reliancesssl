<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introducer extends Model
{
    use HasFactory;

    protected $fillable = [
        'introducer_name',
        'introducer_father',
        'introducer_address',
        'introducer_mobile',
        'introducer_signature',
        'member_id',
        'exist_member_id'
    ];
}
