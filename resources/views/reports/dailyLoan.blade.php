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
                    <h4 class="page-title">দৈনিক ঋণ ফেরত - রিপোর্ট</h4>
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
                                <th>ঋণ ফেরত</th>
                                <th>লভ্যাংশ আদায়</th>
                                <th>বিলম্ব ফি</th>
                                <th>অবশিষ্ট ঋণ</th>
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
                                <input type="text" name="account_no" value="" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="date"> তারিখ </label>
                                    <input name="date" type="date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                           
                            <div class="col-md-6">
                                <div class="row g-1">
                                    <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">ঋণ ফেরত</label>
                                        <input type="number" name="loan_installment" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="late_fee" class="form-label">বিলম্ব ফি </label>
                                        <input type="number" name="late_fee" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label">মন্তব্য</label>
                                <input type="text" name="notes" class="form-control">
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
    $(document).on("click",".edit",function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('getDetails') }}/"+id,
                dataType: "json",
                success: function (data) {
                    //console.log(data);
                    $("#form-edit input[name='id']").val(data.id);
                    $("#form-edit input[name='account_no']").val(data.account_no);
                    $("#form-edit input[name='date']").val(data.date);
                    $("#form-edit input[name='deposit']").val(data.deposit);
                    $("#form-edit input[name='withdraw']").val(data.withdraw);
                    $("#form-edit input[name='loan_installment']").val(data.loan_installment);
                    $("#form-edit input[name='late_fee']").val(data.late_fee);
                    $("#form-edit input[name='notes']").val(data.notes);
                }
            })
            $("#modalEdit").modal("show");
        })
        $("#btn-update").on("click",function () {
            var $this = $("#btn-update"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#form-edit").serializeArray();
            var id = $("#form-edit input[name='id']").val();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ url('daily-collections') }}/"+id,
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
                        $("#modalEdit").modal('hide');

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
            var formData = $("form").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('daily-collections.store') }}",
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
                url: "{{ url('get-savings') }}/"+id,
                dataType: "json",
                success: function (data) {

                    if (data.savings.member.photo !="") {
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
<td><b> দৈনিক সঞ্চয় জমাঃ </b></td>  <td>${data.savings.total} টাকা</td>
</tr>
                   `);

                    if (data.loan!="")
                    {
                        $('.details').append(`
                    <tr>
<td><b> মোট ঋণ প্রদানঃ </b></td>  <td>${data.loan.total} টাকা</td>
</tr>
<tr>
<td><b> অবশিষ্ট ঋণঃ</b></td>  <td>${data.loan.balance} টাকা</td>
</tr>
<tr>
<td><b> বকেয়া কিস্তিঃ</b></td>  <td>${data.dueInstallment} টি</td>
</tr>
                   `);

                        if (data.dueInstallment>0)
                        {
                            $('.details').append(`
<tr>
<td><b> কিস্তির পরিমানঃ</b></td>  <td>${data.loan.per_installment} টাকা</td>
</tr>
                   `);
                        }
                    }


                    $('.btn-details').append(`
                  <a class="btn btn-sm bg-warning" href="{{ url('daily-savings')}}/${data.savings.id}">বিস্তারিত দেখুন</a></td>
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
        function loadData(date1="", date2="")
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dailyLoanCollectionsData') }}",
                    data: { date1:date1,date2:date2},
                },
                columns: [
                    { data: 'name' },
                    { data: 'account_no' },
                    { data: 'loan_installment' },
                    { data: 'interest' },
                    { data: 'late_fee' },
                    { data: 'loan_balance' },
                    { data: 'date' },
                    { data: 'action' },
                ],
                // Buttons with Dropdown
                buttons: ["copy", "print"],

            });
        }

        $("#submit").on("click",function () {
            let date1 = $("#date1").val();
            let date2 = $("#date2").val();
            $("#datatables").DataTable().destroy();
            loadData(date1,date2);

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
                        url: "{{url('daily-collections')}}/" + id,
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
    <script>

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
    </script>
@endsection


