<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'nominee_name',
        'nominee_address',
        'nominee_mobile',
        'nominee_nid',
        'birth_date',
        'nominee_relation',
        'nominee_photo',
        'nominee_percentage',
        'nominee_name1',
        'birth_date1',
        'nominee_nid1',
        'nominee_photo1',
        'nominee_percentage1',
        'nominee_mobile1',
        'nominee_relation1',
        'nominee_address1'
    ];

     public function fdr()
    {
        return $this->belongsTo(Fdr::class,'account_no','account_no');
    }
}
