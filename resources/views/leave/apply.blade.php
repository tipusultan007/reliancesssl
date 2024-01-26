@extends('layouts.app')

@section('content')
    <h2>Apply for Leave</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('leave.apply') }}" method="post">
        @csrf

        <label for="employee_id">Employee:</label>
        <select name="employee_id" id="employee_id">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
            @endforeach
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date">

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date">

        <label for="reason">Reason:</label>
        <textarea name="reason" id="reason" rows="4"></textarea>

        <button type="submit">Submit</button>
    </form>
@endsection
