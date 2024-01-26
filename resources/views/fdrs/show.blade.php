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

        @php
            $fdrWithdraw = \App\Models\FdrCollection::where('account_no',$fdr->account_no)->sum('fdr_withdraw');
            $profitWithdraw = \App\Models\FdrCollection::where('account_no',$fdr->account_no)->sum('profit');
        @endphp
        <div class="row">
            <div class="col-sm-12">
                <!-- Profile -->
                <div class="card bg-primary">
                    <div class="card-body profile-user-box">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-lg">
                                            <img style="width: 96px;height: 96px;"
                                                 src="{{ asset('uploads') }}/{{ $fdr->member->photo??'' }}" alt=""
                                                 class="rounded-circle img-thumbnail">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div>

                                            <h4 class="mt-1 mb-1 text-white">{{ $fdr->member->name }}</h4>
                                            <p class="font-13 text-white-50"> {{ $fdr->member->phone }}</p>

                                            <ul class="mb-0 list-inline text-light">
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdr->account_no }}</h5>
                                                    <p class="mb-0 font-13 text-white-50">হিসাব নং</p>
                                                </li>
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdr->deposits->sum('amount') }}
                                                        টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">FDR জমা</p>
                                                </li>
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdr->withdraws->sum('amount')}}
                                                        টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">FDR উত্তোলন</p>
                                                </li>
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdr->fdrCollection->sum('profit') }}
                                                        টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">মুনাফা উত্তোলন</p>
                                                </li>
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdr->fdr_balance }} টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">অবশিষ্ট জমা</p>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-sm-4">
                                <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                    @if($fdr->status=="active")
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#modalAccountClosing">
                                            <i class="mdi mdi-account-check me-1"></i> হিসাব নিস্পত্তি করুন
                                        </button>
                                    @else
                                        <a href="{{ route('fdr.active',$fdr->id) }}" class="btn btn-danger"
                                           onclick="return confirm('আপনি কি নিশ্চিত?')">
                                            <i class="mdi mdi-account-check me-1"></i> হিসাব চালু করুন
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                            data-bs-target="#modalMemberDetails">
                                        <i class="mdi mdi-account-edit me-1"></i> ব্যক্তিগত তথ্য
                                    </button>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row -->

                    </div> <!-- end card-body/ profile-user-box-->
                </div><!--end profile/ card -->
            </div> <!-- end col-->
        </div>
        <!-- end row -->

        @php
            $nominee = \App\Models\Nominee::where('account_no',$fdr->account_no)->latest()->first();
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
                                    <td><img height="80" src="{{ asset('storage/'.$nominee->nominee_photo1) }}" alt="">
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

        <div class="card">
            <div class="card-header pb-0">
                <h4 class="card-title">FDR জমা</h4>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped fdr-deposits">
                    <thead>
                    <tr>
                        <th>নাম</th>
                        <th>হিসাব নং</th>
                        <th>জমার তারিখ</th>
                        <th>FDR পরিমাণ</th>
                        <th>মুনাফার হার(%)</th>
                        <th>ব্যালেন্স</th>
                        <th>অ্যাকশন</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fdr->deposits as $deposit)
                        <tr>
                            <td>{{ $deposit->member->name }}</td>
                            <td>{{ $deposit->fdr->account_no }}</td>
                            <td>{{ date('d/m/Y',strtotime($deposit->date)) }}</td>
                            <td>{{ $deposit->amount }}</td>
                            <td>{{ $deposit->profit_rate }}</td>
                            <td>{{ $deposit->balance }}</td>
                            <td>
                                <div class="dropdown float-end text-muted">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <!-- item-->
                                        <a href="javascript:void(0);" data-account_no="{{$deposit->fdr->account_no}}"
                                           data-id="{{ $deposit->id }}" data-profit_rate="{{$deposit->profit_rate}}"
                                           data-amount="{{$deposit->amount}}" data-date="{{$deposit->date}}"
                                           class="dropdown-item edit">Edit</a>
                                        <a href="javascript:void(0);" onclick="deleteDeposit({{ $deposit->id }})"
                                           class="dropdown-item">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="card-title">FDR উত্তোলন</h4>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped fdr-deposits">
                    <thead>
                    <tr>
                        <th>নাম</th>
                        <th>হিসাব নং</th>
                        <th>তারিখ</th>
                        <th>উত্তোলন পরিমাণ</th>
                        <th>ব্যালেন্স</th>
                        <th>অ্যাকশন</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fdr->withdraws as $withdraw)
                        <tr>
                            <td>{{ $withdraw->member->name }}</td>
                            <td>{{ $withdraw->fdr->account_no }}</td>
                            <td>{{ date('d/m/Y',strtotime($withdraw->date)) }}</td>
                            <td>{{ $withdraw->amount }}</td>
                            <td>{{ $deposit->balance }}</td>
                            <td>
                                <div class="dropdown float-end text-muted">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <!-- item-->
                                        <a href="javascript:void(0);" data-account_no="{{$withdraw->fdr->account_no}}"
                                           data-id="{{ $withdraw->id }}" data-profit_rate="{{$deposit->profit_rate}}"
                                           data-amount="{{$deposit->amount}}" data-date="{{$deposit->date}}"
                                           class="dropdown-item edit">Edit</a>
                                        <a href="javascript:void(0);" onclick="deleteDeposit({{ $withdraw->id }})"
                                           class="dropdown-item">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                                @php
                                    $dailySavings = \App\Models\Fdr::get();
                                @endphp
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

    <div class="modal fade" id="modalAccountClosing" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">বিবরণী</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                @php
                $total_profit = generateProfit($fdr->id);
                @endphp
                <form id="closingForm" action="{{ route('fdr.closing') }}" method="POST">
                    @csrf
                    <input type="hidden" name="fdr_id" value="{{ $fdr->id }}">
                    <input type="hidden" name="withdraw" value="{{ $fdr->fdr_balance }}">

                    <div class="modal-body">
                        <div id="printDetails">
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                <tr>
                                    <th>হিসাব নংঃ</th>
                                    <th>FDR জমাঃ</th>
                                    <th>FDR প্রাপ্ত মুনাফাঃ</th>
                                    <th>সর্বমোট পাওনাঃ</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td>{{ $fdr->account_no }}</td>
                                    <td>{{ $fdr->fdr_balance }}</td>
                                    <td><input type="number" class="border-0 closing_profit" name="profit" value="{{ $total_profit }}"></td>
                                    <td class="closing_total">{{ $total_profit + $fdr->fdr_balance }}</td>
                                </tr>
                            </table>
                            <div class="form-group">
                                <label for="closing_fee" class="form-label">হিসাব প্রত্যাহার ফি</label>
                                <input type="number" name="closing_fee" id="closing_fee" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="date" class="form-label">তারিখ</label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">নিস্পত্তি করুন</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div>
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
    {{-- @php
         $total_deposited = $fdr->deposit - $fdr->withdraw;
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
                         <table class="table table-sm table-borderless">
                             <tr>
                                 <th style="width: 20%">নাম</th>
                                 <td>:</td>
                                 <td colspan="4">{{ $fdr->member->name }}</td>
                             </tr>
                             <tr>
                                 <th style="width: 20%">হিসাব নং</th>
                                 <td>:</td>
                                 <td colspan="4">{{ $fdr->account_no }}</td>
                             </tr>
                             <tr>
                                 <th style="width: 20%">হিসাব শুরুর তারিখ</th>
                                 <td>:</td>
                                 <td>{{ date('d/m/Y',strtotime($fdr->date)) }}</td>
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
                             <tr>
                                 <td class="text-end "><input type="number" class="text-end total_deposited form-control"
                                                              name="total_deposited" value="{{ $total_deposited }}"
                                                              readonly></td>
                                 <td class="text-end "><input type="number" class="text-end profit form-control"
                                                              value="0" min="0" name="profit"></td>
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
                                 <th class="text-center">হিসাব প্রত্যাহার</th>
                                 <th class="text-center">জরিমানা</th>
                                 <th class="text-center">মোট টাকা</th>
                             </tr>
                             </thead>
                             <tbody>
                             <tr>
                                 <td class="text-end ">
                                     <input type="hidden" name="loan_id" value="{{ $loan?$loan->id:"" }}">
                                     <input type="number" class="text-end loan_balance form-control" name="loan_balance"
                                            value="{{ $loan_balance }}" readonly></td>
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
                         <input type="hidden" name="account_no" value="{{ $fdr->account_no }}">
                         <input type="hidden" name="member_id" value="{{ $fdr->member_id }}">
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
 --}}
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
                    <form action="{{ route('nominees.update',$nominee->id) }}" method="POST"
                          enctype="multipart/form-data">
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
                                                <input type="file" class="form-control" id="nominee_nid"
                                                       name="nominee_nid">
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
                                                       name="nominee_percentage"
                                                       value="{{ $nominee->nominee_percentage }}">
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
                                                       name="nominee_address1"
                                                       value="{{ $nominee->nominee_address1??'' }}">
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
                        <img class=" img-thumbnail" src="{{ asset('uploads/'.$fdr->member->photo) }}"
                             style="height: 100px" alt="">
                    </div>

                    <table class="table table-sm table-striped">
                        <tr>
                            <th>নামঃ</th>
                            <td class="ms-2">{{ $fdr->member->name }}</td>
                            <th>পিতার নামঃ</th>
                            <td class="ms-2">{{ $fdr->member->father_name }}</td>
                        </tr>
                        <tr>
                            <th>মাতার নামঃ</th>
                            <td class="ms-2">{{ $fdr->member->mother_name }}</td>
                            <th>জন্ম তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($fdr->member->birth_date)) }}</td>
                        </tr>
                        <tr>
                            <th>লিঙ্গঃ</th>
                            <td class="ms-2"> @if($fdr->member->gender=='male')
                                    পুরুষ
                                @else
                                    মহিলা
                                @endif</td>
                            <th>বৈবাহিক অবস্থাঃ</th>
                            <td class="ms-2">@if($fdr->member->marital_status=='married')
                                    বিবাহিত
                                @else
                                    অবিবাহিত
                                @endif</td>
                        </tr>
                        <tr>
                            <th>স্বামী/স্ত্রীর নামঃ</th>
                            <td class="ms-2">{{ $fdr->member->spouse_name??'' }}</td>
                            <th>মোবাইলঃ</th>
                            <td class="ms-2">{{ $fdr->member->phone }}</td>
                        </tr>
                        <tr>
                            <th>বর্তমান ঠিকানাঃ</th>
                            <td class="ms-2">{{ $fdr->member->present_address }}</td>
                            <th>স্থায়ী ঠিকানাঃ</th>
                            <td class="ms-2">{{ $fdr->member->permanent_address }}</td>
                        </tr>
                        <tr>
                            <th>জাতীয়তাঃ</th>
                            <td class="ms-2">{{ $fdr->member->nationality }}</td>
                        </tr>
                        <tr>
                            <th>এন আইডিঃ</th>
                            <td class="ms-2">{{ $fdr->member->nid_no }}</td>
                            <th>পেশাঃ</th>
                            <td class="ms-2">{{ $fdr->member->occupation }}</td>
                        </tr>
                        <tr>
                            <th>কর্মস্থলঃ</th>
                            <td class="ms-2">{{ $fdr->member->workplace }}</td>
                            <th>নিবন্ধন তারিখঃ</th>
                            <td class="ms-2">{{ date('d/m/Y',strtotime($fdr->member->join_date)) }}</td>
                        </tr>
                        <tr>
                            <th>এনআইডিঃ</th>
                            <td class="ms-2"><img class=" img-thumbnail" src="{{ asset('uploads/'.$fdr->member->nid) }}"
                                                  style="height: 100px" alt=""></td>
                            <th>স্বাক্ষরঃ</th>
                            <td class="ms-2"><img class=" img-thumbnail"
                                                  src="{{ asset('uploads/'.$fdr->member->signature) }}"
                                                  style="height: 100px" alt=""></td>
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
        var account_no = "{{ $fdr->account_no }}";

        calculate();
        $(document).on("input", "input", function () {
            calculate();
        })

        function calculate() {
            let withdraw = $("#closingForm input[name='withdraw']").val();
            let profit = $("#closingForm input[name='profit']").val();
            let closingTotal = parseInt(withdraw) + parseInt(profit);
            $(".closing_total").text(closingTotal);
        }

        loadData(account_no);

        function loadData(account_no) {
            $('#datatables').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('dataProfitCollections') }}",
                    data: {account_no: account_no}
                },
                columns: [
                    // columns according to JSON

                    {data: 'name'},
                    {data: 'account_no'},
                    {data: 'fdr_withdraw'},
                    {data: 'fdr_balance'},
                    {data: 'profit'},
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
                        url: "{{url('profit-collections')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            console.log(results)
                            if (results.success === true) {
                                $("#datatables").DataTable().destroy();
                                loadData(account_no);
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
                    window.location.href = "{{ url('daily-savings') }}/{{ $fdr->id }}";
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
                        if (results == "success") {
                            window.location.href = "{{ url('daily-savings') }}/{{ $fdr->id }}";
                        }
                    }
                });

            }, function (dismiss) {
                return false;
            })
        }

        $(".edit-nominee").on("click", function () {
            $("#modalEditNominee").modal("show");
        })

        $(document).on("click",".edit",function () {
            $("#modalFdrDeposit").modal("show");
            var account_no = $(this).data("account_no");
            var rate = $(this).data("profit_rate");
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
                url: "{{url('fdr-deposits')}}/" + id,
                data: formData,
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $("#modalFdrDeposit").modal("hide");
                    $this.attr('disabled', false).html($caption);
                    if (data.message === "success") {
                        location.reload();
                        $.NotificationApp.send("Success", "success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                    } else {
                        $.NotificationApp.send("Error", "error", "bottom-right", "rgba(0,0,0,0.2)", "error")
                    }
                }
            });
        })
    </script>
@endsection

