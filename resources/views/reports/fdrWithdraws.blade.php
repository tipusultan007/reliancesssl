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

    @php
        $dailySavings = \App\Models\Fdr::all();
 @endphp
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
                    <h4 class="page-title">FDR জমা রিপোর্ট</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- end row -->
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
                        <table id="datatables" class="table table-sm dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>নাম </th>
                                <th>হিসাব নং</th>
                                <th>তারিখ</th>
                                <th>উত্তোলন পরিমাণ</th>
                                <th>ব্যালেন্স</th>
                                <th>অ্যাকশন</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- container -->
    <!-- Modal -->
    <div class="modal fade" id="modalFdrDeposit" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">FDR জমা</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fdrDepositEditForm">
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
                                <label for="" class="form-label">জমা</label>
                                <input type="number" class="form-control fdr-amount" name="amount">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="" class="form-label">মুনাফার হার(%)</label>
                                <input type="number" class="form-control fdr-profit-rate" name="profit_rate">
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

        $(document).on("click",".view",function () {
            let id = $(this).data('id');
            $(".table-details tbody").empty();
            $.ajax({
                url: "{{ url('getFdrWithdrawDetails') }}/"+id,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $(".table-details tbody").append(`
<tr>
<th>হিসাব নং</th>    <td class="text-end">${data.fdr.account_no}</td>
</tr>
                    <tr>
<th>নাম</th>    <td class="text-end">${data.member.name}</td>
</tr>
                    `);

                    $(".table-details tbody").append(`
<tr>
<th>উত্তোলন</th>    <td class="text-end">${data.amount} টাকা</td>
</tr>
                    `);

                    $(".table-details tbody").append(`
<tr>
<th>তারিখ</th>    <td class="text-end">${dateFormat(data.date, 'dd/MM/yyyy')}</td>
</tr>
                    `);

                    $(".print").prop("href","{{ url('print/fdr-withdraw') }}/"+data.id)
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

        $("#btn-submit").on("click",function () {
            var $this = $("#btn-submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#fdrWithdrawForm").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('fdr-withdraws.store') }}",
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
                        $("#amount").val("");
                        $(".details").empty();
                        $(".btn-details").empty();
                        $(".avatar").empty();

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
            $.ajax({
                url: "{{ url('get-fdr') }}/"+id,
                dataType: "json",
                success: function (data) {

                    if (data.member.photo !="") {
                        $(".avatar").append(`
                    <img src="${path}/${data.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                    }
                    $('.details').append(`
<tr>
<td><b> হিসাব নংঃ </b></td>  <td class="text-danger fw-bolder">${data.fdr.account_no}</td>
</tr>
                    <tr>
<td><b> নামঃ </b></td>  <td>${data.member.name}</td>
</tr>
<tr>
<td><b> মোবাইলঃ </b></td>  <td>${data.member.phone}</td>
</tr>
<tr>
<td><b> পিতার নামঃ </b></td>  <td>${data.member.father_name}</td>
</tr>
<tr>
<td><b> এন আইডিঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>                    `);

                    $('.details').append(`
                    <tr>
<td><b> FDR ব্যালেন্সঃ </b></td>  <td>${data.fdr.fdr_balance} টাকা</td>
</tr>
                   `);


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
        function loadData(date1='',date2='')
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataWithdraws') }}",
                    data:{date1: date1,date2: date2}
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'date' },
                    { data: 'fdr_amount' },
                    { data: 'fdr_balance' },
                    { data: 'action' },
                ],
                buttons: [
                    {
                        extend: 'print',
                        title: '',
                        customize: function(win) {
                            // Add custom header content to the print view
                            $(win.document.body).prepend('' +
                                '<div class="text-center"><h2>রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড</h2>'+
                                '<h4>১ নং সাইড হিন্দু পাড়া, বন্দর, চট্টগ্রাম</h4>'+
                                '<h3><u>FDR উত্তোলন তালিকা</u></h3></div> <hr>'
                            );
                        },
                        exportOptions: {
                            columns: [ 0,1,2,3,4 ]
                        },
                    }
                ],
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
                        url: "{{url('fdr-withdraws')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.status === 'success') {
                                $("#datatables").DataTable().destroy();
                                loadData();
                                $.NotificationApp.send("Success", "Successfully deleted", "bottom-right", "rgba(0,0,0,0.2)", "success")
                            } else {
                                $.NotificationApp.send("Error", "Deletion Failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
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
            $("#modalFdrDeposit").modal("show");
            var account_no = $(this).data("account_no");
            var amount = $(this).data("amount");
            var date = $(this).data("date");
            var id = $(this).data("id");
            $("#fdrDepositEditForm #edit_account_no").val(account_no).trigger("change");
            $("#fdrDepositEditForm .fdr-amount").val(amount);
            $("#fdrDepositEditForm .date").val(date);
            $("#fdrDepositEditForm input[name='id']").val(id);
            $("#fdrDepositEditForm .fdr-profit-rate").val(rate);
        })

        $(document).on("click",'.btn-update',function () {
            var id = $("#fdrDepositEditForm input[name='id']").val();
            var $this = $(".btn-update"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#fdrDepositEditForm").serializeArray();
            $.ajax({
                type: 'PUT',
                url: "{{url('fdr-withdraws')}}/" + id,
                data: formData,
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $("#modalFdrDeposit").modal("hide");
                    $this.attr('disabled', false).html($caption);
                    if (data.message === "success") {
                        $("#datatables").DataTable().destroy();
                        loadData();
                        $.NotificationApp.send("Success", "success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                    } else {
                        $.NotificationApp.send("Error", "error", "bottom-right", "rgba(0,0,0,0.2)", "error")
                    }
                }
            });
        })

        $("#submit").on("click",function () {
            let date1 = $("#date1").val();
            let date2 = $("#date2").val();
            $("#datatables").DataTable().destroy();
            loadData(date1, date2);
        })
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


