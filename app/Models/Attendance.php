<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'date', 'present', 'leave',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttendance($userId, $year, $month)
    {
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $totalPresent = $this->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('present', true)
            ->count();

        $totalLeave = $this->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('leave', true)
            ->count();

        $totalAbsent = $this->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('present', false)
            ->where('leave', false)
            ->count();

        return [
            'total_present' => $totalPresent,
            'total_leave' => $totalLeave,
            'total_absent' => $totalAbsent,
        ];
    }
}
