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
                    <h4 class="page-title">FDR জমা</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                            FDR জমা
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $dailySavings = \App\Models\Fdr::where('status','active')->get();
                        @endphp
                        <form class="needs-validation" >
                            @csrf
                            @method('PATCH')
                            <div class="row g-3">
                                <input type="hidden" name="id" value="{{ $deposit->id }}">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="account_no">হিসাব নং</label>
                                    <select class="form-control select2" id="account_no" name="account_no" data-allow-clear="on" data-placeholder="Select" data-toggle="select2" required >
                                        <option value="">Select</option>
                                        @foreach($dailySavings as $item)
                                            <option value="{{ $item->account_no }}" {{ $item->account_no==$deposit->fdr->account_no?"selected":"" }}>{{ $item->account_no }} - {{ $item->member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="amount" class="form-label">FDR জমা</label>
                                    <input type="number" name="amount" id="amount" class="form-control" value="{{ $deposit->amount }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="profit_rate"> মুনাফার হার(%) </label>
                                    <input id="profit_rate" name="profit_rate" type="number" class="form-control" required value="{{ $deposit->profit_rate }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="date"> তারিখ </label>
                                    <input id="date" name="date" type="date" class="form-control" value="{{ $deposit->date }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end mb-2">
                                    <button class="btn btn-success w-100" type="button" id="btn-submit">সাবমিট</button>
                                </div>
                            </div>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->

        </div>
        <!-- end row -->

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
                url: "{{ url('getDetails') }}/"+id,
                dataType: "json",
                success: function (data) {
                    $(".table-details tbody").append(`
<tr>
<th>হিসাব নং</th>    <td class="text-end">${data.account_no}</td>
</tr>
                    <tr>
<th>নাম</th>    <td class="text-end">${data.member.name}</td>
</tr>
                    `);
                    if (data.deposit>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>জমা</th>    <td class="text-end">${data.deposit} টাকা</td>
</tr>
                    `);
                    }
                    if (data.withdraw>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>উত্তোলন</th>    <td class="text-end">${data.withdraw} টাকা</td>
</tr>
                    `);
                    }
                    if (data.loan_installment>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>ঋণ ফেরত</th>    <td class="text-end">${data.loan_installment} টাকা</td>
</tr>
<tr>
<th>অবশিষ্ট ঋণ</th>    <td class="text-end">${data.loan_balance} টাকা</td>
</tr>
                    `);
                    }
                    if (data.late_fee>0)
                    {
                        $(".table-details tbody").append(`
<tr>
<th>বিলম্ব ফি</th>    <td class="text-end">${data.late_fee} টাকা</td>
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

        $("#btn-submit").on("click",function () {
            var id = $("input[name='id']").val();
            var $this = $("#btn-submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("form").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ url('fdr-deposits') }}/"+id,
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
<td><b> হিসাব নংঃ </b></td>  <td class="text-danger fw-bolder">${data.account_no}</td>
</tr>
                    <tr>
<td><b> নামঃ </b></td>  <td>${data.member.name}</td>
</tr>
<tr>
<td><b> মোবাইলঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>
<tr>
<td><b> পিতার নামঃ </b></td>  <td>${data.member.father_name}</td>
</tr>
<tr>
<td><b> এন আইডিঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>                    `);

                    $('.details').append(`
                    <tr>
<td><b> FDR ব্যালেন্সঃ </b></td>  <td>${data.fdr_balance} টাকা</td>
</tr>
<tr>
<td><b> মুনাফা উত্তোলনঃ </b></td>  <td>${data.profit} টাকা</td>
</tr>
<tr>
<td><b> মুনাফা পাবেঃ </b></td>  <td>${data.due_profit} টাকা</td>
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
        function loadData()
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataDeposits') }}"
                },
                columns: [
                    // columns according to JSON

                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'date' },
                    { data: 'fdr_amount' },
                    { data: 'fdr_balance' },
                    { data: 'profit_rate' },
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


