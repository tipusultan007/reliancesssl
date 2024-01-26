<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {

        $currentMonth = Carbon::now()->format('Y-m');
        // Get the selected month from the request or use the current month
        $selectedMonth = $request->input('month', $currentMonth);

        $attendanceData = Attendance::where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $selectedMonth)
            ->orderBy('date', 'asc')->get();
        // Group data by user ID
        $groupedData = $attendanceData->groupBy('user_id');

        $userNames = User::whereIn('id', $attendanceData->pluck('user_id')->unique())->pluck('name', 'id');

        return view('attendance.index', compact('groupedData', 'selectedMonth', 'currentMonth','userNames'));
    }
    public function create()
    {
        $users = User::all();
        return view('attendance.create', compact('users'));
    }
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'present' => 'boolean',
            'leave' => 'boolean',
        ]);

        // Check for duplicate entry
        $existingAttendance = Attendance::where('user_id', $request->input('user_id'))
            ->whereDate('date', $request->input('date'))
            ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'এই কর্মচারীর উপস্থিতি রেকর্ড এবং তারিখ ইতিমধ্যেই বিদ্যমান']);
        }

        // Create a new attendance record
        $attendance = new Attendance();
        $attendance->user_id = $request->input('user_id');
        $attendance->date = $request->input('date');
        $attendance->present = $request->input('present', false);
        $attendance->leave = $request->input('leave', false);
        $attendance->save();

        return response()->json(['message' => 'উপস্থিতি রেকর্ড সফলভাবে যোগ করা হয়েছে']);

    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'present' => 'boolean',
            'leave' => 'boolean',
        ]);

        // Find the attendance record
        $attendance = Attendance::findOrFail($id);

        // Check for duplicate entry for the same user in the same month and year
        $existingAttendance = Attendance::where('user_id', $request->input('user_id'))
            ->whereDate('date', $request->input('date'))
            ->where('id', '!=', $id) // Exclude the current record being updated
            ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'একই মাস এবং বছরের জন্য ডুপ্লিকেট এন্ট্রি'], 400);
        }

        // Update the attendance record
        $attendance->user_id = $request->input('user_id');
        $attendance->date = $request->input('date');
        $attendance->present = $request->input('present', false);
        $attendance->leave = $request->input('leave', false);
        $attendance->save();

        return response()->json(['message' => 'উপস্থিতি রেকর্ড সফলভাবে আপডেট করা হয়েছে']);
    }


    public function destroy($id)
    {
        try {
            // Find the attendance record
            $attendance = Attendance::findOrFail($id);

            // Delete the attendance record
            $attendance->delete();

            return response()->json(['message' => 'Attendance record deleted successfully']);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'An error occurred while deleting the attendance record'], 500);
        }
    }


   /* public function getAttendance($id)
    {
        $attendance = Attendance::find($id);
        return response()->json($attendance);
    }*/
}

