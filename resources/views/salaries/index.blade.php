@extends('layouts.master')
@section('vendor-css')
    <!-- Datatables css -->
    <link href="{{asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>

@endsection
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Form Wizard</li>
                        </ol>
                    </div>
                    <h4 class="page-title"> কর্মীদের বেতন <h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                            বেতন ফরম
                        </h4>
                    </div>
                    @php
$users = \App\Models\User::all();
 @endphp
                    <div class="card-body">
                        <form>
                            @csrf
                            <div class="form-group mb-2">
                                <label for="" class="form-label">কর্মী'র নাম</label>
                                <select class="form-control select2" id="user_id" name="user_id"
                                        data-placeholder="Select" data-toggle="select2"
                                        required>
                                    <option value="">Select</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"> {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="salary" class="form-label">বেতন </label>
                                <input type="number" name="salary" id="salary" class="form-control">
                            </div>
                            <!-- Month View -->
                            <div class="mb-3 position-relative" id="datepicker5">
                                <label class="form-label">মাস</label>
                                <input type="text" class="form-control" data-provide="datepicker" name="month_year" data-date-autoclose="true" data-date-format="MM yyyy" data-date-min-view-mode="1" data-date-container="#datepicker5">
                            </div>
                            <div class="form-group mb-2">
                                <label for="" class="form-label">মন্তব্য</label>
                                <input type="text" name="description" id="description" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="date"> তারিখ </label>
                                <div class="position-relative" id="datepicker1">
                                    <input type="text" class="form-control date" value="{{ date('d/m/Y') }}"
                                           data-provide="datepicker" data-date-autoclose="true"
                                           data-date-container="#datepicker1" required>
                                    <input id="date" name="date" type="hidden" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group mb-2 d-flex align-items-end">
                                <button class="btn btn-success" id="submit">প্রদান করুন</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-white">
                            বেতন তালিকা
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table incomes-table">
                            <thead>
                            <tr>
                                <th>কর্মী'র নাম</th>
                                <th>তারিখ</th>
                                <th>মন্তব্য</th>
                                <th>মাস</th>
                                <th>বেতন</th>
                                <th>#</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->
@endsection
@section('scripts')

    <!-- Datatables js -->
    <script src="{{asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables.net-select/js/dataTables.select.min.js')}}"></script>

    <!-- Datatable Demo Aapp js -->
    <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>

    <script src="{{asset('assets/js/pages/demo.toastr.js')}}"></script>
    <script>


        $("#submit").on("click", function () {
            var $this = $("#submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("form").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('salaries.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                    $("#name").val("");
                    $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                    $(".incomes-table").DataTable().destroy();
                    loadData();
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#name").val("");
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                }
            })
        })

        loadData();
        function loadData()
        {
            $('.incomes-table').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataSalaries') }}"
                },
                columns: [
                    { data: 'name' },
                    { data: 'date' },
                    { data: 'notes' },
                    { data: 'month_year' },
                    { data: 'salary' },
                    { data: 'action' },
                ],
                buttons: ["copy", "print"],

            });
        }


        function deleteConfirmation(id) {
            swal.fire({
                title: "আপনি কি নিশ্চিত?",
                icon: 'question',
                text: "আপনি এটি ফিরিয়ে আনতে পারবেন না!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: '#0acf97',
                cancelButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছে ফেলুন!',
                cancelButtonText: 'না, বাতিল করুন',
                reverseButtons: !0
            }).then(function (e) {

                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('salaries')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                $(".incomes-table").DataTable().destroy();
                                loadData();
                                $.NotificationApp.send("Success", results.message, "bottom-right", "rgba(0,0,0,0.2)", "success")
                            } else {
                                $.NotificationApp.send("Error", results.message, "bottom-right", "rgba(0,0,0,0.2)", "error")
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
        $('.date')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#date').val(e.format('yyyy-mm-dd'));
            });
    </script>

@endsection


