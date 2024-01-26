@extends('layouts.app')

@section('content')
    <h2>Mark Attendance</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('attendance.mark') }}" method="post">
        @csrf

        <label for="employee_id">Employee:</label>
        <select name="employee_id" id="employee_id">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
            @endforeach
        </select>

        <label for="present">Present:</label>
        <input type="checkbox" name="present" id="present" value="1">

        <button type="submit">Submit</button>
    </form>
@endsection
