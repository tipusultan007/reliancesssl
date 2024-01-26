@extends('layouts.app')

@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
$employees = \App\Models\User::pluck('name','id')
@endphp

@section('content')

    <style>
        @media print {
            body {
                visibility: hidden;
            }

            .table, .table * {
                visibility: visible;
            }

            .table {
                position: absolute;
                left: 0;
                top: 0;
            }
        }

        td.attendance-slot {
            cursor: pointer;
        }
        td.attendance-slot:hover{
            background-color: #0ac58f !important;
            color: #000;
        }
    </style>

    <h3 class="mt-4">কর্মীদের উপস্থিতি</h3>
    <div id="message"></div>
    <div class="row mt-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form id="addAttendanceForm" class="row">
                        @csrf
                        <div class="mb-3 col-md-6">
                            <label for="user_id" class="form-label">কর্মীর নাম</label>
                            <select name="user_id" id="user_id" data-placeholder="সিলেক্ট" data-toggle="select2"
                                    class="form-select select2" required>
                                <option value="">সিলেক্ট</option>
                                @foreach ($employees as $key=>$employee)
                                    <option value="{{ $key }}">{{ $employee }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="attendance_date" class="form-label">তারিখ</label>
                            <input type="date" name="date" value="{{date('Y-m-d')}}" id="attendance_date"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="mt-2">
                                <input type="checkbox" id="present" value="1" name="present" class="form-check-input">
                                <label for="present" class="form-label">উপস্থিত</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="mt-2">
                                <input type="checkbox" id="leave" name="leave" value="1" class="form-check-input">
                                <label for="leave" class="form-label">ছুটি</label>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex justify-content-end align-items-start">
                            <button type="submit" id="createAttendance" class="btn btn-primary w-100">সাবমিট</button>
                        </div>
                        {{-- <div class="form-check mb-3">
                             <input type="checkbox" name="present" id="present" class="form-check-input" value="1">
                             <label class="form-check-label" for="present">উপস্থিত</label>
                         </div>--}}


                    </form>

                    <div id="message" class="mt-3 alert-container"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('attendance.index') }}" method="get" class="mb-4 row">
                        <div class="col-md-6">
                            <label for="month" class="form-label">মাসের নাম</label>
                            <input type="month" id="month" name="month" value="{{ $selectedMonth }}"
                                   class="form-control">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">সাবমিট</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-info pb-2">
            <h2 class="text-center text-white">উপস্থিতি শীট</h2>
            <button type="button" class="btn btn-secondary fw-bolder mb-2" id="printButton">প্রিন্ট</button>
        </div>
        <div class="card-body">
            {{--<div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th colspan="{{ count(CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth())) + 3 }}" class="text-center">
                            <span class="text-dark fs-3">{{ Carbon::parse($selectedMonth)->format('F - Y') }}</span>
                        </th>
                    </tr>
                    <tr>
                        <th>কর্মীর নাম</th>

                        @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)

                            <th>{{ $day->format('d') }}</th>
                        @endforeach
                        <th>মোট উপস্থিতি</th>
                        <th>মোট অনুপস্থিতি</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($groupedData as $employeeId => $attendanceGroup)
                        @php
                            $presentCount = 0;
                            $absentCount = 0;
                        @endphp
                        <tr>
                            <td>{{ $employeeNames[$employeeId] }}</td>
                            @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)
                                @php
                                    $attendance = $attendanceGroup->where('date', $day->format('Y-m-d'))->first();
                                    if ($attendance) {
                                    if ($attendance->present) {
                                        $presentCount++;
                                    } else {
                                        $absentCount++;
                                    }
                                }
                                @endphp
                                <td>{{ $attendance ? ($attendance->present==1 ? 'P' : 'A') : '-' }}</td>
                            @endforeach
                            <td>{{ $presentCount }}</td>
                            <td>{{ $absentCount }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>--}}

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>

                    <tr>
                        <th colspan="{{ count(CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth())) + 4 }}"
                            class="text-center text-dark fs-4">
                            <h2>রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড</h2>
                            <p>১ নং সাইড হিন্দু পাড়া, বন্দর, চট্টগ্রাম</p>
                            <h3><u>কর্মচারী উপস্থিতি শীট</u></h3>
                            <h4>{{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F-Y') }}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th>কর্মীর নাম</th>
                        @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)
                            <th>{{ $day->format('d') }}</th>
                        @endforeach
                        <th>মোট উপস্থিতি</th>
                        <th>মোট অনুপস্থিতি</th>
                        <th>মোট ছুটি</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($groupedData as $employeeId => $attendanceGroup)
                        <tr>
                            <td>{{ $userNames[$employeeId] }}</td>
                            @php
                                $presentCount = 0;
                                $absentCount = 0;
                                $leaveCount = 0;
                            @endphp
                            @foreach (CarbonPeriod::create(Carbon::parse($selectedMonth)->startOfMonth(), Carbon::parse($selectedMonth)->endOfMonth()) as $day)
                                @php
                                    $attendance = $attendanceGroup->where('date', $day->format('Y-m-d'))->first();
                                    if ($attendance) {
                                        if ($attendance->present) {
                                            $presentCount++;
                                        } else {
                                            if ($attendance->leave) {
                                                $leaveCount++;
                                            } else {
                                                $absentCount++;
                                            }
                                        }
                                    }
                                @endphp
                                <td class="attendance-slot" data-date="{{ $attendance?$attendance->date:"" }}" data-user-id="{{ $attendance?$attendance->user_id:"" }}" data-name="{{$userNames[$employeeId]}}" data-present="{{ $attendance ? $attendance->present:"" }}" data-leave="{{ $attendance ? $attendance->leave:"" }}" data-id="{{ $attendance ? $attendance->id:"" }}">{{ $attendance ? ($attendance->present ? 'P' : ($attendance->leave ? 'L' : 'A')) : '-' }}</td>
                            @endforeach
                            <td>{{ $presentCount }}</td>
                            <td>{{ $absentCount }}</td>
                            <td>{{ $leaveCount }}</td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="attendance-edit-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-labelledby="warning-header-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-warning">
                        <h4 class="modal-title" id="warning-header-modalLabel">উপস্থিতি আপডেট ফরম</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-hidden="true"></button>
                    </div>
                    <form id="attendanceUpdateForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>নামঃ <span class="employee-name"></span></h3>
                                    <input type="hidden" name="user_id" id="edit_user_id">
                                    <input type="hidden" name="id" id="edit_id">
                                </div>
                                <div class="col-md-12">
                                    <label for="edit_date" class="form-label">তারিখ</label>
                                    <input type="date" name="date" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="mt-2">
                                        <input type="checkbox" id="edit_present" value="1" name="present"
                                               class="form-check-input">
                                        <label for="edit_present" class="form-label">উপস্থিত</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="mt-2">
                                        <input type="checkbox" id="edit_leave" name="leave" value="1"
                                               class="form-check-input">
                                        <label for="edit_leave" class="form-label">ছুটি</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning btn-update">আপডেট</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @endsection

        @section('scripts')
            <script>
                $(document).ready(function () {
                    $('#addAttendanceForm').on('submit', function (e) {
                        e.preventDefault();

                        var formData = $(this).serialize();

                        $.ajax({
                            url: '{{ route('attendance.store') }}',
                            type: 'POST',
                            data: formData,
                            success: function (response) {
                                console.log(response.message);
                                showAlert('success', response.message);
                                // Refresh the attendance table or perform other actions
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                                showAlert('error', 'An error occurred while adding the attendance.');
                            }
                        });
                    });

                    $('.editAttendanceForm').on('submit', function (e) {
                        e.preventDefault();

                        var formData = $(this).serialize();
                        var attendanceId = $(this).data('attendance-id');

                        $.ajax({
                            url: '{{ url('attendance') }}/' + attendanceId,
                            type: 'PUT',
                            data: formData,
                            success: function (response) {
                                console.log(response.message);
                                showAlert('success', response.message);
                                // Refresh the attendance table or perform other actions
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                                showAlert('error', 'An error occurred while updating the attendance.');
                            }
                        });
                    });

                    function showAlert(type, message) {
                        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                        var alertHtml = '<div class="alert ' + alertClass + '">' + message + '</div>';
                        $('.alert-container').html(alertHtml).fadeIn().delay(3000).fadeOut();
                    }

                    $(".attendance-slot").click(function() {
                        var dataId = $(this).attr("data-id");
                        var dataUserId = $(this).attr("data-user-id");
                        var dataName = $(this).attr("data-name");
                        var dataPresent = $(this).attr("data-present");
                        var dataLeave = $(this).attr("data-leave");
                        var dataDate = $(this).attr("data-date");

                        if (dataId !== undefined && dataId !== "") {
                            $("#attendanceUpdateForm .employee-name").text(dataName);
                            $("#attendanceUpdateForm input[name='id']").val(dataId);
                            $("#attendanceUpdateForm input[name='user_id']").val(dataUserId);
                            $("#attendanceUpdateForm input[name='date']").val(dataDate);
                            if (dataPresent==1)
                            {
                                $("#edit_present").prop("checked", true);
                            }
                            if (dataLeave==1)
                            {
                                $("#edit_leave").prop("checked", true);
                            }
                            $("#attendance-edit-modal").modal('show');
                            // You can now use the dataId variable to do whatever you want with the data-id value
                        } else {
                            console.log("data-id is empty or undefined");
                        }
                    });

                    $('.btn-update').on('click', function () {

                        var dataId = $("#attendanceUpdateForm input[name='id']").val();
                        var formData = new FormData($('#attendanceUpdateForm')[0]);
                        $.ajax({
                            url: '{{ url('attendance') }}/'+dataId,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#message').text(response.message).addClass('alert alert-success');
                                $("#attendance-edit-modal").modal('hide')
                                $('#attendanceUpdateForm').trigger('reset');
                                location.reload();
                                // Update any relevant page elements or display a success message
                            },
                            error: function (xhr) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = Object.values(errors).flat().join('<br>');
                                $('#message').html(errorMessage).addClass('alert alert-danger');
                                $("#attendance-edit-modal").modal('hide');
                                $('#attendanceUpdateForm').trigger('reset');
                            }
                        });
                    });
                });
            </script>
            <script>
                document.getElementById('printButton').addEventListener('click', function () {
                    window.print();
                });
            </script>
@endsection
