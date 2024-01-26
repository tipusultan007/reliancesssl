<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_name',
        'mother_name',
        'spouse_name',
        'gender',
        'phone',
        'marital_status',
        'nid_no',
        'nid',
        'birth_id',
        'birth_id_no',
        'present_address',
        'permanent_address',
        'occupation',
        'workplace',
        'photo',
        'nationality',
        'birth_date',
        'join_date',
        'status',
        'signature',
    ];


    public function monthly()
    {
        return $this->hasMany(MonthlySaving::class,'member_id','id');
    }

    public function daily()
    {
        return $this->hasMany(DailySavings::class,'member_id','id');
    }
    public function monthlyLoan()
    {
        return $this->hasMany(MonthlyLoan::class,'member_id','id');
    }

    public function dailyLoan()
    {
        return $this->hasMany(DailyLoan::class,'member_id','id');
    }
    public function fdr()
    {
        return $this->hasMany(Fdr::class,'member_id','id');
    }

    public function getPhotoAttribute()
    {
        if ($this->attributes['photo']) {
            return $this->attributes['photo'];
        } else {
            return $this->attributes['gender'] === 'male' ? 'male.png' : 'female.png';
        }
    }
}
