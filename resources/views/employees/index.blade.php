@extends('layouts.app')

@section('content')
    <h2>Employee List</h2>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>

                    <th>নাম</th>
                    <th>মোবাইল নং</th>
                    <th>ঠিকানা</th>
                    <th>নিয়োগ তারিখ</th>
                    <th>স্ট্যাটাস</th>
                    <th>ছবি</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($employees as $employee)
                    <tr>

                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->hire_date }}</td>
                        <td>
                            @if($employee->employee_status==1)
                                <span class="badge bg-success py-1">সক্রিয়</span>
                            @else
                                <span class="badge bg-danger py-1">নিষ্ক্রিয়</span>
                            @endif
                        </td>
                        <td>
                            @if ($employee->photo)
                                <img width="60" src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" class="img-thumbnail">
                            @else
                                No photo
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">Edit</a>
                            <!-- Add delete button as needed -->
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
