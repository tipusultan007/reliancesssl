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

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"> ড্যাশবোর্ড</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Form Wizard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">FDR মুনাফা উত্তোলন</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                           FDR / মুনাফা উত্তোলন
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $dailySavings = \App\Models\Fdr::where('status','active')->get();
                        @endphp
                        <form id="profitForm" class="needs-validation">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="account_no">হিসাব নং</label>
                                    <select class="form-control select2" id="account_no" name="account_no" data-allow-clear="on" data-placeholder="Select" data-toggle="select2" required>
                                        <option value="">Select</option>
                                        @foreach($dailySavings as $item)
                                            <option value="{{ $item->account_no }}">{{ $item->account_no }} - {{ $item->member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="profit" class="form-label">মুনাফা উত্তোলন</label>
                                    <input type="number" name="profit" id="profit" class="form-control" readonly>
                                </div>

                            </div>
                            <div class="row">
                                {{--<div class="col-md-6 mb-2">
                                    <label for="fdr_withdraw" class="form-label">FDR উত্তোলন</label>
                                    <input type="number" name="fdr_withdraw" id="fdr_withdraw" class="form-control">
                                </div>--}}
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="date"> তারিখ </label>
                                    <div class="position-relative" id="datepicker1">
                                        <input id="date" name="date" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                {{--<div class="col-md-3 mb-2">
                                    <label for="profit_installments" class="form-label">কিস্তি সংখ্যা</label>
                                    <input type="number" name="profit_installments" id="profit_installments" class="form-control">
                                </div>--}}
                                <div class="col-md-6">
                                    <label for="notes" class="form-label">মন্তব্য</label>
                                    <input type="text" name="notes" class="form-control" id="notes">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-success w-100" type="button" id="btn-submit">সাবমিট</button>
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
                                <th>মুনাফা উত্তোলন</th>
                                <th>FDR ব্যালেন্স</th>
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

    <!-- Modal -->
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
                    <button type="button" onclick="printDiv()" class="btn btn-primary">প্রিন্ট করুন</button>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
    <!-- Modal -->
    <div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">বিবরণী</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="dataPreview">
                        <table class="table table-bordered table-sm table-preview">
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="printDiv()" class="btn btn-primary">প্রিন্ট করুন</button>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">FDR মুনাফা</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fdrProfitEditForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <div class="col-md-6">
                                <label for="" class="form-label">হিসাব নং</label>
                                <select class="form-control select2" id="edit_account_no" name="account_no" data-allow-clear="on" data-placeholder="Select" data-toggle="select2" required>
                                    <option value="">Select</option>
                                    @foreach($dailySavings as $item)
                                        <option value="{{ $item->account_no }}">{{ $item->account_no }} - {{ $item->member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">উত্তোলন পরিমাণ</label>
                                <input type="number" class="form-control profit" name="profit">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="" class="form-label">তারিখ</label>
                                <input type="date" class="form-control date" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button"  class="btn btn-primary btn-update">আপডেট</button>
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
        $("#btn-preview").on("click",function () {
            let total = 0;
            let deposit = $("#deposit").val();
            let withdraw = $("#withdraw").val();
            let loan_installment = $("#loan_installment").val();
            let late_fee = $("#late_fee").val();
            if (deposit>0)
            {
                total += parseInt(deposit);
            }

            if (loan_installment>0)
            {
                total += parseInt(loan_installment);
            }
            if (late_fee>0)
            {
                total += parseInt(late_fee);
            }


        })
        $(document).on("click",".view",function () {
            let id = $(this).data('id');
            $(".table-details tbody").empty();
            $.ajax({
                url: "{{ url('getFdrProfitDetails') }}/"+id,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $(".table-details tbody").append(`
<tr>
<th>হিসাব নং</th>    <td class="text-end">${data.account_no}</td>
</tr>
                    <tr>
<th>নাম</th>    <td class="text-end">${data.member.name}</td>
</tr>
                    `);

                    if (data.profit>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>মুনাফা উত্তোলন</th>    <td class="text-end">${data.profit} টাকা</td>
</tr>
                    `);
                    }

                    $(".table-details tbody").append(`
<tr>
<th>তারিখ</th>    <td class="text-end">${dateFormat(data.date, 'dd/MM/yyyy')}</td>
</tr>
                    `);
                }
            })
            $("#modalView").modal("show");
        })
        $('.date')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#date').val(e.format('yyyy-mm-dd'));
            });

        $('.birth_date1')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#birth_date1').val(e.format('yyyy-mm-dd'));
            });
        $('.birth_date2')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#birth_date2').val(e.format('yyyy-mm-dd'));
            });
        $("#btn-submit").on("click",function () {
            var $this = $("#btn-submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#profitForm").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('profit-collections.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    console.log(data);
                    $this.attr('disabled', false).html($caption);
                    //$(".spinner").hide();
                    $("#account_no").val("").trigger('change');
                    if (data == "empty")
                    {
                        $.NotificationApp.send("Error","Data submission failed","bottom-right","rgba(0,0,0,0.2)","error")
                    }else {
                        $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                        $("#deposit").val("");
                        $("#withdraw").val("");
                        $("#loan_installment").val("");
                        $("#late_fee").val("");
                        $("#notes").val("");
                        $(".details").empty();
                        $(".btn-details").empty();
                        $(".avatar").empty();
                        $("#profit").val('0');
                        $("#datatables").DataTable().destroy();
                        loadData();
                    }
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#account_no").val("").trigger('change');
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error","Data submission failed","bottom-right","rgba(0,0,0,0.2)","error")
                }
            })
        })

        $("#account_no").on("select2:select",function (e) {
            let id = e.params.data.id;
            let path = "{{ asset('uploads') }}";
            $(".details").empty();
            $(".btn-details").empty();
            $(".avatar").empty();
            $(".fdr-deposit").remove();
            $("#profit").val('0');
            $.ajax({
                url: "{{ url('get-fdr') }}/"+id,
                dataType: "json",
                success: function (data) {

                    console.log(data.profit);
                    var total = 0;
                    if (data.fdr.member.photo !="") {
                        $(".avatar").append(`
                    <img src="${path}/${data.fdr.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                    }
                    $('.details').append(`
<tr>
<td><b> হিসাব নংঃ </b></td>  <td class="text-danger fw-bolder">${data.fdr.account_no}</td>
</tr>
                    <tr>
<td><b> নামঃ </b></td>  <td>${data.fdr.member.name}</td>
</tr>
<tr>
<td><b> মোবাইলঃ </b></td>  <td>${data.fdr.member.nid_no}</td>
</tr>
<tr>
<td><b> পিতার নামঃ </b></td>  <td>${data.fdr.member.father_name}</td>
</tr>
<tr>
<td><b> এন আইডিঃ </b></td>  <td>${data.fdr.member.nid_no}</td>
</tr>                    `);

                    $('.details').append(`
                    <tr>
<td><b> FDR ব্যালেন্সঃ </b></td>  <td>${data.fdr.fdr_balance} টাকা</td>
</tr>
<tr>
<td><b> মুনাফা উত্তোলনঃ </b></td>  <td>${data.fdr.profit} টাকা</td>
</tr>

                   `);



                    $.each(data.profit,function (a,b){
                        total += b.profit;
                    });

                    $("#profit").val(total);

                    $('.btn-details').append(`
                  <a class="btn btn-sm bg-info text-white" href="{{ url('fdr')}}/${data.id}">বিস্তারিত দেখুন</a></td>
                    `);

                }
            })
        })


        var checkForm = function(form) { /* Submit button was clicked */

            console.log(hi);
            form.myButton.disabled = true;
            form.myButton.value = "Please wait...";
            return true;
        };

        var resetForm = function(form) { /* Reset button was clicked */
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
                    "url": "{{ url('dataProfitCollections') }}"
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'profit' },
                    { data: 'fdr_balance' },
                    { data: 'date' },
                    { data: 'action' },
                ],
                // Buttons with Dropdown
                buttons: ["copy", "print"],

            });
        }



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
                        url: "{{url('profit-collections')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
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
    <script>
        function printDiv() {
            $("#modalView").modal("hide");
            var printContent = document.getElementById("printDetails").innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection


