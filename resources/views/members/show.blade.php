@extends('layouts.master')
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
$totalDaily = $member->daily->where('status','active')->sum('total');
$totalMonthly = $member->monthly->where('status','active')->sum('total');
$totalDailyLoan = $member->dailyLoan->where('status','active')->sum('balance');
$totalMonthlyLoan = $member->monthlyLoan->where('status','active')->sum('balance');
$fdrBalance = $member->fdr->sum('fdr_balance');
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
                                            <img src="{{ asset('storage') }}/{{ $member->photo??'' }}" alt="" class="rounded-circle img-thumbnail">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <h4 class="mt-1 mb-1 text-white">{{ $member->name }}</h4>
                                            <p class="font-13 text-white-50"> {{ $member->phone }}</p>

                                            <ul class="mb-0 list-inline text-light">
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $totalDaily + $totalMonthly }} টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">মোট সঞ্চয়</p>
                                                </li>
                                                <li class="list-inline-item me-3">
                                                    <h5 class="mb-1 text-white">{{ $fdrBalance }} টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">FDR সঞ্চয়</p>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5 class="mb-1 text-white">{{ $totalDailyLoan + $totalMonthlyLoan }} টাকা</h5>
                                                    <p class="mb-0 font-13 text-white-50">অবশিষ্ট ঋণ</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            <div class="col-sm-4">
                                <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                    <a href="{{ route('members.edit',$member->id) }}" class="btn btn-light">
                                        <i class="mdi mdi-account-edit me-1"></i> Edit Profile
                                    </a>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row -->

                    </div> <!-- end card-body/ profile-user-box-->
                </div><!--end profile/ card -->
            </div> <!-- end col-->
        </div>
        <!-- end row -->


        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-striped">
                            <tr>
                                <th>নামঃ</th>
                                <td class="ms-2">{{ $member->name }}</td>
                                <th>পিতার নামঃ</th>
                                <td class="ms-2">{{ $member->father_name }}</td>
                            </tr>
                            <tr>
                                <th>মাতার নামঃ</th>
                                <td class="ms-2">{{ $member->mother_name }}</td>
                                <th>জন্ম তারিখঃ</th>
                                <td class="ms-2">{{ date('d/m/Y',strtotime($member->birth_date)) }}</td>
                            </tr>
                            <tr>
                                <th>লিঙ্গঃ</th>
                                <td class="ms-2"> @if($member->gender=='male')
                                        পুরুষ
                                    @else
                                        মহিলা
                                    @endif</td>
                                <th>বৈবাহিক অবস্থাঃ</th>
                                <td class="ms-2">@if($member->marital_status=='married')
                                        বিবাহিত
                                    @else
                                        অবিবাহিত
                                    @endif</td>
                            </tr>
                            <tr>
                                <th>স্বামী/স্ত্রীর নামঃ</th>
                                <td class="ms-2">{{ $member->spouse_name??'' }}</td>
                                <th>মোবাইলঃ</th>
                                <td class="ms-2">{{ $member->phone }}</td>
                            </tr>
                            <tr>
                                <th>বর্তমান ঠিকানাঃ</th>
                                <td class="ms-2">{{ $member->present_address }}</td>
                                <th>স্থায়ী ঠিকানাঃ</th>
                                <td class="ms-2">{{ $member->permanent_address }}</td>
                            </tr>
                            <tr>
                                <th>জাতীয়তাঃ</th>
                                <td class="ms-2" colspan="3">{{ $member->nationality }}</td>
                            </tr>
                            <tr>
                                <th>এন আইডিঃ</th>
                                <td class="ms-2">{{ $member->nid_no }}</td>
                                <th>পেশাঃ</th>
                                <td class="ms-2">{{ $member->occupation }}</td>
                            </tr>
                            <tr>
                                <th>কর্মস্থলঃ</th>
                                <td class="ms-2">{{ $member->workplace }}</td>
                                <th>নিবন্ধন তারিখঃ</th>
                                <td class="ms-2">{{ date('d/m/Y',strtotime($member->join_date)) }}</td>
                            </tr>
                            <tr>
                                <th>এনআইডিঃ </th>
                                <td class="ms-2"><a data-lightbox="nid" href="{{ asset('storage/'.$member->nid) }}"><img  class=" img-thumbnail" src="{{ asset('storage/'.$member->nid) }}" style="height: 100px" alt=""></a></td>
                                <th>স্বাক্ষরঃ</th>
                                <td class="ms-2"><a data-lightbox="signature" href="{{ asset('storage/'.$member->signature) }}"><img  class=" img-thumbnail" src="{{ asset('storage/'.$member->signature) }}" style="height: 100px" alt=""></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h4 class="header-title text-dark">সকল সঞ্চয়</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-centered mb-0">
                                <thead>
                                <tr>
                                    <th>হিসাব নং</th>
                                    <th>হিসাবের ধরন</th>
                                    <th>মোট জমা</th>
                                    <th>স্ট্যাটাস</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $item['account_no'] }}</td>
                                        <td>
                                        @if($item['type']=='daily')
                                                <span class="badge bg-info">দৈনিক সঞ্চয়</span>
                                            @else
                                                <span class="badge bg-warning text-black">মাসিক সঞ্চয়</span>
                                        @endif
                                        </td>
                                        <td><strong>{{ $item['balance'] }} টাকা</strong></td>
                                        <td>
                                            @if($item['status']=='active')
                                                <span class="badge bg-success">চলমান</span>
                                            @else
                                                <span class="badge bg-danger">বন্ধ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item['type']=='daily')
                                                <a href="{{ route('daily-savings.show',$item['id']) }}" class="btn btn-sm btn-secondary">বিস্তারিত</a>
                                            @else
                                                <a href="{{ route('monthly-savings.show',$item['id']) }}" class="btn btn-sm btn-secondary">বিস্তারিত</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div> <!-- end table responsive-->
                    </div> <!-- end col-->
                </div> <!-- end row-->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h4 class="header-title text-white">সকল ঋণ</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-centered mb-0">
                                <thead>
                                <tr>
                                    <th>হিসাব নং</th>
                                    <th>ঋণের ধরন</th>
                                    <th>অবশিষ্ট ঋণ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataLoan as $key => $item)
                                    <tr>
                                        <td>{{ $item['account_no'] }}</td>
                                        <td>
                                            @if($item['type']=='daily')
                                                <span class="badge bg-info">দৈনিক ঋণ</span>
                                            @else
                                                <span class="badge bg-warning text-black">মাসিক ঋণ</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $item['balance'] }} টাকা</strong></td>
                                        <td>
                                            @if($item['status']=='active')
                                                <span class="badge bg-success">চলমান</span>
                                            @else
                                                <span class="badge bg-danger">পরিশোধ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item['type']=='daily')
                                                <a href="{{ route('daily-loans.show',$item['id']) }}" class="btn btn-sm btn-secondary">বিস্তারিত</a>
                                            @else
                                                <a href="{{ route('monthly-loans.show',$item['id']) }}" class="btn btn-sm btn-secondary">বিস্তারিত</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end table responsive-->
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div>
            <!-- end col -->

        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
@section('scripts')
    <!-- Chart.js -->
    <script src="{{asset('assets/vendor/chart.js/chart.min.js')}}"></script>
    <!-- Profile Demo App js -->
    <script src="{{asset('assets/js/pages/demo.profile.js')}}"></script>

@endsection

