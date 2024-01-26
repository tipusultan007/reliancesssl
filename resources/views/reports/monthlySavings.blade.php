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
        <div class="card">
            <div class="card-body">
                <form >
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label" for="date1"> শুরুর তারিখ </label>
                            <div class="position-relative" id="datepicker1">
                                <input id="date1" name="date1" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label" for="date2"> শেষ তারিখ </label>
                            <div class="position-relative" id="datepicker2">
                                <input id="date2" name="date2" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button class="btn btn-success w-100" type="button" id="submit">সাবমিট</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
                               <input class="form-control" name="account_no" id="edit_account_no" readonly>
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
var edit_monthly_amount = 0;

        

        $(document).on("change","#form-edit input[name='monthly_installments']",function () {
            let installments = $(this).val();
            $("#form-edit input[name='monthly_amount']").val(edit_monthly_amount*installments);
        })
        loadData();
        function loadData(date1="", date2="")
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('monthlyCollectionsData') }}",
                    data:{ date1: date1, date2: date2}
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'monthly_amount' },
                    { data: 'late_fee' },
                    { data: 'due' },
                    { data: 'due_return' },
                    { data: 'date' },
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

        $('.date1')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#date1').val(e.format('yyyy-mm-dd'));
            });
        $('.date2')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#date2').val(e.format('yyyy-mm-dd'));
            });

        $("#submit").on("click",function () {
            let date1 = $("#date1").val();
            let date2 = $("#date2").val();
            $("#datatables").DataTable().destroy();
            loadData(date1, date2);
        })
        
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
                    //$("#form-edit select.account_no").val(collection.account_no).trigger("change");
                     $("#form-edit input[name='account_no']").val(collection.account_no);
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
    </script>

@endsection


