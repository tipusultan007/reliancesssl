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
@section('content')
    <div class="container-fluid">
        @php
$deposit = \App\Models\FdrDeposit::sum('amount');
$withdraw = \App\Models\FdrWithdraw::sum('amount');
$remain = \App\Models\FdrDeposit::sum('remain');
 @endphp

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
                    <h4 class="page-title">FDR সঞ্চয়</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header pb-0">
                        <h4 class="card-title">মোট জমা</h4>
                    </div>
                    <div class="card-body">
                        <h4 class="text-primary">{{ $deposit }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header pb-0">
                        <h4 class="card-title">মোট উত্তোলন</h4>
                    </div>
                    <div class="card-body">
                        <h4 class="text-primary">{{ $withdraw }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header pb-0">
                        <h4 class="card-title">অবশিষ্ট জমা</h4>
                    </div>
                    <div class="card-body">
                        <h4 class="text-primary">{{ $remain }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3"> FDR সঞ্চয় তালিকা</h4>
                        <table id="datatables" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>নাম </th>
                                <th>হিসাব নং</th>
                                <th>হিসাব শুরুর তারিখ</th>
                                <th>FDR পরিমাণ</th>
                                <th>ব্যালেন্স</th>
                                <th>মুনাফা উত্তোলন</th>
                                <th>স্ট্যাটাস</th>
                                <th>অ্যাকশন</th>
                            </tr>
                            </thead>
                        </table>


                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
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
    {{--<script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>--}}

    <script>
        loadData();
        function loadData()
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataAllFdr') }}"
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'date' },
                    { data: 'fdr_amount' },
                    { data: 'fdr_balance' },
                    { data: 'profit' },
                    { data: 'status' },
                    { data: 'action' },
                ],
                /* columnDefs: [
                     {
                         // Actions
                         targets: 7,
                         title: 'Actions',
                         orderable: false,
                         render: function (data, type, full, meta) {
                             let id = full['id'];
                             return (
                                 '<div class="btn-group">' +
                                 '<a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">menu' +
                                 '</a>' +
                                 '<div class="dropdown-menu dropdown-menu-end">' +
                                 '<a href="{{ url('members') }}/'+id+'" class="dropdown-item">' +
                                'Details</a>' +
                                '<a href="javascript:;" data-id="'+id+'" class="dropdown-item delete-record">' +
                                'Delete</a></div>' +
                                '</div>' +
                                '</div>'+
                                '<a href="javascript:;" class="item-edit" data-id="'+id+'">Edit' +
                                '</a>'
                            );
                        }
                    }
                ],*/
                // Buttons with Dropdown
                buttons: ["copy", "print"],

            });
        }

        function deleteConfirmation(id) {
            swal.fire({
                title: "আপনি কি নিশ্চিত?",
                icon: 'question',
                text: "আপনি এটি ফিরিয়ে আনতে পারবেন না!",
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
                        url: "{{url('fdr')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.success === true) {
                                $("#datatables").DataTable().destroy();
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
    </script>
@endsection


