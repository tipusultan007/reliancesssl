@extends('layouts.app')

@section('content')
    <h2>Payroll Details</h2>

    <p><strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
    <p><strong>Payment Date:</strong> {{ $payroll->payment_date }}</p>
    <p><strong>Basic Salary:</strong> {{ $payroll->basic_salary }}</p>
    <p><strong>Overtime Pay:</strong> {{ $payroll->overtime_pay }}</p>
    <p><strong>Present Bonus:</strong> {{ $payroll->present_bonus }}</p>
    <p><strong>Tea Allowance:</strong> {{ $payroll->tea_allowance }}</p>
    <p><strong>Mobile Bill:</strong> {{ $payroll->mobile_bill }}</p>
    <p><strong>Other Bill:</strong> {{ $payroll->other_bill }}</p>
    <p><strong>Deductions:</strong> {{ $payroll->deductions }}</p>
    <p><strong>Net Salary:</strong> {{ $payroll->net_salary }}</p>

    <!-- Display more details as needed -->

    <a href="{{ route('payrolls.edit', $payroll->id) }}">Edit</a>
    <a href="{{ route('payrolls.index') }}">Back to List</a>
@endsection
