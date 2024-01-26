@extends('layouts.master')
@section('vendor-css')
    <!-- Datatables css -->
    <link href="{{asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />


@endsection
@section('title')
    কর্মী হাজিরা তালিকা
@endsection
@section('content')
    <div class="nk-block">
        <div class="card">
            <div class="card-header">
                Attendance By Date
            </div>
            <div class="card-body">
                <form method="get" action="{{ route('daily-attendances.index') }}">
                    <div class="row input-daterange">

                        <div class="col-md-4">
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date')?? date("Y-m-d") }}" />
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="filter" id="filter" class="btn btn-primary">Check</button>
                            <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-header">
                        {{__('Daily Attendance')}}
                    </div>

                    <div class="card-body">
                        <form>
                            @csrf
                            <table class="table attendance_table">
                                <thead>
                                <tr>
                                    <th>{{__('Employee')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Time In')}}</th>
                                    <th>{{__('Time Out')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                @forelse($users as $user)
                                    @php
                                        $attendance = \App\Models\DailyAttendance::where([
         'user_id' => $user->id,'date' => request('from_date')?? date("Y-m-d")
     ])->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            {{--<input type="hidden" name="user_id" id="user_id">--}}
                                            <div class="user" data-user="{{$user->id}}">{{ $user->name }}</div>
                                        </td>
                                        <td>
                                            <input class="form-control date" type="date" name="date" value="{{ request('from_date')?? date("Y-m-d") }}">
                                        </td>

                                        <td>
                                            <div class="time_in">{{$attendance->time_in??''}}</div>
                                        </td>

                                        <td>
                                            <div class="time_out">{{$attendance->time_out??''}}</div>
                                        </td>


                                        <td>
                                            @if($attendance)
                                                @if($attendance->status=='Present')
                                                    <span class="badge badge-dot badge-success">{{__('Present')}}</span>
                                                @elseif($attendance->status=='Absent')
                                                    <span class="badge badge-dot badge-danger">{{__('Absent')}}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>

                                            @if($attendance)
                                                @if($attendance->status=='Present')
                                                    <button class="btn btn-sm btn-danger btnUpdateAbsent" type="button" data-bs-status="Absent" data-bs-toggle="modal" data-bs-target="#modalUpdateAbsent"><i class="fa-solid fa-check"></i></button>
                                                    <button class="btn btn-sm btn-info mr-3 btnEdit" type="button" data-bs-status="present" data-bs-toggle="modal" data-bs-target="#modalUpdatePresent"><i class="fa-regular fa-pen-to-square"></i></button>
                                                @elseif($attendance->status=='Absent')
                                                    <button class="btn btn-sm btn-success btnUpdatePresent" type="button" data-bs-status="Present" data-bs-toggle="modal" data-bs-target="#modalUpdatePresent"><i class="fa ni ni-done"></i></button>
                                                @endif
                                            @else
                                                <button class="btn btn-sm btn-success btnPresent" type="button" data-bs-status="Present" data-bs-toggle="modal" data-bs-target="#modalPresent"><i class="uil uil-check"></i></button>
                                                <button class="btn btn-sm btn-danger btnAbsent" type="button" data-bs-status="Absent" data-bs-toggle="modal" data-bs-target="#modalAbsent"><i class="uil uil-times"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </table>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal Form Present-->
    <div class="modal fade" tabindex="-1" id="modalPresent">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Attendance</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{ route('daily-attendances.store') }}" method="POST" id="formPresent" class="row form-validate is-alter">
                        @csrf
                        <div class="form-group col-md-6">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="status" id="status" value="1">
                            <input type="hidden" name="date" id="date" value="{{ date("Y-m-d") }}">
                            <label class="form-label" for="time_in">Time In</label>
                            <div class="form-control-wrap">
                                <input type="time" class="form-control time" name="time_in" id="time_in" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="time_out">Time Out</label>
                            <div class="form-control-wrap">
                                <input type="time" class="form-control" name="time_out" id="time_out" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label" for="note">Note</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control" name="note" id="note" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-6 mt-3">
                            <button type="submit" id="btnPresent" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="modalAbsent">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body modal-body-lg text-center">
                    <div class="nk-modal">
                        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                        <h4 class="nk-modal-title">Are you sure?</h4>
                        <form action="{{route('daily-attendances.store')}}" id="formAbsent" method="POST">
                            @csrf
                            <div class="nk-modal-text">

                                <input type="hidden" name="user_id" id="user_id">
                                <input type="hidden" name="status" id="status" value="0">
                                <input type="hidden" name="date" id="date" value="{{ date("Y-m-d") }}">

                            </div>
                            <div class="nk-modal-action mt-5">
                                <a href="#" class="btn btn-sm btn-mw btn-light mr-3" data-dismiss="modal">Return</a>
                                <button type="submit" id="btnAbsent" class="btn btn-sm btn-mw btn-danger">Absent</button>
                            </div>
                        </form>
                    </div>
                </div><!-- .modal-body -->
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="modalUpdatePresent">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Attendance</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form id="formUpdatePresent" action="{{ url('updateAttendance') }}" method="POST" class="row form-validate is-alter">
                        @csrf
                        <div class="form-group col-md-6">
                            <input type="hidden" name="user_id">
                            <input type="hidden" name="date">
                            <input type="hidden" name="status" id="status" value="1">
                            <label class="form-label" for="time_in">Time In</label>
                            <div class="form-control-wrap">
                                <input type="time" class="form-control" name="time_in" id="time_in" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="time_out">Time Out</label>
                            <div class="form-control-wrap">
                                <input type="time" class="form-control" name="time_out" id="time_out" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group col-md-12 mt-3">
                            <label class="form-label" for="note">Note</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control" name="note" id="note" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-6 mt-3">
                            <button type="submit" id="btnUpdatePresent" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="modalUpdateAbsent">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body modal-body-lg text-center">
                    <div class="nk-modal">
                        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                        <h4 class="nk-modal-title">Are you sure?</h4>
                        <form action="{{ url('updateAttendance') }}" method="POST" id="formUpdateAbsent">
                            @csrf
                            <div class="nk-modal-text">
                                <input type="hidden" name="user_id">
                                <input type="hidden" name="status" value="0">
                                <input type="hidden" name="date">
                                <input type="hidden" name="time_in" value="">
                                <input type="hidden" name="time_out" value="">
                            </div>
                            <div class="nk-modal-action mt-5">
                                <a href="#" class="btn btn-sm btn-mw btn-light mr-3" data-dismiss="modal">Return</a>
                                <button type="submit" id="btnUpdateAbsent" class="btn btn-sm btn-mw btn-danger">Absent</button>
                            </div>
                        </form>
                    </div>
                </div><!-- .modal-body -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent

    <script>


        //$('#time_in').timepicker();
       // $('#time_out').timepicker();


        $("table.attendance_table").on("click", ".btnPresent", function() {
            var rowindex = $(this).closest('tr').index();
            var user_id = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.user').data('user');
            var date = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.date').val();
            $("#formPresent input[name=user_id]").val(user_id);
            $("#formPresent input[name=date]").val(date);
            //$("#formPresent input[name=status]").val("Present");
        });
        $("table.attendance_table").on("click", ".btnAbsent", function() {
            var rowindex = $(this).closest('tr').index();
            var user_id = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.user').data('user');
            var date = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.date').val();
            $("#formAbsent input[name=user_id]").val(user_id);
            $("#formAbsent input[name=date]").val(date);
            //$("#formPresent input[name=status]").val("Present");
        });


        $("table.attendance_table").on("click", ".btnUpdatePresent", function() {
            var rowindex = $(this).closest('tr').index();
            var user_id = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.user').data('user');
            var date = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.date').val();
            $("#formUpdatePresent input[name=user_id]").val(user_id);
            $("#formUpdatePresent input[name=date]").val(date);
            //$('#formUpdatePresent #time_in').timepicker();
            //$('#formUpdatePresent #time_out').timepicker();

            //$("#formPresent input[name=status]").val("Present");
        });
        $("table.attendance_table").on("click", ".btnUpdateAbsent", function() {
            var rowindex = $(this).closest('tr').index();
            var user_id = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.user').data('user');
            var date = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.date').val();
            $("#formUpdateAbsent input[name=user_id]").val(user_id);
            $("#formUpdateAbsent input[name=date]").val(date);
        });

        $("table.attendance_table").on("click", ".btnEdit", function() {
            //$('#formUpdatePresent #time_out').timepicker();
            var rowindex = $(this).closest('tr').index();
            var user_id = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.user').data('user');
            var date = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.date').val();
            var time_in = $('table.attendance_table tr:nth-child(' + (rowindex + 1) + ')').find('.time_in').text();

            console.log(time_in);
            $("#formUpdatePresent input[name=user_id]").val(user_id);
            $("#formUpdatePresent input[name=time_in]").val(time_in);
            $("#formUpdatePresent input[name=date]").val(date);
        });




    </script>

@endsection
