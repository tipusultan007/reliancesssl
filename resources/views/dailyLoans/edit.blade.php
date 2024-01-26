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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Form Wizard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-warning pb-1">
                        <h4 class="card-title text-black">ঋণ আবেদন পত্র সম্পাদন</h4>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" method="POST" action="{{ route('daily-loans.update',$loan->id) }}" novalidate="" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <div class="col-xl-12">
                                    <h4 class="header-title mb-2">ঋণ বিবরণী</h4>
                                    <div class="row">
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="userName">হিসাব নং</label>
                                            <select name="savings_id" id="account_no" class="form-control form-control-sm select2" data-allow-clear="on" data-placeholder="Select" data-toggle="select2">
                                                <option value="">Select</option>
                                                @foreach($savings as $item)
                                                    <option value="{{ $item->id }}" {{ $loan->account_no==$item->account_no?"selected": "" }}>{{ $item->account_no }} - {{ $item->member->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="loan_amount">ঋণের পরিমাণ </label>
                                            <input type="number" class="form-control form-control-sm" id="loan_amount" name="loan_amount" value="{{ $loan->loan_amount }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="interest_rate">লভ্যাশের হার </label>
                                            <div class="position-relative">
                                                <input type="number" class="form-control form-control-sm" id="interest_rate" name="interest_rate" value="{{ $loan->interest_rate }}">
                                                <input type="hidden" name="interest" id="interest" value="{{ $loan->interest }}">
{{--                                                <input type="hidden" name="total" id="total" value="{{ $loan->total }}">--}}
{{--                                                <input type="hidden" name="balance" id="balance" value="{{ $loan->balance }}">--}}
                                            </div>
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="per_installment">কিস্তির পরিমাণ </label>
                                            <input type="number" class="form-control form-control-sm" id="per_installment" name="per_installment" value="{{ $loan->per_installment }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label-sm">সর্বমোট ঋণ</label>
                                            <input type="number" name="total" id="total" class="form-control" value="{{ $loan->total }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label-sm">অবশিষ্ট ঋণ</label>
                                            <input type="number" name="balance" id="balance" class="form-control" value="{{ $loan->balance }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label-sm">সর্বমোট ঋণ ফেরত</label>
                                            <input type="number" name="paid_loan" id="paid_loan" class="form-control" value="{{ $loan->paid_loan }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="form-label-sm">সর্বমোট লভ্যাংশ আদায়</label>
                                            <input type="number" name="paid_interest" id="paid_interest" class="form-control" value="{{ $loan->paid_interest }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="date">ঋণ প্রদানের তারিখ  </label>
                                                <input id="date" name="date" type="date" class="form-control" value="{{ $loan->date }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label class="form-label-sm" for="notes">মন্তব্য  </label>
                                            <input type="text" class="form-control form-control-sm" id="notes" name="notes" value="{{ $loan->notes }}">
                                        </div>
                                    </div>
                                    @php
                                        $document = \App\Models\LoanDocument::where('loan_id',$loan->id)->where('loan_type','daily')->first();
                                    @endphp
                                    <h4 class="header-title mb-2"> ঋণের ডকুমেন্টস</h4>
                                    <div class="row mb-2">
                                        <div class="col-xl-6 mb-2">
                                            <label for="bank_name" class="form-label-sm">ব্যাংকের নাম</label>
                                            <input type="text" class="form-control form-control-sm" name="bank_name" id="bank_name" value="{{ $document->bank_name??"" }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="branch_name" class="form-label-sm">শাখার নাম</label>
                                            <input type="text" class="form-control form-control-sm" name="branch_name" id="branch_name" value="{{ $document->branch_name??"" }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="account_holder" class="form-label-sm">একাউন্ট হোল্ডার নাম</label>
                                            <input type="text" class="form-control form-control-sm" name="account_holder" id="account_holder" value="{{ $document->account_holder??"" }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="bank_ac_number" class="form-label-sm">ব্যাংকের হিসাব নম্বর</label>
                                            <input type="text" class="form-control form-control-sm" name="bank_ac_number" id="bank_ac_number" value="{{ $document->bank_ac_number??"" }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="cheque_number" class="form-label-sm">চেক নম্বর</label>
                                            <input type="number" class="form-control form-control-sm" name="cheque_number" id="cheque_number" value="{{ $document->cheque_number??"" }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="documents" class="form-label-sm">ডকুমেন্টস</label>
                                            <input type="file" class="form-control form-control-sm" name="documents" id="documents">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xl-12">
                                    @php
                                        $guarantor = \App\Models\Guarantor::where('loan_id',$loan->id)->where('loan_type','daily')->first();
                                    @endphp
                                    <h4> জামিনদারের তথ্য</h4>
                                    <div class="row mb-2">
                                        <h4 class="header-title mb-2">জামিনদার - ০১</h4>
                                        <div class="col-md-12 mb-2">
                                            <select name="g_member_id" id="g_member_id" class="form-control form-control-sm select2" data-allow-clear="on" data-placeholder="Select" data-toggle="select2">
                                                <option value="">Select</option>
                                                @foreach($guarantorList as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id==$guarantor->g_member_id?"selected":"" }}>{{ $item->name }} - {{ $item->father_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--<div class="col-xl-6 mb-2">
                                            <label for="name" class="form-label-sm">নাম</label>
                                            <input type="text" class="form-control form-control-sm" id="name" name="name">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="address" class="form-label-sm">ঠিকানা</label>
                                            <input type="text" class="form-control form-control-sm" id="address" name="address">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_account_no" class="form-label-sm">হিসাব নং</label>
                                            <input type="text" class="form-control form-control-sm" id="g_account_no" name="g_account_no">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="photo" class="form-label-sm">জামিনদারের ছবি</label>
                                            <input type="file" class="form-control form-control-sm" id="photo" name="photo">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="signature" class="form-label-sm"> স্বাক্ষর </label>
                                            <input type="file" class="form-control form-control-sm" id="signature" name="signature">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_documents" class="form-label-sm"> ডকুমেন্টস </label>
                                            <input type="file" class="form-control form-control-sm" id="g_documents" name="g_documents">
                                        </div>--}}
                                    </div>
                                    <div class="row mb-2">
                                        <h4 class="header-title mb-2">জামিনদার - ০২</h4>
                                        <div class="col-xl-6 mb-2">
                                            <label for="name1" class="form-label-sm">নাম</label>
                                            <input type="text" class="form-control form-control-sm" id="name1" name="name1" value="{{ $guarantor->name1 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="address1" class="form-label-sm">ঠিকানা</label>
                                            <input type="text" class="form-control form-control-sm" id="address1" name="address1" value="{{ $guarantor->address1 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_mobile1" class="form-label-sm">মোবাইল নং</label>
                                            <input type="text" class="form-control form-control-sm" id="g_mobile1" name="g_mobile1" value="{{ $guarantor->g_mobile1 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="photo1" class="form-label-sm">জামিনদারের ছবি</label>
                                            <input type="file" class="form-control form-control-sm" id="photo1" name="photo1">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="signature1" class="form-label-sm"> স্বাক্ষর </label>
                                            <input type="file" class="form-control form-control-sm" id="signature1" name="signature1">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_documents1" class="form-label-sm"> ডকুমেন্টস </label>
                                            <input type="file" class="form-control form-control-sm" id="g_documents1" name="g_documents1">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <h4 class="header-title mb-2">জামিনদার - ০৩</h4>
                                        <div class="col-xl-6 mb-2">
                                            <label for="name2" class="form-label-sm">নাম</label>
                                            <input type="text" class="form-control form-control-sm" id="name2" name="name2" value="{{ $guarantor->name2 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="address2" class="form-label-sm">ঠিকানা</label>
                                            <input type="text" class="form-control form-control-sm" id="address2" name="address2" value="{{ $guarantor->address2 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_mobile2" class="form-label-sm">মোবাইল নং</label>
                                            <input type="text" class="form-control form-control-sm" id="g_mobile2" name="g_mobile2" value="{{ $guarantor->g_mobile2 }}">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="photo2" class="form-label-sm">জামিনদারের ছবি</label>
                                            <input type="file" class="form-control form-control-sm" id="photo2" name="photo2">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="signature2" class="form-label-sm"> স্বাক্ষর </label>
                                            <input type="file" class="form-control form-control-sm" id="signature2" name="signature2">
                                        </div>
                                        <div class="col-xl-6 mb-2">
                                            <label for="g_documents2" class="form-label-sm"> ডকুমেন্টস </label>
                                            <input type="file" class="form-control form-control-sm" id="g_documents2" name="g_documents2">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <button class="btn btn-primary" type="submit" id="btn-submit">সাবমিট করুন</button>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-white text-center">সদস্য তথ্য</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center avatar">

                        </p>
                        <table class="table table-sm details">

                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-white text-center">জামিনদারের তথ্য</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center gavatar">

                        </p>
                        <table class="table table-sm gdetails">

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
    <script src="{{asset('assets/js/pages/demo.toastr.js')}}"></script>
    <!-- Datatable Demo Aapp js -->
    <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>
    <script>
        $(document).ready(function () {
            function calculateInstallment(principal,interest_rate) {
                let interest = parseFloat(principal) * parseFloat(interest_rate/100);
                let total = parseFloat(principal) + parseFloat(interest);
                let per_installment = parseFloat(total)/317;

                $("#per_installment").val(per_installment.toFixed(3));
                $("#interest").val(interest);
                $("#total").val(total);
                $("#balance").val(total);
                /*console.log('Interest -'+interest);
                console.log('Total -'+total);
                console.log('Per Installment -'+per_installment);*/
            }

            $("#loan_amount").on("input",function () {
                let interest_rate = $("#interest_rate").val();
                let  principal = $(this).val();
                calculateInstallment(principal,interest_rate);
            })
            $("#interest_rate").on("input",function () {
                let principal = $("#loan_amount").val();
                let interest_rate = $(this).val();
                calculateInstallment(principal,interest_rate);
            })
            $('.date')
                .datepicker({ format: 'dd/mm/yyyy' })
                .on('changeDate', function(e){
                    $('#date').val(e.format('yyyy-mm-dd'));
                });

            $("#account_no").on("select2:select",function (e) {
                let id = e.params.data.id;
                let path = "{{ asset('uploads') }}";
                $(".details").empty();
                $(".avatar").empty();
                $.ajax({
                    url: "{{ url('savingsDetails') }}/"+id,
                    dataType: "json",
                    success: function (data) {

                        if (data.member.photo !="") {
                            $(".avatar").append(`
                    <img src="${path}/${data.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                        }
                        $(".avatar").append(`
                    <p class="text-center mt-1"><b>হিসাব নংঃ </b>${data.account_no}</p>
                    `);
                        $('.details').append(`
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

                        if (data.daily_savings>0)
                        {
                            $('.details').append(`
                    <tr>
<td><b> দৈনিক সঞ্চয়ঃ </b></td>  <td>${data.daily_savings}</td>
</tr>
                   `);
                        }

                        if (data.daily_loans>0)
                        {
                            $('.details').append(`
                    <tr>
<td><b> দৈনিক ঋনঃ </b></td>  <td>${data.daily_loans}</td>
</tr>
                   `);
                        }
                        if (data.guarantor_loan>0)
                        {
                            $('.details').append(`
                    <tr>
<td><b> জামিনদার ঋনঃ </b></td>  <td>${data.guarantor_loan}</td>
</tr>
                   `);
                        }
                    }
                })
            })

            /* $("#btn-submit").on("click",function () {
                 var $this = $("#btn-submit"); //submit button selector using ID
                 var $caption = $this.html();// We store the html content of the submit button
                 const formData = new FormData();
                 var x = $("form").serializeArray();
                 $.each(x, function(i, field){
                     formData.append(field.name, field.value);
                 });
                 formData.append('documents',$("#documents")[0].files[0]);
                 //formData.append('photo',$("#photo")[0].files[0]);
                 //formData.append('signature',$("#signature")[0].files[0]);
                 //formData.append('g_documents',$("#g_documents")[0].files[0]);
                 formData.append('photo1',$("#photo1")[0].files[0]);
                 formData.append('signature1',$("#signature1")[0].files[0]);
                 formData.append('g_documents1',$("#g_documents1")[0].files[0]);
                 formData.append('photo2',$("#photo2")[0].files[0]);
                 formData.append('signature2',$("#signature2")[0].files[0]);
                 formData.append('g_documents2',$("#g_documents2")[0].files[0]);

                 $.ajax({
                     method: 'POST',
                     processData: false,
                     contentType: false,
                     cache: false,
                     data: formData,
                     enctype: 'multipart/form-data',
                     url: "{{ route('daily-loans.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                    if (data.error)
                    {
                        $.NotificationApp.send("Error",data.error,"bottom-right","rgba(0,0,0,0.2)","error")
                    }else {
                        $.NotificationApp.send("Success",data.success,"bottom-right","rgba(0,0,0,0.2)","success")
                        //window.location.href = "{{ url('monthly-loans') }}";
                        $("form").trigger('reset');
                        $('.details').empty();
                        $("#account_no").val("").trigger("change");
                        $(".avatar").empty();
                    }
                    //console.log(data)
                    //$(".spinner").hide();

                    // window.location.href = "{{ url('monthly-savings') }}"
                    //$.NotificationApp.send("Message","Data submission success","bottom-right","rgba(0,0,0,0.2)","success")
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error","Data submission failed","rgba(0,0,0,0.2)","error")
                }
            })
        })*/
            $("#g_member_id").on("select2:select",function (e) {
                let id = e.params.data.id;
                let path = "{{ asset('uploads') }}";
                $(".gdetails").empty();
                $(".gavatar").empty();
                $.ajax({
                    url: "{{ url('guarantorDetails') }}/"+id,
                    dataType: "json",
                    success: function (data) {

                        if (data.member.photo !="") {
                            $(".gavatar").append(`
                    <img src="${path}/${data.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                        }
                        $(".gavatar").append(`
                    <p class="text-center mt-1"><b>হিসাব নংঃ </b>${data.account_no}</p>
                    `);
                        $('.gdetails').append(`
                    <tr>
<td><b> নামঃ </b></td>  <td>${data.member.name}</td>
</tr>
<tr>
<td><b> মোবাইলঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>                  `);

                        if (data.total_monthly_loans>0)
                        {
                            $('.gdetails').append(`
                    <tr>
<td><b> মাসিক ঋনঃ </b></td>  <td>${data.total_monthly_loans}</td>
</tr>
                   `);
                        }

                        if (data.total_daily_loans>0)
                        {
                            $('.gdetails').append(`
                    <tr>
<td><b> দৈনিক ঋনঃ </b></td>  <td>${data.total_daily_loans}</td>
</tr>
                   `);
                        }
                        if (data.guarantor_loan>0)
                        {
                            $('.gdetails').append(`
                    <tr>
<td><b> জামিনদার ঋনঃ </b></td>  <td>${data.guarantor_loan}</td>
</tr>
                   `);
                        }
                    }
                })
            })
            $("#account_no").on("select2:clear",function (){
                $(".details").empty();
                $(".avatar").empty();
            })
            $("#g_member_id").on("select2:clear",function (){
                $(".gdetails").empty();
                $(".gavatar").empty();
            })
        })
    </script>
@endsection


