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
সদস্য তালিকা
@endsection
@section('content')
    <style>
        @media print {
            body {
                background-color: #ffffff; /* Set your desired background color */
            }
        }
    </style>
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
                    <h4 class="page-title">সদস্য </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3"> সকল সদস্য তালিকা</h4>
                        <table id="datatable-buttons" class="table table-bordered table-sm dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>ছবি </th>
                                <th>নাম </th>
                                <th>পিতা/স্বামীর নাম</th>
                                <th>মোবাইল</th>
                                <th>এন আই ডি</th>
                                <th>যোগদানের তারিখ</th>
                                <th>সঞ্চয় জমা</th>
                                <th>অবশিষ্ট ঋণ</th>
                                <th>স্ট্যাটাস</th>
                                <th>একশন</th>
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

      {{-- <!-- Datatable Demo Aapp js -->
    <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>--}}
    <script>
        loadData();
        function loadData()
        {
            $('#datatable-buttons').DataTable({
                "proccessing": true,
                "serverSide": true,
                order: [[5, 'desc']],
                "ajax":{
                    "url": "{{ url('membersData') }}"
                },
                dom: '<"row"<"col-4"l><"col-4"B><"col-4"f>>rt<"row"<"col-6"i><"col-6"p>>',
                buttons: [
                    {
                        extend: 'print',
                        title: '',
                        customize: function(win) {
                            // Add custom header content to the print view
                            $(win.document.body).prepend('' +
                                '<div class="text-center"><h2>রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড</h2>'+
                                '<h4>১ নং সাইড হিন্দু পাড়া, বন্দর, চট্টগ্রাম</h4>'+
                                '<h3><u>সদস্য তালিকা</u></h3></div> <hr>'
                            );
                        },
                        exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8 ]
                        },
                    }
                ],
                columns: [
                    { data: 'photo',orderable: false },
                    { data: 'name' },
                    { data: 'father_name', orderable: false },
                    { data: 'phone' , orderable: false},
                    { data: 'nid_no' , orderable: false},
                    { data: 'join_date' },
                    { data: 'deposited' , orderable: false},
                    { data: 'remainLoan', orderable: false },
                    { data: 'status' },
                    { data: 'action' },
                ]
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
                        url: "{{url('members')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.success === true) {
                                $("#datatable-buttons").DataTable().destroy();
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


