<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','date','time_in','time_out','status','note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
