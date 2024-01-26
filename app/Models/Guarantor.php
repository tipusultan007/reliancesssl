<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'account_no',
        'g_account_no',
        'signature',
        'photo',
        'g_documents',
        'name1',
        'address1',
        'g_mobile1',
        'signature1',
        'photo1',
        'g_documents1',
        'name2',
        'address2',
        'g_mobile2',
        'signature2',
        'photo2',
        'g_documents2',
        'g_member_id',
        'loan_id',
        'loan_type',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class,'g_member_id','id');
    }
}
