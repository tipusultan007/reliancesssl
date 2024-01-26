<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->filled('salary_month')?$request->input('salary_month'):date('Y-m');
        $payrolls = Payroll::with('user')->where('salary_month',$month)->get();
        $employees = User::all();
        $now = Carbon::now();
        $previousMonth = $now->subMonth()->format('Y-m'); // Format: YYYY-MM

        return view('payrolls.index', compact('payrolls','employees','previousMonth'));
    }

    public function create()
    {
        $employees = User::all();
        $now = Carbon::now();
        $previousMonth = $now->subMonth()->format('Y-m'); // Format: YYYY-MM
        return view('payrolls.create', compact('employees','previousMonth'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'salary_month' => 'required|date_format:Y-m',
            'working_days' => 'required|numeric|min:0',
            'present_days' => 'required|numeric|min:0',
            'absence_days' => 'required|numeric|min:0',
            'leave_days' => 'required|numeric|min:0',
            'basic_salary' => 'required|numeric|min:0',
            'payable_basic' => 'required|numeric|min:0',
            'present_bonus' => 'required|numeric|min:0',
            'tea_allowance' => 'required|numeric|min:0',
            'mobile_bill' => 'required|numeric|min:0',
            'other_bill' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
        ]);

        $employee = User::findOrFail($request->input('user_id'));

        // Check if a payroll entry already exists for the selected employee and month
        $existingPayroll = Payroll::where('user_id', $employee->id)
            ->where('salary_month', $request->salary_month)
            ->first();

        if ($existingPayroll) {
            return redirect()->route('payrolls.create')->with('error', 'বর্তমান মাসে এই কর্মচারীর জন্য বেতনের এন্ট্রি ইতিমধ্যেই বিদ্যমান।');
        }

        $validatedData['trx_id'] = Str::uuid();

        $payroll = Payroll::create($validatedData);

        $salaryMonth = Carbon::parse($request->salary_month)->translatedFormat('F-Y');

        $expense = Expense::create([
            'category_id' => 1,
            'amount' => $payroll->net_salary,
            'date' => $payroll->payment_date,
            'description' => 'মাসিক বেতন - '.$payroll->user->name.' - '.$salaryMonth,
            'user_id' => Auth::id(),
            'trx_id' => $payroll->trx_id
        ]);

        $trx_cat_id = TransactionCategory::where('expense_category_id',$expense->category_id)->first();
        $transaction = Transaction::create([
            'transaction_category_id' => $trx_cat_id->id,
            'trx_id' => $expense->trx_id,
            'date' => $expense->date,
            'amount' => $expense->amount,
            'user_id' => $expense->user_id,
        ]);

        return redirect()->route('payrolls.create')->with('success', 'পে-রোল সফলভাবে তৈরি হয়েছে।');
    }


    public function edit(Payroll $payroll)
    {
        $employees = User::all();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'salary_month' => 'required|date_format:Y-m',
            'working_days' => 'required|numeric|min:0',
            'present_days' => 'required|numeric|min:0',
            'absence_days' => 'required|numeric|min:0',
            'leave_days' => 'required|numeric|min:0',
            'basic_salary' => 'required|numeric|min:0',
            'payable_basic' => 'required|numeric|min:0',
            'present_bonus' => 'required|numeric|min:0',
            'tea_allowance' => 'required|numeric|min:0',
            'mobile_bill' => 'required|numeric|min:0',
            'other_bill' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
        ]);

        $employee = User::findOrFail($request->input('user_id'));

        // Check if another payroll entry exists for the same employee and month
        $existingPayroll = Payroll::where('user_id', $employee->id)
            ->where('salary_month', $request->salary_month)
            ->where('id', '!=', $payroll->id)
            ->first();

        //dd($existingPayroll);
        if ($existingPayroll) {
            return redirect()->route('payrolls.edit', $payroll->id)->with('error', 'প্রদত্ত মাসের বেতন প্রদান করা হয়েছিলো।');
        }

        // Update the payroll entry with the validated data
        $payroll->update($validatedData);
        $salaryMonth = Carbon::parse($request->salary_month)->translatedFormat('F-Y');
        $expense = Expense::where('category_id',1)->where('trx_id',$payroll->trx_id)->first();
        $expense->amount = $payroll->net_salary;
        $expense->date = $payroll->payment_date;
        $expTrx = Transaction::where('trx_id',$payroll->trx_id)->first();
        $expTrx->amount = $payroll->net_salary;
        $expTrx->date = $payroll->payment_date;
        $expTrx->save();
        $expense->description = 'মাসিক বেতন - '.$payroll->user->name.' - '.$salaryMonth;
            $expense->user_id = Auth::id();
            $expense->save();

        return redirect()->route('payrolls.index')->with('success', 'বেতনের রেকর্ড সফলভাবে আপডেট করা হয়েছে।');
    }
    public function getEmployeeDays(Request $request)
    {
        $employeeId = $request->input('user_id');

        // Fetch the selected employee
        $employee = User::findOrFail($employeeId);

        // Get the current month and year
        $month = date('m',strtotime($request->salary_month));
        $year = date('Y',strtotime($request->salary_month));
        //$attendanceModel = new Attendance();
        $attendanceTotals = $this->getTotalAttendance($employeeId, $year, $month);

        $basicSalary = $employee->salary;
        $workingDays = 23;
        $presentDays = $attendanceTotals['total_present'];
        $leaveDays = $attendanceTotals['total_leave'];
        $absentDays = $attendanceTotals['total_absent'];

        $salary = ($presentDays + $leaveDays) / ($workingDays - $absentDays) * $basicSalary;
        //$salary = ceil($salary);
        $data['name'] = $employee->name;
        $data['basic_salary'] = $employee->salary;
        $data['payable_basic'] = ceil($salary);
        $data['working_days'] = $workingDays;
        $data['total_present'] = $attendanceTotals['total_present'];
        $data['total_leave'] = $attendanceTotals['total_leave'];
        $data['total_absent'] = $attendanceTotals['total_absent'];
        return json_encode($data);
    }

    public function getTotalAttendance($employeeId, $year, $month)
    {
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $totalPresent = Attendance::where('user_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('present', 1)
            ->count();

        $totalLeave = Attendance::where('user_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('leave', 1)
            ->count();

        $totalAbsent = Attendance::where('user_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('present', 0)
            ->where('leave', 0)
            ->count();

        $data['total_present'] = $totalPresent;
        $data['total_leave'] = $totalLeave;
        $data['total_absent'] = $totalAbsent;
        return $data;
    }

    public function destroy(Payroll $payroll)
    {
        $expense = Expense::where('category_id',1)->where('trx_id',$payroll->trx_id)->delete();
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'Payroll record deleted successfully.');
    }
}

