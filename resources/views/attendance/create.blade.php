@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Create Attendance Record</h2>

        <div class="card">
            <div class="card-body">
                <form id="createAttendanceForm">
                    @csrf

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Select Employee:</label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="attendance_date" class="form-label">Date:</label>
                        <input type="date" name="date" id="attendance_date" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="present" id="present" class="form-check-input" value="1">
                        <label class="form-check-label" for="present">Present</label>
                    </div>

                    <button type="button" id="createAttendance" class="btn btn-primary">Create Attendance</button>
                </form>

                <div id="message" class="mt-3"></div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#createAttendance').on('click', function () {

                    var formData = $('#createAttendanceForm').serialize();
                    $.ajax({
                        url: '{{ route('attendance.store') }}',
                        method: 'POST',
                        data: formData,
                        success: function (response) {
                            if ($("#message").hasClass('alert alert-danger'))
                            {
                                $("#message").removeClass('alert alert-danger')
                            }
                            $('#message').text(response.message).addClass('alert alert-success');
                            // Clear form fields or reset form as needed
                            console.log(response);
                        },
                        error: function (xhr) {
                            var error = xhr.responseJSON.error;
                            if ($("#message").hasClass('alert alert-success'))
                            {
                                $("#message").removeClass('alert alert-success')
                            }
                           $('#message').text(error).addClass('alert alert-danger');
                        }
                    });
                });
            });
        </script>
    </div>
@endsection
