@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>কর্মীর তথ্য</h2>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>নাম:</strong> {{ $employee->name }}</p>
                        <p><strong>পিতার নাম:</strong> {{ $employee->father_name }}</p>
                        <p><strong>মাতার নাম:</strong> {{ $employee->mother_name }}</p>
                        <p><strong>ঠিকানা:</strong> {{ $employee->address }}</p>
                        <p><strong>ই-মেইল:</strong> {{ $employee->email }}</p>
                        <p><strong>মোবাইল নং:</strong> {{ $employee->phone }}</p>
                        <p><strong>নিয়োগ তারিখ:</strong> {{ $employee->hire_date }}</p>
                        <p><strong>স্ট্যাটাস:</strong> {{ $employee->employee_status }}</p>
                        <p><strong>বেসিক বেতন:</strong> {{ $employee->salary }}</p>
                        <p><strong>চাকুরীচ্যূতির তারিখ:</strong> {{ $employee->termination_date }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3>ছবি</h3>
                        @if ($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" class="img-thumbnail">
                        @else
                            <p>No photo available</p>
                        @endif
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
