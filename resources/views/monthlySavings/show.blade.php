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
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-4">
                <h4 class="page-title">মাসিক সঞ্চয়</h4>
            </div>
            <div class="col-4"><h4 class="page-title text-danger">হিসাব নংঃ {{ $saving->account_no }}</h4></div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-sm-12">
                <!-- Profile -->
                <div class="card bg-primary">
                    <div class="card-body profile-user-box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="avatar-lg d-block mx-auto">
                                            <img src="{{ asset('storage/'.$saving->member->photo) }}" alt="" class="rounded-circle img-thumbnail">
                                        </div>

                                        <button type="button" class="btn w-100 btn-light my-2" data-bs-toggle="modal"
                                                data-bs-target="#modalMemberDetails">
                                            <i class="mdi mdi-account-edit me-1"></i> ব্যক্তিগত তথ্য
                                        </button>
                                        @if($saving->status=="active")
                                            <button type="button" class="btn w-100 btn-success" data-bs-toggle="modal" data-bs-target="#modalAccountClosing">
                                                <i class="mdi mdi-account-check me-1"></i> হিসাব নিস্পত্তি
                                            </button>
                                        @else
                                            <a href="javascript:void(0);"  class="btn btn-danger" onclick="makeActive('{{$saving->account_no}}')">
                                                <i class="mdi mdi-account-check me-1"></i> হিসাব চালু
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-sm table-light w-100 table-bordered">
                                            <tr><th>নাম</th><td><a target="_blank" href="{{ route('members.show',$saving->member_id) }}">{{ $saving->member->name }}</a></td></tr>
                                            <tr><th>মোবাইল</th><td>{{ $saving->member->phone??'-' }}</td></tr>
                                            <tr><th>ঠিকানা</th><td>{{ $saving->member->present_address??'-' }}</td></tr>
                                            <tr><th>তারিখ</th><td>{{ date('d/m/Y',strtotime($saving->date)) }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        @php
                                            $loans = \App\Models\MonthlyLoan::where('account_no',$saving->account_no)->get();
                                        @endphp
                                        <table class="table table-sm table-light w-100 table-bordered">
                                            <tr><th>তারিখ</th><td>{{ date('d/m/Y',strtotime($saving->date)) }}</td></tr>
                                            <tr><th>জমা</th><td>{{ $saving->deposit }}</td></tr>
                                            <tr><th>উত্তোলন</th><td>{{ $saving->withdraw }}</td></tr>
                                            <tr><th>মুনাফা</th><td>{{ $saving->profit }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table table-sm table-light table-bordered">
                                            <tr><th>ঋণ প্রদান</th><td>{{$saving->total_loan_provide}}</td></tr>
                                            <tr><th>ঋণ ফেরত</th><td>{{$saving->total_paid_loan}}</td></tr>
                                            <tr><th>লভ্যাংশ আদায়</th><td>{{$saving->total_paid_interest}}</td></tr>
                                            <tr><th>অবশিষ্ট ঋণ</th><td>{{ $saving->remain_loan }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-sm-4">
                                <div class="text-center mt-sm-0 mt-3 text-sm-end">


                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row -->

                    </div> <!-- end card-body/ profile-user-box-->
                </div><!--end profile/ card -->
            </div> <!-- end col-->
        </div>
        <!-- end row -->

        @php
            $nominee = \App\Models\Nominee::where('account_no',$saving->account_no)->latest()->first();
        @endphp
        @if($nominee)
            <div class="row">
                <div class="col md-6">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">নমিনি-০১</h4>
                            <button class="btn btn-sm btn-light edit-nominee" data-bs-toggle="modal"
                                    data-bs-target="#modalEditNominee">এডিট করুন <i
                                    class="mdi mdi-account-edit ms-1"></i></button>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>নামঃ</th>
                                    <td> {{$nominee->nominee_name}} </td>
                                </tr>
                                <tr>
                                    <th>ঠিকানাঃ</th>
                                    <td>  {{$nominee->nominee_address}} </td>
                                </tr>
                                <tr>
                                    <th>মোবাইলঃ</th>
                                    <td>  {{$nominee->nominee_mobile}} </td>
                                </tr>
                                <tr>
                                    <th>সম্পর্কঃ</th>
                                    <td> {{$nominee->nominee_relation}} </td>
                                </tr>
                                <tr>
                                    <th>অংশঃ</th>
                                    <td> {{$nominee->nominee_percentage}}%</td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র ছবিঃ</th>
                                    <td><img height="80" src="{{ asset('storage/'.$nominee->nominee_photo) }}" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র এনআইডিঃ</th>
                                    <td><img height="120" src="{{ asset('storage/'.$nominee->nominee_nid) }}" alt="">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col md-6">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">নমিনি-০২</h4>
                            <a href="javascript:void(0);" class="btn btn-sm btn-light">এডিট করুন <i
                                    class="mdi mdi-account-edit ms-1"></i></a>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>নামঃ</th>
                                    <td> {{$nominee->nominee_name1}} </td>
                                </tr>
                                <tr>
                                    <th>ঠিকানাঃ</th>
                                    <td>  {{$nominee->nominee_address1}} </td>
                                </tr>
                                <tr>
                                    <th>মোবাইলঃ</th>
                                    <td>  {{$nominee->nominee_mobile1}} </td>
                                </tr>
                                <tr>
                                    <th>সম্পর্কঃ</th>
                                    <td> {{$nominee->nominee_relation1}} </td>
                                </tr>
                                <tr>
                                    <th>অংশঃ</th>
                                    <td> {{$nominee->nominee_percentage1}}%</td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র ছবিঃ</th>
                                    <td>
                                        <img height="80" src="{{ asset('storage/'.$nominee->nominee_photo1) }}" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র এনআইডিঃ</th>
                                    <td><img height="120" src="{{ asset('storage/'.$nominee->nominee_nid1) }}" alt="">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h4 class="header-title text-white">সকল ঋণ</h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-sm table-striped table-centered mb-0">
                            <thead>
                            <tr>
                                <th>হিসাব নং</th>
                                <th>ঋণের তারিখ</th>
                                <th>ঋণের পরিমাণ</th>
                                <th>সুদের হার</th>
                                <th>অবশিষ্ট ঋণ</th>
                                <th>লভ্যাংশ আদায়</th>
                                <th>স্ট্যাটাস</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $loans = \App\Models\MonthlyLoan::where('account_no',$saving->account_no)->get();
                            @endphp
                            @foreach($loans as $item)

                                <tr>
                                    <td>{{ $item->account_no }}</td>
                                    <td>{{ date('d/m/Y',strtotime($item->date)) }}</td>
                                    <td>{{ $item->loan_amount }}</td>
                                    <td>{{ $item->interest_rate }}%</td>
                                    <td>{{ $item->remain_balance }}</td>
                                    <td>{{ $item->total_paid_interest +  $item->extra_interest}}</td>
                                    <td> @if($item->status=='active')
                                            <span class="badge bg-success">চলমান</span>
                                        @else
                                            <span class="badge bg-danger">পরিশোধিত</span>
                                        @endif</td>
                                    <td>
                                        <div class="dropdown float-end text-muted">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <!-- item-->
                                                <a href="{{ route('monthly-loans.show',$item->id) }}" class="dropdown-item view">বিস্তারিত</a>
                                                <a href="{{ route('monthly-loans.edit',$item->id) }}" class="dropdown-item edit">সম্পাদন করুন</a>
                                                <a href="javascript:void(0);" data-id="{{$item->id}}" onclick="deleteSavingsConfirmation({{$item->id}})" class="dropdown-item delete"> ডিলেট করুন</a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h4 class="header-title text-white">সকল লেনদেন</h4>
                    </div>
                    <div class="card-body">
                        <table class="table" id="datatables">
                            <thead>
                            <tr>
                                <th>নাম</th>
                                <th>হিসাব নং</th>
                                <th>জমা</th>
                                <th>কিস্তি সংখ্যা</th>
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

    @php
    $loan = \App\Models\MonthlyLoan::where('account_no',$saving->account_no)->where('status','active')->first();
        $total_deposited = $saving->total;
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
    <div class="modal fade" id="modalAccountClosing" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-md-center" id="myLargeModalLabel">হিসাব বিবরণী</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="closingForm">
                    @csrf
                    <div class="modal-body">
                        @if($guarantorData)
                            <h2 class="text-danger text-center">এই সদস্য উক্ত চলমান ঋণের জামিনদার</h2>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>হিসাব নং</th>
                                    <th>নাম</th>
                                    <th>অবশিষ্ট ঋণ</th>
                                </tr>
                                </thead>
                                @forelse($guarantorData as $item)
                                    <tr>
                                        <td>{{ $item['account_no'] }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['balance'] }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </table>
                        @endif


                        <table class="table table-sm table-borderless">
                            <tr>
                                <th style="width: 20%">নাম</th> <td>:</td> <td colspan="4">{{ $saving->member->name }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">হিসাব নং</th> <td>:</td> <td colspan="4">{{ $saving->account_no }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">হিসাব শুরুর তারিখ</th> <td>:</td> <td>{{ date('d/m/Y',strtotime($saving->date)) }}</td> <th style="width: 20%">হিসাব বন্ধের তারিখ</th> <td>:</td> <td><div class="position-relative" id="datepicker1">
                                        <input type="text" class="form-control date" value="{{ date('d/m/Y') }}"
                                               data-provide="datepicker" data-date-autoclose="true"
                                               data-date-container="#datepicker1" required>
                                        <input id="date" name="date" type="hidden" value="{{ date('Y-m-d') }}">
                                    </div></td>
                            </tr>
                        </table>
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;text-align: center;font-weight: bold">আমানতকারীর পাওনা</caption>
                            <thead>
                            <tr>
                                <th class="text-center">সঞ্চয়ের মোট জমা</th>
                                <th class="text-center">মুনাফা</th>
                                <th class="text-center">বোনাস</th>
                                <th class="text-center">মোট টাকা</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-end "><input type="number" class="text-end total_deposited form-control" name="total_deposited" value="{{ $total_deposited }}" readonly></td>
                                <td class="text-end "><input type="number" class="text-end profit form-control" value="0" min="0" name="profit"></td>
                                <td class="text-end "><input type="number" class="text-end bonus form-control" value="0" min="0" name="bonus"></td>
                                <td class="text-end "><input type="number" class="text-end depositor_owing form-control" name="depositor_owing" readonly></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;text-align: center;font-weight: bold">প্রতিষ্ঠানের পাওনা</caption>
                            <thead>
                            <tr>
                                <th class="text-center">মোট ঋন/বকেয়া</th>
                                <th class="text-center">বকেয়া লভ্যাংশ</th>
                                <th class="text-center">হিসাব প্রত্যাহার </th>
                                <th class="text-center">জরিমানা</th>
                                <th class="text-center">মোট টাকা</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-end ">
                                    <input type="hidden" name="loan_id" value="{{ $loan?$loan->id:"" }}">
                                    <input type="number" class="text-end loan_balance form-control" name="loan_balance" value="{{ $loan_balance }}" readonly></td>
                                <td class="text-end "><input type="number" class="text-end due_interest form-control" name="due_interest" value="{{ $due_interest }}" readonly></td>
                                <td class="text-end "><input type="number"  class="text-end service_charge form-control" value="0" min="0" name="service_charge"></td>
                                <td class="text-end "><input type="number" class="text-end late_fee form-control" value="0" min="0" name="late_fee"></td>
                                <td class="text-end "><input type="number" class="text-end organization_owing form-control" name="organization_owing" readonly></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-end">আমানতকারীর পাওনাঃ</th><td class="text-end depositor_owing"> </td>
                            </tr>
                            <tr>
                                <th class="text-end">প্রতিষ্ঠানের পাওনাঃ</th><td class="text-end organization_owing"> </td>
                            </tr>
                            <tr>
                                <th class="text-end">আমানতকারীর পাওনা/প্রতিষ্ঠানের পাওনাঃ</th><td class="text-end total"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="account_no" value="{{ $saving->account_no }}">
                        <input type="hidden" name="member_id" value="{{ $saving->member_id }}">
                        <input type="hidden" name="trx_id" value="{{ \Illuminate\Support\Str::uuid() }}">
                        <input type="hidden" name="type" value="monthly">
                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-close" class="btn btn-primary">নিস্পত্তি করুন</button>
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="modalEditNominee" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">নমিনি এডিট</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form action="{{ route('nominees.update',$nominee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>নমিনী -০১</h4>
                                <div class="row mb-3 nominee">
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_name">নাম</label>
                                            <input type="text" class="form-control" id="nominee_name"
                                                   name="nominee_name" value="{{ $nominee->nominee_name }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_address">ঠিকানা</label>
                                            <input type="text" class="form-control" id="nominee_address"
                                                   name="nominee_address" value="{{ $nominee->nominee_address }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_mobile">মোবাইল</label>
                                            <input type="text" class="form-control" id="nominee_mobile"
                                                   name="nominee_mobile" value="{{ $nominee->nominee_mobile }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_photo">নমিনী ছবি</label>
                                            <input type="file" class="form-control" id="nominee_photo"
                                                   name="nominee_photo">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_nid">এন আই ডি</label>
                                            <input type="file" class="form-control" id="nominee_nid" name="nominee_nid">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="birth_date1">জন্ম তারিখ </label>
                                            <div class="position-relative" id="datepicker2">
                                                <input type="text" class="form-control birth_date1"
                                                       value="{{ date('d/m/Y',strtotime($nominee->birth_date)) }}"
                                                       data-provide="datepicker" data-date-autoclose="true"
                                                       data-date-container="#datepicker2" required>
                                                <input id="birth_date1" name="birth_date"
                                                       value="{{ $nominee->birth_date }}" type="hidden">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_relation">সম্পর্ক </label>
                                            <input type="text" class="form-control" id="nominee_relation"
                                                   name="nominee_relation" value="{{ $nominee->nominee_relation }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_percentage">অংশ</label>
                                            <input type="number" class="form-control" id="nominee_percentage"
                                                   name="nominee_percentage" value="{{ $nominee->nominee_percentage }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>নমিনী -০২</h4>
                                <div class="row mb-3 nominee">
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_name1">নাম</label>
                                            <input type="text" class="form-control" id="nominee_name1"
                                                   name="nominee_name1" value="{{ $nominee->nominee_name1??'' }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_address1">ঠিকানা</label>
                                            <input type="text" class="form-control" id="nominee_address1"
                                                   name="nominee_address1" value="{{ $nominee->nominee_address1??'' }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_mobile1">মোবাইল</label>
                                            <input type="text" class="form-control" id="nominee_mobile1"
                                                   name="nominee_mobile1" value="{{ $nominee->nominee_name1??'' }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_photo1">নমিনী ছবি</label>
                                            <input type="file" class="form-control" id="nominee_photo1"
                                                   name="nominee_photo1">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_nid1">এন আই ডি</label>
                                            <input type="file" class="form-control" id="nominee_nid1"
                                                   name="nominee_nid1" value="{{ $nominee->nominee_name1??'' }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="birth_date2">জন্ম তারিখ </label>
                                            <div class="position-relative" id="datepicker3">
                                                <input type="text" class="form-control birth_date2"
                                                       data-provide="datepicker"
                                                       value="{{ date('d/m/Y',strtotime($nominee->birth_date1)) }}"
                                                       data-date-autoclose="true" data-date-container="#datepicker3"
                                                       required>
                                                <input id="birth_date2" name="birth_date1" type="hidden"
                                                       value="{{ $nominee->birth_date1 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_relation1">সম্পর্ক </label>
                                            <input type="text" class="form-control" id="nominee_relation1"
                                                   name="nominee_relation1"
                                                   value="{{ $nominee->nominee_relation1??'' }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-12 mt-2">
                                        <div class="form-group">
                                            <label class="form-label" for="nominee_percentage1">অংশ</label>
                                            <input type="number" class="form-control" id="nominee_percentage1"
                                                   name="nominee_percentage1"
                                                   value="{{ $nominee->nominee_percentage1??'' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>


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
                        <img class=" img-thumbnail" src="{{ asset('uploads/'.$saving->member->photo) }}" style="height: 100px" alt="">
                    </div>

                    <table class="table table-sm table-striped">
                        <tr>
                            <th>নামঃ</th>
                            <td class="ms-2">{{ $saving->member->name }}</td>
                            <th>পিতার নামঃ</th>
                            <td class="ms-2">{{ $saving->member->father_name }}</td>
                        </tr>
                        <tr>
                            <th>মাতার নামঃ</th>
                            <td class="ms-2">{{ $saving->member->mother_name }}</td>
                            <th>জন্ম তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($saving->member->birth_date)) }}</td>
                        </tr>
                        <tr>
                            <th>লিঙ্গঃ</th>
                            <td class="ms-2"> @if($saving->member->gender=='male')
                                    পুরুষ
                                @else
                                    মহিলা
                                @endif</td>
                            <th>বৈবাহিক অবস্থাঃ</th>
                            <td class="ms-2">@if($saving->member->marital_status=='married')
                                    বিবাহিত
                                @else
                                    অবিবাহিত
                                @endif</td>
                        </tr>
                        <tr>
                            <th>স্বামী/স্ত্রীর নামঃ</th>
                            <td class="ms-2">{{ $saving->member->spouse_name??'' }}</td>
                            <th>মোবাইলঃ</th>
                            <td class="ms-2">{{ $saving->member->phone }}</td>
                        </tr>
                        <tr>
                            <th>বর্তমান ঠিকানাঃ</th>
                            <td class="ms-2">{{ $saving->member->present_address }}</td>
                            <th>স্থায়ী ঠিকানাঃ</th>
                            <td class="ms-2">{{ $saving->member->permanent_address }}</td>
                        </tr>
                        <tr>
                            <th>জাতীয়তাঃ</th>
                            <td class="ms-2">{{ $saving->member->nationality }}</td>
                        </tr>
                        <tr>
                            <th>এন আইডিঃ</th>
                            <td class="ms-2">{{ $saving->member->nid_no }}</td>
                            <th>পেশাঃ</th>
                            <td class="ms-2">{{ $saving->member->occupation }}</td>
                        </tr>
                        <tr>
                            <th>কর্মস্থলঃ</th>
                            <td class="ms-2">{{ $saving->member->workplace }}</td>
                            <th>নিবন্ধন তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($saving->member->join_date)) }}</td>
                        </tr>
                        <tr>
                            <th>এনআইডিঃ </th>
                            <td class="ms-2"><img class=" img-thumbnail" src="{{ asset('uploads/'.$saving->member->nid) }}" style="height: 100px" alt=""></td>
                            <th>স্বাক্ষরঃ</th>
                            <td class="ms-2"><img class=" img-thumbnail" src="{{ asset('uploads/'.$saving->member->signature) }}" style="height: 100px" alt=""></td>
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
                                <input type="text" name="account_no" class="form-control" value="{{ $saving->account_no }}" readonly>
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

        var interest_rate = 0;
        var monthly_amount = 0;

        var edit_interest_rate = 0;
        var edit_monthly_amount = 0;

        var account = "{{ $saving->account_no }}";
        loadData(account);

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
                     { data: 'monthly_installments' },
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
                                loadData(account);
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
        function deleteSavingsConfirmation(id) {
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
                        url: "{{url('monthly-savings')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.success === true) {
                                $("#datatables").DataTable().destroy();
                                loadData(account);
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
                    window.location.href = "{{ url('monthly-savings') }}/{{ $saving->id }}";
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
                            window.location.href = "{{ url('monthly-savings') }}/{{ $saving->id }}";
                        }
                    }
                });

            }, function (dismiss) {
                return false;
            })
        }
    </script>

@endsection

