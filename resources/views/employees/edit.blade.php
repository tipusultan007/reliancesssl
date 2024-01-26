@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Employee</h2>

        <div class="card">
            <div class="card-body">
                <form id="editEmployeeForm" class="row" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Display existing values for editing -->
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">নাম</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $employee->name }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="father_name" class="form-label">পিতার নাম</label>
                        <input type="text" name="father_name" id="father_name" class="form-control" value="{{ $employee->father_name }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="mother_name" class="form-label">মাতার নাম</label>
                        <input type="text" name="mother_name" id="mother_name" class="form-control" value="{{ $employee->mother_name }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label">ঠিকানা</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ $employee->address }}">
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="phone" class="form-label">মোবাইল</label>
                        <input type="tel" name="phone" id="phone" class="form-control" value="{{ $employee->phone }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="hire_date" class="form-label">নিয়োগ তারিখ</label>
                        <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ $employee->hire_date }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="employee_status" class="form-label">স্ট্যাটাস</label>
                        <select name="employee_status" id="employee_status" class="form-select select2" data-toggle="select2">
                            <option value="1" {{ $employee->employee_status==1?"selected":"" }}>সক্রিয়</option>
                            <option value="0" {{ $employee->employee_status==0?"selected":"" }}>নিস্ক্রিয়</option>
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="salary" class="form-label">বেসিক বেতন</label>
                        <input type="number" name="salary" id="salary" class="form-control" value="{{ $employee->salary }}" step="0.01">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="termination_date" class="form-label">চাকুরীচ্যূতির তারিখ</label>
                        <input type="date" name="termination_date" id="termination_date" class="form-control" value="{{ $employee->termination_date }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="photo" class="form-label">ছবি</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                        @if ($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" class="img-thumbnail mt-2">
                        @endif
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="documents" class="form-label">ডকুমেন্টস</label>
                        <input type="file" name="documents" id="documents" class="form-control">
                        @if ($employee->documents)
                            <ul>
                                <li><a href="{{ asset('storage/' . $employee->documents) }}" target="_blank">{{ $employee->documents }}</a></li>
                            </ul>
                        @endif
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                        <div class="form-group">
                            <label for="roles" class="form-label">কর্মীর ধরন</label>
                            <!-- Multiple Select -->
                            <select name="roles" class="select2 form-control" id="roles" data-toggle="select2" data-placeholder="সিলেক্ট করুণ" required>
                                <option value=""></option>
                                @foreach($roles as $key => $role)
                                    <option value="{{ $key }}" {{ $employee->hasRole($role) ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="salary" class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" id="updateEmployee" class="btn btn-primary">আপডেট</button>
                    </div>
                </form>

                <div id="message" class="mt-3"></div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#updateEmployee').on('click', function () {
                    var formData = new FormData($('#editEmployeeForm')[0]);
                    $.ajax({
                        url: '{{ route('employees.update', $employee->id) }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#message').text(response.message).addClass('alert alert-success');
                            // Update any relevant page elements or display a success message
                        },
                        error: function (xhr) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = Object.values(errors).flat().join('<br>');
                            $('#message').html(errorMessage).addClass('alert alert-danger');
                        }
                    });
                });
            });
        </script>
    </div>
@endsection
