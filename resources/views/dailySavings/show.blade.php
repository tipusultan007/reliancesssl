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
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Profile</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-sm-12">
                <!-- Profile -->
                <div class="card bg-primary">
                    <div class="card-body profile-user-box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row align-items-top">
                                    <div class="col-md-2 text-center">
                                        <div class="avatar-lg d-block mx-auto">
                                            @if( $saving->member->photo != null)
                                                <img src="{{ asset('storage/'.$saving->member->photo) }}" alt=""
                                                     class="rounded-circle img-thumbnail">
                                            @else
                                                <img src="{{ asset('assets/male.png') }}" alt=""
                                                     class="rounded-circle img-thumbnail">
                                            @endif

                                        </div>
                                        <button type="button" class="btn btn-light mt-2" data-bs-toggle="modal"
                                                data-bs-target="#modalMemberDetails">
                                            <i class="mdi mdi-account-edit me-1"></i> ব্যক্তিগত তথ্য
                                        </button>
                                    </div>
                                    @php
                                        $loan = \App\Models\DailyLoan::where('account_no',$saving->account_no)->where('status','active')->latest()->first();
                                    @endphp
                                    <div class="col-md-4">
                                        <table class="table table-sm table-light w-100 table-bordered">
                                            <tr><th>নাম</th><td><a target="_blank" href="{{ route('members.show',$saving->member_id) }}">{{ $saving->member->name }}</a></td></tr>
                                            <tr><th>মোবাইল</th><td>{{ $saving->member->phone??'-' }}</td></tr>
                                            <tr><th>ঠিকানা</th><td>{{ $saving->member->present_address??'-' }}</td></tr>
                                            <tr><th>তারিখ</th><td>{{ date('d/m/Y',strtotime($saving->date)) }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table table-sm table-light w-100 table-bordered">

                                            <tr><th>জমা</th><td>{{ $saving->total_deposit }}</td></tr>
                                            <tr><th>উত্তোলন</th><td>{{ $saving->total_withdraw }}</td></tr>
                                            <tr><th>মুনাফা</th><td>{{ $saving->total_profit }}</td></tr>
                                            <tr><th>অবশিষ্ট ব্যলেন্স</th><td>{{ $saving->total_balance }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table table-sm table-light w-100 table-bordered">
                                            <tr><th>অবশিষ্ট ঋণ</th><td>{{ $loan?$loan->total_balance:0 }}</td></tr>
                                            <tr>
                                                <th colspan="2">
                                                    @if($saving->status=="active")
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                                data-bs-target="#modalAccountClosing">
                                                            <i class="mdi mdi-account-check me-1"></i> হিসাব নিস্পত্তি করুন
                                                        </button>
                                                    @else
                                                        <a href="javascript:void(0);" class="btn btn-danger"
                                                           onclick="makeActive('{{$saving->account_no}}')">
                                                            <i class="mdi mdi-account-check me-1"></i> হিসাব চালু করুন
                                                        </a>
                                                    @endif
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
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
                            <button class="btn btn-sm btn-light edit-nominee">এডিট করুন <i
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
                                    <td>
                                        @if($nominee->nominee_photo)
                                            <img height="80" src="{{ asset('storage/'.$nominee->nominee_photo) }}" alt="">
                                        @else

                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র এনআইডিঃ</th>
                                    <td>
                                        @if($nominee->nominee_nid)
                                            <img height="120" src="{{ asset('storage/'.$nominee->nominee_nid) }}" alt="">
                                        @else

                                        @endif

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
                                    <td>@if($nominee->nominee_photo1)
                                            <img height="80" src="{{ asset('storage/'.$nominee->nominee_photo1) }}" alt="">
                                        @else

                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <th>নমিনি'র এনআইডিঃ</th>
                                    <td>
                                        @if($nominee->nominee_nid1)
                                            <img height="120" src="{{ asset('storage/'.$nominee->nominee_nid1) }}" alt="">
                                        @else

                                        @endif

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
                                <th>সুদের পরিমাণ</th>
                                <th>সর্বমোট</th>
                                <th>অবশিষ্ট ঋণ</th>
                                <th>স্ট্যাটাস</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $loans = \App\Models\DailyLoan::where('account_no',$saving->account_no)->get();
                            @endphp
                            @foreach($loans as $item)
                                <tr>
                                    <td>{{ $item->account_no }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->loan_amount }}</td>
                                    <td>{{ $item->interest_rate }}%</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->total_balance }}</td>
                                    <td> @if($item->status=='active')
                                            <span class="badge bg-success">চলমান</span>
                                        @else
                                            <span class="badge bg-danger">পরিশোধিত</span>
                                        @endif</td>
                                    <td>
                                        <div class="dropdown float-end text-muted">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop"
                                               data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <!-- item-->
                                                <a href="{{ route('daily-loans.show',$item->id) }}"
                                                   class="dropdown-item ">বিস্তারিত</a>
                                                <a href="{{ route('daily-loans.edit',$item->id) }}"
                                                   class="dropdown-item edit">সম্পাদন করুন</a>
                                                <a href="javascript:;" onclick="deleteLoan({{ $item->id }})"
                                                   class="dropdown-item">ডিলেট করুন</a>


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
            <!-- end col -->

        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm" id="datatables">
                            <thead>
                            <tr>
                                <th>নাম</th>
                                <th>হিসাব নং</th>
                                <th>জমা</th>
                                <th>উত্তোলন</th>
                                <th>ঋণ ফেরত</th>
                                <th>বিলম্ব ফি</th>
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
                                        <label for="deposit" class="form-label">জমা</label>
                                        <input type="number" name="deposit" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="withdraw" class="form-label">উত্তোলন</label>
                                        <input type="number" name="withdraw" class="form-control">
                                    </div>
                                </div>
                            </div>
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

    <!-- Modal -->
    <div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">বিবরণী</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
    @php
        $total_deposited = $saving->deposit - $saving->withdraw;
        $loan_balance = $loan?$loan->balance:0;
        $due_interest = 0;
    @endphp
        <!--  Modal content for the Large example -->
    <div class="modal fade" id="modalAccountClosing" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-md-center" id="myLargeModalLabel">হিসাব বিবরণী</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formClosingAccount">
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
                                <th style="width: 20%">নাম</th>
                                <td>:</td>
                                <td colspan="4">{{ $saving->member->name }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">হিসাব নং</th>
                                <td>:</td>
                                <td colspan="4">{{ $saving->account_no }}</td>
                            </tr>
                            <tr>
                                <th style="width: 20%">হিসাব শুরুর তারিখ</th>
                                <td>:</td>
                                <td>{{ date('d/m/Y',strtotime($saving->date)) }}</td>
                                <th style="width: 20%">হিসাব বন্ধের তারিখ</th>
                                <td>:</td>
                                <td>
                                    <div class="position-relative" id="datepicker1">
                                        <input type="text" class="form-control date" value="{{ date('d/m/Y') }}"
                                               data-provide="datepicker" data-date-autoclose="true"
                                               data-date-container="#datepicker1" required>
                                        <input id="date" name="date" type="hidden" value="{{ date('Y-m-d') }}">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;text-align: center;font-weight: bold">আমানতকারীর পাওনা
                            </caption>
                            <thead>
                            <tr>
                                <th class="text-center">সঞ্চয়ের মোট জমা</th>
                                <th class="text-center">মুনাফা</th>
                                <th class="text-center">বোনাস</th>
                                <th class="text-center">মোট টাকা</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
$depositAndProfit = calculateRemainingDepositAndProfit($saving->total_deposit, $saving->total_profit, $saving->total_withdraw, $saving->total_balance)
 @endphp
                            <tr>
                                <td class="text-end "><input type="number" class="text-end total_deposited form-control"
                                                             name="total_deposited" value="{{ $depositAndProfit['remainingDeposit'] }}"
                                                             readonly></td>
                                <td class="text-end "><input type="number" class="text-end profit form-control"
                                                             value="{{ $depositAndProfit['remainingProfit'] }}" min="0" name="profit"></td>
                                <td class="text-end "><input type="number" class="text-end bonus form-control" value="0"
                                                             min="0" name="bonus"></td>
                                <td class="text-end "><input type="number" class="text-end depositor_owing form-control"
                                                             name="depositor_owing" readonly></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <caption style="caption-side: top;text-align: center;font-weight: bold">প্রতিষ্ঠানের পাওনা
                            </caption>
                            <thead>
                            <tr>
                                <th class="text-center">মোট ঋন/বকেয়া</th>
                                <th class="text-center">বকেয়া লভ্যাংশ</th>
                                <th class="text-center">হিসাব প্রত্যাহার ফি</th>
                                <th class="text-center">জরিমানা</th>
                                <th class="text-center">মোট টাকা</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-end ">
                                    <input type="hidden" name="loan_id" value="{{ $loan?$loan->id:"" }}">
                                    <input type="number" class="text-end loan_balance form-control" name="loan_balance"
                                           value="{{ $loan?$loan->total_balance:0 }}" readonly></td>
                                <td class="text-end "><input type="number" class="text-end due_interest form-control"
                                                             name="due_interest" value="{{ $due_interest }}" readonly>
                                </td>
                                <td class="text-end "><input type="number" class="text-end service_charge form-control"
                                                             value="0" min="0" name="service_charge"></td>
                                <td class="text-end "><input type="number" class="text-end late_fee form-control"
                                                             value="0" min="0" name="late_fee"></td>
                                <td class="text-end "><input type="number"
                                                             class="text-end organization_owing form-control"
                                                             name="organization_owing" readonly></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-end">আমানতকারীর পাওনাঃ</th>
                                <td class="text-end depositor_owing"></td>
                            </tr>
                            <tr>
                                <th class="text-end">প্রতিষ্ঠানের পাওনাঃ</th>
                                <td class="text-end organization_owing"></td>
                            </tr>
                            <tr>
                                <th class="text-end">আমানতকারীর পাওনা/প্রতিষ্ঠানের পাওনাঃ</th>
                                <td class="text-end total"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="account_no" value="{{ $saving->account_no }}">
                        <input type="hidden" name="member_id" value="{{ $saving->member_id }}">
                        <input type="hidden" name="trx_id" value="{{ \Illuminate\Support\Str::uuid() }}">
                        <input type="hidden" name="type" value="daily">
                        <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-close" class="btn btn-primary">নিস্পত্তি করুন</button>
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @if($nominee)
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
        </div>
    @endif <!-- end modal-->
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
@endsection
@section('scripts')
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
    <!-- Chart.js -->
    <script src="{{asset('assets/vendor/chart.js/chart.min.js')}}"></script>
    <!-- Profile Demo App js -->
    <script src="{{asset('assets/js/pages/demo.profile.js')}}"></script>
    <script src="{{asset('assets/js/pages/demo.toastr.js')}}"></script>
    <script>
        function deleteLoan(id) {
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
                        url: "{{url('daily-loans')}}/" + id,
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
    </script>

    <script>
        var account = "{{ $saving->account_no }}";

        calculate();
        $(document).on("change", "input", function () {
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

            $(".total").text(depositor_owed - organization_owed);
        }

        loadData(account);

        function loadData(account_no) {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('getSavingsTransaction') }}",
                    data: {account_no: account_no}
                },
                columns: [
                    // columns according to JSON

                    {data: 'name'},
                    {data: 'account_no'},
                    {data: 'deposit'},
                    {data: 'withdraw'},
                    {data: 'loan_installment'},
                    {data: 'late_fee'},
                    {data: 'date'},
                    {data: 'action'},
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
                        loadData(account);
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
        $(document).on("click", ".view", function () {
            let id = $(this).data('id');
            $(".table-details tbody").empty();
            $.ajax({
                url: "{{ url('getDetails') }}/" + id,
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
                    if (data.deposit > 0) {
                        $(".table-details tbody").append(`
<tr>
<th>জমা</th>    <td class="text-end">${data.deposit} টাকা</td>
</tr>
                    `);
                    }
                    if (data.withdraw > 0) {
                        $(".table-details tbody").append(`
<tr>
<th>উত্তোলন</th>    <td class="text-end">${data.withdraw} টাকা</td>
</tr>
                    `);
                    }
                    if (data.loan_installment > 0) {
                        $(".table-details tbody").append(`
<tr>
<th>ঋণ ফেরত</th>    <td class="text-end">${data.loan_installment} টাকা</td>
</tr>
<tr>
<th>অবশিষ্ট ঋণ</th>    <td class="text-end">${data.loan_balance} টাকা</td>
</tr>
                    `);
                    }
                    if (data.late_fee > 0) {
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


        function dateFormat(input_D, format_D) {
            // input date parsed
            const date = new Date(input_D);

            //extracting parts of date string
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            //to replace month
            format_D = format_D.replace("MM", month.toString().padStart(2, "0"));

            //to replace year
            if (format_D.indexOf("yyyy") > -1) {
                format_D = format_D.replace("yyyy", year.toString());
            } else if (format_D.indexOf("yy") > -1) {
                format_D = format_D.replace("yy", year.toString().substr(2, 2));
            }

            //to replace day
            format_D = format_D.replace("dd", day.toString().padStart(2, "0"));

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
            var formData = $("#formClosingAccount").serializeArray();

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
                    window.location.href = "{{ url('daily-savings') }}/{{ $saving->id }}";
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
                if (e.value == true) {
                    $.ajax({
                        type: 'get',
                        url: "{{url('makeAccountActive')}}/" + account,
                        success: function (results) {
                            if (results == "success") {
                                window.location.href = "{{ url('daily-savings') }}/{{ $saving->id }}";
                            }
                        }
                    });
                }else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }

        $(".edit-nominee").on("click", function () {
            $("#modalEditNominee").modal("show");
        })
    </script>
@endsection

