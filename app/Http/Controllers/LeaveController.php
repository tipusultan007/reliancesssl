<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function applyForm()
    {
        $employees = Employee::all();
        return view('leave.apply', compact('employees'));
    }

    public function applyForLeave(Request $request)
    {
        $leave = new Leave([
            'employee_id' => $request->input('employee_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'reason' => $request->input('reason'),
        ]);

        $leave->save();

        return redirect()->route('leave.applyForm')->with('success', 'Leave applied successfully.');
    }
}
