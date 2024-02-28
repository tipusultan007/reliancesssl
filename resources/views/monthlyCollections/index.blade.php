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
                    <h4 class="page-title">মাসিক সঞ্চয় / ঋণ / লভ্যাংশ আদায়</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8">
                <form class="row" id="importForm" action="{{ route('importMonthlySavings') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6 mb-2">
                        <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" id="submitButton" class="btn btn-primary">
                            <span id="spinnerIcon" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                            Import
                        </button>
                    </div>

                </form>
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                            মাসিক সঞ্চয় / ঋণ ফেরত / লভ্যাংশ
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $dailySavings = \App\Models\MonthlySaving::where('status','active')->get();
                        @endphp
                        <form class="needs-validation" id="form-submit">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="account_no">হিসাব নং</label>
                                    <select class="form-control select2" id="account_no" name="account_no"
                                            data-allow-clear="on" data-placeholder="Select" data-toggle="select2"
                                            required>
                                        <option value="">Select</option>
                                        @foreach($dailySavings as $item)
                                            <option value="{{ $item->account_no }}">{{ $item->account_no }}
                                                - {{ $item->member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="date"> তারিখ </label>
                                    <div class="position-relative" id="datepicker1">
                                        <input id="check_date" name="check_date" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="monthly_amount" class="form-label">মাসিক জমা</label>
                                    <input type="number" name="monthly_amount" id="monthly_amount" class="form-control">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="monthly_installments" class="form-label">কিস্তি সংখ্যা</label>
                                    <input type="number" name="monthly_installments" id="monthly_installments"
                                           class="form-control" min="0">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="monthly_interest" class="form-label"> মাসিক লভ্যাংশ </label>
                                    <input type="number" name="monthly_interest" id="monthly_interest"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-2">
                                    <label for="interest_installments" class="form-label">লভ্যাংশ কিস্তি সংখ্যা</label>
                                    <input type="number" name="interest_installments" id="interest_installments"
                                           class="form-control" min="0" >
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="" class="form-label">ঋণ ফেরত</label>
                                    <input type="number" name="loan_installment" id="loan_installment"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-2">
                                    <label for="late_fee" class="form-label">বিলম্ব ফি </label>
                                    <input type="number" name="late_fee" id="late_fee" class="form-control">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="due" class="form-label">বকেয়া</label>
                                    <input type="number" name="due" class="form-control" id="due">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="due_return" class="form-label">বকেয়া পরিশোধ</label>
                                    <input type="number" name="due_return" class="form-control" id="due_return">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="form-label">চক্রবৃদ্ধি সুদ</label>
                                    <input type="number" name="extra_interest" id="extra_interest" class="form-control">
                                </div>
                                <div class="col-md-5 mb-2">
                                    <label class="form-label" for="date"> তারিখ </label>
                                    <div class="position-relative" id="datepicker2">
                                        <input id="date" name="date" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <button class="btn btn-success w-100" id="submit">সাবমিট</button>
                                </div>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-center text-white">সদস্য তথ্য</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center avatar">

                        </p>
                        <table class="table table-sm details">

                        </table>

                        <div class="btn-details d-flex justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="datatables">
                            <thead>
                            <tr>
                                <th>নাম</th>
                                <th>হিসাব নং</th>
                                <th>জমা</th>
                                <th>লভ্যাংশ</th>
                                <th>ঋণ ফেরত</th>
                                <th>বিলম্ব ফি</th>
                                <th>বকেয়া</th>
                                <th>বকেয়া ফেরত</th>
                                <th>তারিখ</th>
                                <th>#</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- container -->
    <div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">বিবরণী</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="printDetails">
                        <table class="table table-bordered table-sm table-details">
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-primary print" target="_blank">প্রিন্ট করুন</a>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">সম্পাদন</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" id="form-edit">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="row g-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="account_no">হিসাব নং</label>
                                <select class="form-control select2 account_no"  name="account_no"
                                        data-allow-clear="on" data-placeholder="Select" data-toggle="select2"
                                        required>
                                    <option value="">Select</option>
                                    @foreach($dailySavings as $item)
                                        <option value="{{ $item->account_no }}">{{ $item->account_no }}
                                            - {{ $item->member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{--<div class="col-md-6 mb-2">
                                <label class="form-label" for="date"> তারিখ </label>
                                <input id="check_date" name="check_date" type="date" class="form-control">
                            </div>--}}
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="monthly_amount" class="form-label">মাসিক জমা</label>
                                <input type="number" name="monthly_amount" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="monthly_installments" class="form-label">কিস্তি সংখ্যা</label>
                                <input type="number" name="monthly_installments"
                                       class="form-control" min="0">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="monthly_interest" class="form-label"> মাসিক লভ্যাংশ </label>
                                <input type="number" name="monthly_interest"
                                       class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="interest_installments" class="form-label">লভ্যাংশ কিস্তি সংখ্যা</label>
                                <input type="number" name="interest_installments"
                                       class="form-control" min="0" >
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="" class="form-label">ঋণ ফেরত</label>
                                <input type="number" name="loan_installment"
                                       class="form-control">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label for="late_fee" class="form-label">বিলম্ব ফি </label>
                                <input type="number" name="late_fee" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="due" class="form-label">বকেয়া</label>
                                <input type="number" name="due" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="due_return" class="form-label">বকেয়া পরিশোধ</label>
                                <input type="number" name="due_return" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label">চক্রবৃদ্ধি সুদ</label>
                                <input type="number" name="extra_interest" class="form-control">
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="form-label" for="date"> তারিখ </label>
                                <div class="position-relative" id="datepicker2">
                                    <input name="date" class="form-control" type="date" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary" id="btn-update">আপডেট করুন</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->

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
$(document).ready(function () {
            var isSubmitting = false;
            var form = $('#importForm');
            var submitButton = $('#submitButton');
            var spinnerIcon = $('#spinnerIcon');

            form.submit(function (e) {
                e.preventDefault();

                // Prevent double-clicking
                if (isSubmitting) {
                    return;
                }

                var formData = new FormData(form[0]);

                // Disable the submit button
                submitButton.prop('disabled', true);

                // Show spinner
                spinnerIcon.show();

                // Set the flag to indicate submission is in progress
                isSubmitting = true;

                $.ajax({
                    url: '{{ route('importMonthlySavings') }}', // Replace with the actual URL
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Display success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });

                        // Optionally, do something with the response data
                        console.log(response.data);

                        // Reset the form after success
                        form.trigger('reset');
                    },
                    error: function (xhr, status, error) {
                        // Display error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred during the import.',
                        });
                    },
                    complete: function () {
                        // Enable the submit button
                        submitButton.prop('disabled', false);

                        // Hide spinner
                        spinnerIcon.hide();

                        // Reset the flag
                        isSubmitting = false;
                    },
                });
            });
        });
        var interest_rate = 0;
        var monthly_amount = 0;

        var edit_interest_rate = 0;
        var edit_monthly_amount = 0;

        $(document).on("change","#monthly_installments",function () {
            let installments = $(this).val();
            $("#monthly_amount").val(monthly_amount*installments);
        })
        $(document).on("change","#interest_installments",function () {
            let installments = $(this).val();
            $("#monthly_interest").val(interest_rate*installments);
        })

        $(document).on("change","#form-edit input[name='monthly_installments']",function () {
            let installments = $(this).val();
            $("#form-edit input[name='monthly_amount']").val(edit_monthly_amount*installments);
        })
        $(document).on("change","#form-edit input[name='interest_installments']",function () {
            let installments = $(this).val();
            $("#form-edit input[name='monthly_interest']").val(edit_interest_rate*installments);
        })

        $('.date')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#date').val(e.format('yyyy-mm-dd'));
            });

        $("#btn-update").on("click", function () {
            var id = $("#form-edit input[name='id']").val()
            var $this = $("#btn-update"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#form-edit").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ url('monthly-collections') }}/"+id,
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    console.log(data);
                    $this.attr('disabled', false).html($caption);
                    //$(".spinner").hide();
                    $("#account_no").val("").trigger('change');
                    monthly_amount = 0;
                    interest_rate = 0;
                    if (data == "empty") {
                        $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                        $("#monthly_interest").val("");
                        $("#monthly_amount").val("");
                        $("#monthly_installments").val("");
                        $("#interest_installments").val("");
                        $("#due").val("");
                        $("#due_return").val("");
                        $(".details").empty();
                        $(".btn-details").empty();
                        $(".avatar").empty();
                        $("#loan_installment").val("");
                        $("#modalEdit").modal("hide");
                    } else {
                        $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                        $("#monthly_interest").val("");
                        $("#due").val("");
                        $("#due_return").val("");
                        $("#monthly_amount").val("");
                        $("#monthly_installments").val("");
                        $("#loan_installment").val("");
                        $("#interest_installments").val("");
                        $(".details").empty();
                        $(".avatar").empty();
                        $(".btn-details").empty();
                        $("#datatables").DataTable().destroy();
                        $("#modalEdit").modal("hide");
                        loadData();
                    }
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#account_no").val("").trigger('change');
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                }
            })
        })
        $("#submit").on("click", function () {
            var $this = $("#submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#form-submit").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('monthly-collections.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    console.log(data);
                    $this.attr('disabled', false).html($caption);
                    //$(".spinner").hide();
                    $("#account_no").val("").trigger('change');
                    monthly_amount = 0;
                    interest_rate = 0;
                    if (data == "empty") {
                        $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                        $("#monthly_interest").val("");
                        $("#monthly_amount").val("");
                        $("#monthly_installments").val("");
                        $("#interest_installments").val("");
                        $("#due").val("");
                        $("#due_return").val("");
                        $(".details").empty();
                        $(".btn-details").empty();
                        $(".avatar").empty();
                        $("#loan_installment").val("");
                    } else {
                        $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                        $("#monthly_interest").val("");
                        $("#due").val("");
                        $("#due_return").val("");
                        $("#monthly_amount").val("");
                        $("#monthly_installments").val("");
                        $("#loan_installment").val("");
                        $("#interest_installments").val("");
                        $(".details").empty();
                        $(".avatar").empty();
                        $(".btn-details").empty();
                        $("#datatables").DataTable().destroy();
                        loadData();
                    }
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#account_no").val("").trigger('change');
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                }
            })
        })

        $(document).on("change","#check_date",function () {
            checkAccount();
        })

        function checkAccount() {
            let id = $("#account_no option:selected").val();
            let date = $("#check_date").val();
            let path = "{{ asset('uploads') }}";
            monthly_amount = 0;
            interest_rate = 0;
            $(".details").empty();
            $("#monthly_installments").val("");
            $("#interest_installments").val("");
            $("#monthly_amount").val("");
            $("#monthly_interest").val("");
            $(".avatar").empty();
            $('.btn-details').empty();
            $.ajax({
                url: "{{ url('getMonthlySavings') }}/",
                data: {account_no: id, date: date},
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $(".details").html("");
                    $('.btn-details').html("");
                    $(".avatar").html("");
                    if (data.savings.member.photo != "") {
                        $(".avatar").append(`
                    <img src="${path}/${data.savings.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                    }
                    $('.details').append(`
                            <tr>
                            <td><b> হিসাব নংঃ </b></td>  <td>${data.savings.account_no}</td>
                            </tr>
                                                <tr>
                            <td><b> নামঃ </b></td>  <td>${data.savings.member.name}</td>
                            </tr>
                            <tr>
                            <td><b> মোবাইলঃ </b></td>  <td>${data.savings.member.nid_no}</td>
                            </tr>
                            <tr>
                            <td><b> পিতার নামঃ </b></td>  <td>${data.savings.member.father_name}</td>
                            </tr>
                            <tr>
                            <td><b> এন আইডিঃ </b></td>  <td>${data.savings.member.nid_no}</td>
                            </tr>                    `);


                    $('.details').append(`
                    <tr>
                        <td><b> মাসিক সঞ্চয় জমাঃ </b></td>  <td>${data.savings.total} টাকা</td>
                    </tr>
                   `);


                    if (data.loan != "") {
                        $('.details').append(`
                    <tr>
                        <td><b> মোট ঋণ প্রদানঃ </b></td>  <td>${data.loan.loan_amount} টাকা</td>
                        </tr>
                        <tr>
                        <td><b> অবশিষ্ট ঋণঃ</b></td>  <td>${data.loan.remain_balance} টাকা</td>
                        </tr>
                   `);
                    }

                    if (data.detail.due_savings>0)
                    {
                        $("#monthly_installments").prop("readonly",false);
                        $("#monthly_installments").val(data.detail.due_savings);
                        $("#monthly_amount").val(data.detail.monthly_amount * data.detail.due_savings);
                        monthly_amount = data.detail.monthly_amount;
                    }else {
                        $("#monthly_installments").prop("readonly",true)
                    }
                    if (data.detail.due_interest>0)
                    {
                        $("#interest_installments").prop("readonly",false)
                        $("#interest_installments").val(data.detail.due_interest);
                        $("#monthly_interest").val(data.detail.interest_rate * data.detail.due_interest);
                        interest_rate = data.detail.interest_rate;
                    }else {
                        $("#interest_installments").prop("readonly",true)
                    }

                    if (data.due !="")
                    {
                        if (data.due.remain>0) {
                            $('.details').append(`
                                            <tr>
                        <td><b> মোট বকেয়াঃ </b></td>  <td>${data.due.due} টাকা</td>
                        </tr>
                        <tr>
                        <td><b> বকেয়া অবশিষ্টঃ </b></td>  <td>${data.due.remain} টাকা</td>
                        </tr>
                   `);
                        }
                    }

                    $('.btn-details').append(`
                       <a class="btn btn-sm bg-warning" href="{{ url('monthly-savings')}}/${data.savings.id}">বিস্তারিত দেখুন</a></td>
                    `);
                }
            })
        }

        $("#account_no").on("change", function () {
            checkAccount();
        })


        var checkForm = function (form) { /* Submit button was clicked */

            console.log(hi);
            form.myButton.disabled = true;
            form.myButton.value = "Please wait...";
            return true;
        };

        var resetForm = function (form) { /* Reset button was clicked */
            form.myButton.disabled = false;
            form.myButton.value = "Submit";
        };
        loadData();
        function loadData()
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataMonthlyCollections') }}"
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'monthly_amount' },
                    { data: 'monthly_interest' },
                    { data: 'loan_installment' },
                    { data: 'late_fee' },
                    { data: 'due' },
                    { data: 'due_return' },
                    { data: 'date' },
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
                        url: "{{url('monthly-collections')}}/" + id,
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


        $(document).on("click",".edit",function () {
            var id = $(this).data('id');
            $("#form-edit input[name='id']").val(id);
            $("#form-edit").trigger('reset');
            edit_monthly_amount = 0;
            edit_interest_rate = 0;
            $.ajax({
                url: "{{ url('getDetailsMonthly') }}/"+id,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var collection = data.collection;
                    var detail = data.detail;
                    edit_monthly_amount = detail.monthly_amount;
                    edit_interest_rate = detail.interest_rate;
                    $("#form-edit select.account_no").val(collection.account_no).trigger("change");
                    if (collection.monthly_amount>0) {
                        $("#form-edit input[name='monthly_amount']").val(collection.monthly_amount);
                        $("#form-edit input[name='monthly_installments']").val(collection.monthly_installments);
                    }
                    if (collection.monthly_interest>0)
                    {
                        $("#form-edit input[name='monthly_interest']").val(collection.monthly_interest);
                        $("#form-edit input[name='interest_installments']").val(collection.interest_installments);
                    }
                    if (collection.loan_installment>0)
                    {
                        $("#form-edit input[name='loan_installment']").val(collection.loan_installment);
                    }
                    if (collection.due>0)
                    {
                        $("#form-edit input[name='due']").val(collection.due);
                    }
                    if (collection.due_return>0)
                    {
                        $("#form-edit input[name='due_return']").val(collection.due_return);
                    }
                    if (collection.extra_interest>0)
                    {
                        $("#form-edit input[name='extra_interest']").val(collection.extra_interest);
                    }
                    if (collection.late_fee>0)
                    {
                        $("#form-edit input[name='late_fee']").val(collection.late_fee);
                    }
                    $("#form-edit input[name='date']").val(collection.date);
                    var due = collection.due;
                    var dueReturn = collection.due_return;
                    var lateFee = collection.late_fee;
                    var date = collection.date;
                    var accountNo = collection.account_no;
                    var extraInterest = collection.extra_interest;
                    $("#modalEdit").modal("show");
                }
            })
        })
        $(document).on("click",".view",function () {
            let id = $(this).data('id');
            $(".table-details tbody").empty();
            $.ajax({
                url: "{{ url('getDetailsMonthly') }}/"+id,
                dataType: "json",
                success: function (data) {
                    $(".table-details tbody").append(`
<tr>
<th>হিসাব নং</th>    <td class="text-end">${data.collection.account_no}</td>
</tr>
                    <tr>
<th>নাম</th>    <td class="text-end">${data.collection.member.name}</td>
</tr>
                    `);
                    if (data.collection.monthly_amount>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>জমা</th>    <td class="text-end">${data.collection.monthly_amount} টাকা</td>
</tr>
                    `);
                    }
                    if (data.collection.monthly_interest>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>লভ্যাশ আদায়</th>    <td class="text-end">${data.collection.monthly_interest} টাকা</td>
</tr>

                    `);
                    }
                    if (data.collection.extra_interest>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th> চক্রবৃদ্ধি মুনাফা </th>    <td class="text-end">${data.collection.extra_interest} টাকা</td>
</tr>

                    `);
                    }
                    if (data.collection.loan_installment>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>ঋণ ফেরত</th>    <td class="text-end">${data.collection.loan_installment} টাকা</td>
</tr>
                    `);
                    }
                    if (data.collection.balance>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>অবশিষ্ট ঋণ</th>    <td class="text-end">${data.collection.balance} টাকা</td>
</tr>
                    `);
                    }
                    if (data.collection.late_fee>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>বিলম্ব ফি</th>    <td class="text-end">${data.collection.late_fee} টাকা</td>
</tr>
                    `);
                    }
                    if (data.collection.due>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>বকেয়া</th>    <td class="text-end">${data.collection.due} টাকা</td>
</tr>
                    `);
                    }
                    if (data.collection.due_return>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>বকেয়া পরিশোধ</th>    <td class="text-end">${data.collection.due_return} টাকা</td>
</tr>
                    `);
                    }

                    $(".table-details tbody").append(`
<tr>
<th>ট্রানজেকশন আইডি</th>    <td class="text-end">${data.collection.trx_id}</td>
</tr>
                    `);

                    $(".table-details tbody").append(`
<tr>
<th>তারিখ</th>    <td class="text-end">${dateFormat(data.collection.date, 'dd/MM/yyyy')}</td>
</tr>
                    `);

                    $(".print").prop("href","{{ url('print/monthly') }}/"+data.collection.id)
                }
            })
            $("#modalView").modal("show");
        })

        function dateFormat(input_D, format_D) {
            // input date parsed
            const date = new Date(input_D);

            //extracting parts of date string
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            //to replace month
            format_D = format_D.replace("MM", month.toString().padStart(2,"0"));

            //to replace year
            if (format_D.indexOf("yyyy") > -1) {
                format_D = format_D.replace("yyyy", year.toString());
            } else if (format_D.indexOf("yy") > -1) {
                format_D = format_D.replace("yy", year.toString().substr(2,2));
            }

            //to replace day
            format_D = format_D.replace("dd", day.toString().padStart(2,"0"));

            return format_D;
        }
    </script>

@endsection


