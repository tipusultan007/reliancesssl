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
            <div class="col-4">
                    <h4 class="page-title">মাসিক ঋণ</h4>
            </div>
            <div class="col-4"><h4 class="page-title text-danger">হিসাব নংঃ {{ $loan->account_no }}</h4></div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-sm-12">
                <!-- Profile -->
                <div class="card bg-light-lighten">
                    <div class="card-body profile-user-box">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="avatar-lg d-block mx-auto">
                                    <img src="{{ asset('uploads') }}/{{ $loan->member->photo }}" alt="" class="rounded-circle img-thumbnail">
                                </div>
                                <a class="btn btn-success my-2 w-100" href="{{ route('monthly-loans.edit',$loan->id) }}">এডিট</a>
                                <button class="btn btn-primary my-1 w-100" data-bs-toggle="modal" data-bs-target="#statusModal">স্ট্যাটাস</button>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-sm table-light w-100 table-bordered">
                                    <tr><th>নাম</th><td><a target="_blank" href="{{ route('members.show',$loan->member_id) }}">{{ $loan->member->name }}</a></td></tr>
                                    <tr><th>মোবাইল</th><td>{{ $loan->member->phone??'-' }}</td></tr>
                                    <tr><th>ঠিকানা</th><td>{{ $loan->member->present_address??'-' }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-3">
                                @php
                                    //$loan = \App\Models\MonthlyLoan::where('account_no',$saving->account_no)->where('status','active')->first();
                                    if ($loan)
                                        {
                                            $interest = \App\Models\MonthlyCollection::where('loan_id',$loan->id)->sum('monthly_interest');
                                        }
                                @endphp
                                    <table class="table table-sm table-light">
                                        <tr><th>ঋণ প্রদানের তারিখ</th> <td>{{ date('d/m/Y',strtotime($loan->date)) }}</td></tr>
                                        <tr><th>ঋণের পরিমাণ</th> <td>{{ $loan->loan_amount }}</td></tr>
                                        <tr><th>সুদের পরিমাণ</th> <td>{{ $loan->interest_rate }}%</td></tr>
                                    </table>
                                   {{-- <ul class="mb-0 list-inline text-light">
                                        <li class="list-inline-item me-3">
                                            <h5 class="mt-1 mb-1 text-white">{{ $loan->member->name }}</h5>
                                            <p class="font-13 text-white-50"> {{ $loan->member->phone }}</p>
                                        </li>
                                        <li class="list-inline-item">
                                            <h5 class="mt-1 mb-1 text-white">{{ $loan->account_no }}</h5>
                                            <p class="font-13 text-white-50"> হিসাব নং</p>
                                        </li>
                                    </ul>

                                    <ul class="mb-0 list-inline text-light">
                                        @if($loan)
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1 text-white">{{$loan->loan_amount}} টাকা</h5>
                                                <p class="mb-0 font-13 text-white-50"> মোট ঋণ বিতরণ</p>
                                            </li><li class="list-inline-item me-3">
                                                <h5 class="mb-1 text-white">{{$loan->balance}} টাকা</h5>
                                                <p class="mb-0 font-13 text-white-50"> অবশিষ্ট ঋণ</p>
                                            </li>
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1 text-white">{{$interest}} টাকা</h5>
                                                <p class="mb-0 font-13 text-white-50"> লভ্যাংশ আদায়</p>
                                            </li>
                                        @endif
                                    </ul>--}}
                            </div>
                            <div class="col-md-3">
                                <table class="table table-sm table-light">
                                    <tr><th>ঋণ ফেরত</th> <td>{{ $loan->total_paid_loan }}</td></tr>
                                    <tr><th>অবশিষ্ট ঋণ</th> <td>{{ $loan->remain_balance }}</td></tr>
                                    <tr><th>লভ্যাংশ আদায়</th> <td>{{ $loan->total_paid_interest + $loan->extra_interest }}</td></tr>
                                </table>
                            </div>
                        </div>

                    </div> <!-- end card-body/ profile-user-box-->
                </div><!--end profile/ card -->
            </div> <!-- end col-->
        </div>
        @php
        $guarantor = \App\Models\Guarantor::where('loan_id',$loan->id)->where('loan_type','monthly')->first();
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
                                <th> নাম </th> <td>{{ $guarantor->member->name??"" }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->member->name??"" }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->member->present_address??"" }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>
                                    @if(isset($guarantor->member))
                                    @if($guarantor->member->photo !="")
                                        <a data-lightbox="photo" href="{{ asset('storage/'.$guarantor->member->photo) }}">
                                            ছবি
                                        </a>
                                    @endif
                                        @if($guarantor->member->signature !="")
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
                                <th> নাম </th> <td>{{ $guarantor->name1??"" }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->g_mobile1??"" }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->address1??"" }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>

                                    @if($guarantor->photo1 !="")
                                        <a data-lightbox="photo1" href="{{ asset('storage/'.$guarantor->photo1) }}">
                                            ছবি
                                        </a>
                                    @endif
                                    @if($guarantor->signature1 !="")
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
                                <th> নাম </th> <td>{{ $guarantor->name2??"" }}</td>
                            </tr>
                            <tr>
                                <th> মোবাইল নং </th> <td>{{ $guarantor->g_mobile2??"" }}</td>
                            </tr>
                            <tr>
                                <th> ঠিকানা </th> <td>{{ $guarantor->address2??"" }}</td>
                            </tr>
                            <tr>
                                <th> ফাইল </th> <td>
                                    @if($guarantor->photo2 !="")
                                        <a data-lightbox="photo2" href="{{ asset('storage/'.$guarantor->photo2) }}">
                                            ছবি
                                        </a>
                                    @endif
                                    @if($guarantor->signature2 !="")
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
                                <th> চক্রবৃদ্ধি সুদ আদায়</th>
                                <th> ঋণ ব্যালেন্স</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            @foreach($loan->loanCollections as $item)
                                <tr>
                                    <td>{{ date('d/m/Y',strtotime($item->date)) }}</td>
                                    <td>{{ $item->loan_installment }}</td>
                                    <td>{{ $item->monthly_interest }}</td>
                                    <td>{{ $item->extra_interest }}</td>
                                    <td>{{ $item->balance }}</td>
                                    <td>
                                        <button data-id="{{ $item->id }}" class="btn btn-sm btn-warning edit">এডিট</button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteConfirmation({{$item->id}})">ডিলেট</button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->

    @php
        //$total_deposited = $saving->total;
        $loan_balance = $loan?$loan->balance:0;
        $due_interest = 0;

        if ($loan)
    {
        $paidInterest = \App\Models\MonthlyCollection::where('loan_id',$loan->id)->sum('interest_installments');
        $principal = $loan->balance;
        $rate = $loan->interest_rate;
        $interest = ($principal * $rate * 1)/100;
        $dueInterest = getMonthListFromDate($loan->date,date('Y-m-d')) - $paidInterest;

        $due_interest = $interest * $dueInterest;
    }
    @endphp
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
                                <input type="text" name="account_no" class="form-control" value="{{ $loan->account_no }}" readonly>
                            </div>

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

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">স্ট্যাটাস আপডেট</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" action="{{ route('monthly.loan.status.update',$loan->id) }}" method="POST" id="form-edit">
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

        var edit_interest_rate = 0;
        var edit_monthly_amount = 0;

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

        $(document).on("change","#form-edit input[name='monthly_installments']",function () {
            let installments = $(this).val();
            $("#form-edit input[name='monthly_amount']").val(edit_monthly_amount*installments);
        })
        $(document).on("change","#form-edit input[name='interest_installments']",function () {
            let installments = $(this).val();
            $("#form-edit input[name='monthly_interest']").val(edit_interest_rate*installments);
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
                        loadData(account);
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

        function deleteConfirmation(id) {
            swal.fire({
                title: "আপনি কি নিশ্চিত?",
                icon: 'question',
                text: "আপনি এটি ফিরিয়ে আনতে পারবেন না!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: '#0acf97',
                cancelButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ',
                cancelButtonText: 'না',
                reverseButtons: !0
            }).then(function (e) {

                if (e.value === true) {

                    $.ajax({

                        url: "{{url('monthly-loan-collections')}}/" + id,
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.success === true) {

                                $.NotificationApp.send("Success", results.message, "bottom-right", "rgba(0,0,0,0.2)", "success");
                                location.reload();
                            } else {
                                $.NotificationApp.send("Error", results.message, "bottom-right", "rgba(0,0,0,0.2)", "error");
                                location.reload();
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
                confirmButtonText: 'হ্যাঁ',
                cancelButtonText: 'না',
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

    </script>

@endsection

