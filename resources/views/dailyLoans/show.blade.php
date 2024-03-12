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
    <link rel="stylesheet" href="{{ asset('assets/css/ekko-lightbox.css') }}" type="text/css"/>
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ড্যাশবোর্ড</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">দৈনিক সঞ্চয়</a></li>
                            <li class="breadcrumb-item active">দৈনিক ঋণ</li>
                        </ol>
                    </div>
                    <h4 class="page-title">দৈনিক ঋণ</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-sm-12">
                <!-- Profile -->
                <div class="card bg-light">
                    <div class="card-body profile-user-box">
                        <div class="row">
                            <div class="col-2">
                                <div class="avatar-lg mx-auto">
                                    <img src="{{ asset('uploads') }}/{{ $loan->member->photo }}" alt="" class="rounded-circle img-thumbnail">
                                </div>
                                <a class="btn btn-success my-2 w-100" href="{{ route('daily-loans.edit',$loan->id) }}">এডিট</a>
                                <button class="btn btn-primary my-2 w-100" data-bs-toggle="modal" data-bs-target="#statusModal">স্ট্যাটাস</button>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-sm table-light w-100 table-bordered">
                                    <tr><th>নাম</th><td><a target="_blank" href="{{ route('members.show',$loan->member_id) }}">{{ $loan->member->name }}</a></td></tr>
                                    <tr><th>মোবাইল</th><td>{{ $loan->member->phone??'-' }}</td></tr>
                                    <tr><th>হিসাব নং</th><td>{{ $loan->account_no }}</td></tr>
                                    <tr><th>ঋণ'র তারিখ</th> <td>{{ date('d/m/Y',strtotime($loan->date)) }}</td></tr>
                                </table>
                            </div>

                          <div class="col-md-3">
                              <table class="table table-sm table-bordered table-light">
                                  <tr><th>ঋণের পরিমাণ</th> <td>{{ $loan->loan_amount }}</td></tr>
                                  <tr><th>সুদের পরিমাণ</th> <td>{{$loan->interest}}({{$loan->interest_rate}}%) টাকা</td></tr>
                                  <tr><th>লভ্যাংশ আদায়</th> <td>{{$loan->paid_interest}}</td></tr>
                                  <tr><th>অবশিষ্ট ঋণ</th> <td>{{$loan->balance}}</td></tr>
                              </table>
                          </div>
                            <div class="col-md-3">
                                <table class="table table-sm table-bordered table-light">
                                    <tr><th>ঋণ প্রদানের তারিখ</th> <td>{{ date('d/m/Y',strtotime($loan->date)) }}</td></tr>
                                    <tr><th>ঋণের পরিমাণ</th> <td>{{ $loan->loan_amount }}</td></tr>
                                    <tr><th>সুদের পরিমাণ</th> <td>{{$loan->interest}}({{$loan->interest_rate}}%) টাকা</td></tr>
                                    <tr><th>সর্বমোট</th> <td>{{$loan->total}}</td></tr>
                                </table>
                            </div>
                        </div> <!-- end row -->

                    </div> <!-- end card-body/ profile-user-box-->
                </div><!--end profile/ card -->
            </div> <!-- end col-->
        </div>
        @php
            $guarantor = \App\Models\Guarantor::where('loan_id',$loan->id)->where('loan_type','daily')->first();
            //$documents = \App\Models\Loa::where('loan_id',$loan->id)->where('loan_type','monthly')->first();
        @endphp
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary py-0 text-white text-center">
                        <h5>জামিনদার ০১</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th> নাম </th> <td>{{ $guarantor->member->name??'' }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->member->name??'' }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->member->present_address??'' }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>
                                    @if($guarantor->g_member_id)
                                        @if($guarantor->member->photo)
                                            <a data-lightbox="photo" href="{{ asset('storage/'.$guarantor->member->photo) }}">
                                                ছবি
                                            </a>
                                        @endif
                                        @if($guarantor->member->signature)
                                            ,<a data-lightbox="signature" href="{{ asset('storage/'.$guarantor->member->signature) }}">
                                                স্বাক্ষর
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary py-0 text-white text-center">
                        <h5>জামিনদার ০২</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th> নাম </th> <td>{{ $guarantor->name1 }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->g_mobile1 }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->address1 }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>
                                    @if($guarantor->photo1)
                                        <a data-lightbox="photo1" href="{{ asset('storage/'.$guarantor->photo1) }}">
                                            ছবি
                                        </a>
                                    @endif
                                    @if($guarantor->signature1)
                                        , <a data-lightbox="signature1" href="{{ asset('storage/'.$guarantor->signature1) }}">
                                            স্বাক্ষর
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary py-0 text-white text-center">
                        <h5>জামিনদার ০৩</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th> নাম </th> <td>{{ $guarantor->name2 }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->g_mobile2 }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->address2 }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>
                                    @if($guarantor->photo2)
                                        <a data-lightbox="photo2" href="{{ asset('storage/'.$guarantor->photo2) }}">
                                            ছবি
                                        </a>
                                    @endif
                                    @if($guarantor->signature2)
                                        ,<a data-lightbox="signature2" href="{{ asset('storage/'.$guarantor->signature2) }}">
                                            স্বাক্ষর
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white pb-0">
                        <h4 class="card-title"> ঋণ ফেরত/লভ্যাংশ আদায়</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered loan-trx">
                            <thead>
                            <tr>
                                <th>তারিখ</th>
                                <th> ঋন ফেরত</th>
                                <th> লভ্যাংশ আদায়</th>
                                <th> ঋণ ব্যালেন্স</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            @foreach($loan->loanCollections as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->date)) }}</td>
                                    <td>{{ $item->loan_return }}</td>
                                    <td>{{ $item->interest }}</td>
                                    <td>{{ $item->loan_balance }}</td>
                                    <td>
                                        <a href="javascript:;" data-id="{{ $item->id }}" class="btn btn-primary btn-sm edit ml-2">এডিট</a>
                                        <a href="{{ route('delete.loan.collection',$item->id) }}" class="btn btn-danger btn-sm reject ml-2" onclick="return confirm('Are you sure you want to delete this?')">ডিলেট</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->


        <!--  Modal content for the Large example -->
    <div class="modal fade" id="modalEditNominee" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">নমিনি এডিট</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                    </div> <!-- end modal footer -->
                </form>

            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
    <div class="modal fade" id="modalMemberDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">সদস্যের ব্যক্তিগত তথ্য</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-2">
                        <img class=" img-thumbnail" src="{{ asset('uploads/'.$loan->member->photo) }}" style="height: 100px" alt="">
                    </div>

                    <table class="table table-sm table-striped">
                        <tr>
                            <th>নামঃ</th>
                            <td class="ms-2">{{ $loan->member->name }}</td>
                            <th>পিতার নামঃ</th>
                            <td class="ms-2">{{ $loan->member->father_name }}</td>
                        </tr>
                        <tr>
                            <th>মাতার নামঃ</th>
                            <td class="ms-2">{{ $loan->member->mother_name }}</td>
                            <th>জন্ম তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($loan->member->birth_date)) }}</td>
                        </tr>
                        <tr>
                            <th>লিঙ্গঃ</th>
                            <td class="ms-2"> @if($loan->member->gender=='male')
                                    পুরুষ
                                @else
                                    মহিলা
                                @endif</td>
                            <th>বৈবাহিক অবস্থাঃ</th>
                            <td class="ms-2">@if($loan->member->marital_status=='married')
                                    বিবাহিত
                                @else
                                    অবিবাহিত
                                @endif</td>
                        </tr>
                        <tr>
                            <th>স্বামী/স্ত্রীর নামঃ</th>
                            <td class="ms-2">{{ $loan->member->spouse_name??'' }}</td>
                            <th>মোবাইলঃ</th>
                            <td class="ms-2">{{ $loan->member->phone }}</td>
                        </tr>
                        <tr>
                            <th>বর্তমান ঠিকানাঃ</th>
                            <td class="ms-2">{{ $loan->member->present_address }}</td>
                            <th>স্থায়ী ঠিকানাঃ</th>
                            <td class="ms-2">{{ $loan->member->permanent_address }}</td>
                        </tr>
                        <tr>
                            <th>জাতীয়তাঃ</th>
                            <td class="ms-2">{{ $loan->member->nationality }}</td>
                        </tr>
                        <tr>
                            <th>এন আইডিঃ</th>
                            <td class="ms-2">{{ $loan->member->nid_no }}</td>
                            <th>পেশাঃ</th>
                            <td class="ms-2">{{ $loan->member->occupation }}</td>
                        </tr>
                        <tr>
                            <th>কর্মস্থলঃ</th>
                            <td class="ms-2">{{ $loan->member->workplace }}</td>
                            <th>নিবন্ধন তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($loan->member->join_date)) }}</td>
                        </tr>
                        <tr>
                            <th>এনআইডিঃ </th>
                            <td class="ms-2"><img class=" img-thumbnail" src="{{ asset('uploads/'.$loan->member->nid) }}" style="height: 100px" alt=""></td>
                            <th>স্বাক্ষরঃ</th>
                            <td class="ms-2"><img class=" img-thumbnail" src="{{ asset('uploads/'.$loan->member->signature) }}" style="height: 100px" alt=""></td>
                        </tr>
                    </table>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">স্ট্যাটাস আপডেট</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" action="{{ route('daily.loan.status.update',$loan->id) }}" method="POST" id="form-edit">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $loan->id }}">
                        <div class="form-group">
                            <label for="status" class="form-label">স্ট্যাটাস</label>
                            <select name="status"  class="select2 status form-select">
                                <option value="active" {{ $loan->status === 'active'?'selected':'' }}>চলমান</option>
                                <option value="closed" {{ $loan->status === 'closed'?'selected':'' }}>বন্ধ</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary">আপডেট করুন</button>
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
    <script src="{{asset('assets/js/index.bundle.min.js')}}"></script>
    <script>
        $('.status').select2({
            dropdownParent: $('#statusModal')
        });
        var interest_rate = 0;
        var monthly_amount = 0;

        calculate();
        $(document).on("change","input",function () {
            calculate();
        })
        function calculate() {
            let total_deposited = $(".total_deposited").val();
            let profit = $(".profit").val();
            let bonus = $(".bonus").val();
            let loan_balance = $(".loan_balance").val();
            let service_charge = $(".service_charge").val();
            let late_fee = $(".late_fee").val();
            let depositor_owed = parseInt(total_deposited) + parseInt(profit) + parseInt(bonus);
            $(".depositor_owing").val(depositor_owed);
            $(".depositor_owing").text(depositor_owed);
            let organization_owed = parseInt(loan_balance) + parseInt(service_charge) + parseInt(late_fee);
            $(".organization_owing").val(organization_owed);
            $(".organization_owing").text(organization_owed);

            $(".total").text(depositor_owed-organization_owed);
        }

        $('.date')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#date').val(e.format('yyyy-mm-dd'));
            });

        var account = "{{ $loan->account_no }}";
        loadData(account);
        function loadData(account)
        {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax":{
                    "url": "{{ url('dataCollectionsByAccount') }}",
                    data: {account_no: account}
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
                // Buttons with Dropdown
                buttons: ["copy", "print"],

            });
        }





        $("#btn-close").on("click", function () {
            var $this = $("#btn-close"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#closingForm").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('account-closing.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $("#modalAccountClosing").modal("hide");
                    $this.attr('disabled', false).html($caption);
                    window.location.href = "{{ url('monthly-savings') }}/{{ $loan->id }}";
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $.NotificationApp.send("Error", "Try again", "bottom-right", "rgba(0,0,0,0.2)", "error")
                }
            })
        })

        function makeActive(account) {
            swal.fire({
                title: "আপনি কি নিশ্চিত?",
                icon: 'question',
                text: "আপনি এটি ফিরিয়ে আনতে পারবেন না!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: '#0acf97',
                cancelButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, হিসাব পুনঃরায় চালু করুন!',
                cancelButtonText: 'না, বাতিল করুন',
                reverseButtons: !0
            }).then(function (e) {
                $.ajax({
                    type: 'get',
                    url: "{{url('makeAccountActive')}}/" + account,
                    success: function (results) {
                        if (results=="success")
                        {
                            window.location.href = "{{ url('monthly-savings') }}/{{ $loan->id }}";
                        }
                    }
                });

            }, function (dismiss) {
                return false;
            })
        }
        const options = {
            keyboard: true,
            size: 'sm'
        };
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            const lightbox = new Lightbox(el, options);
            $(this).lightbox.show();
        });

        $(".loan-trx").DataTable();

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
                    //$("#form-edit input[name='deposit']").val(data.deposit);
                    //$("#form-edit input[name='withdraw']").val(data.withdraw);
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
                        $("#loan_installment").val("");
                        $("#late_fee").val("");
                        $("#notes").val("");
                        $(".details").empty();
                        $(".btn-details").empty();
                        $(".avatar").empty();
                        $("#modalEdit").modal('hide');

                        location.reload();
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
    </script>
@endsection

