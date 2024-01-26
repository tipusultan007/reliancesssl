<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_name',
        'mother_name',
        'address',
        'email',
        'phone',
        'hire_date',
        'employee_status',
        'salary',
        'photo',
        'documents',
        'termination_date'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function getMonthlyAttendance($year, $month)
    {
        return $this->attendances()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
    }

    public function getMonthlyLeaves($year, $month)
    {
        return $this->leaves()
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->get();
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
