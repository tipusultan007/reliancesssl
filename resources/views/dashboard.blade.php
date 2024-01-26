@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-light" id="dash-daterange">
                                <span class="input-group-text bg-primary border-primary text-white">
                                                    <i class="mdi mdi-calendar-range font-13"></i>
                                                </span>
                            </div>
                            <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                            <a href="javascript: void(0);" class="btn btn-primary ms-1">
                                <i class="mdi mdi-filter-variant"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title">ড্যাশবোর্ড</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-info">
                    <div class="card-header pb-0 bg-info border-bottom">
                        <h5 class="text-light fw-bolder fs-3 mt-0" title="Number of Customers">সদস্য</h5>
                    </div>
                    <div class="card-body">

                        <h3 class="mb-1"> {{ $total_member }} </h3>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-primary">
                    <div class="card-header pb-0 border-bottom bg-primary d-flex justify-content-between">
                        <h3 class="text-light fw-bolder mt-0" title="Number of Orders">দৈনিক সঞ্চয়</h3>
                        <h5>{{ \App\Models\DailySavings::totalCounts() }}</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\DailySavings::totalBalance() }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-danger">
                    <div class="card-header pb-0 border-bottom bg-danger d-flex justify-content-between">
                        <h5 class="text-light fw-bolder fs-3 mt-0" title="Growth">মাসিক সঞ্চয় </h5>
                        <h5>{{ \App\Models\MonthlySaving::totalCounts() }}</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\MonthlySaving::totalBalance() }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-success">
                    <div class="card-header pb-0 border-bottom bg-success d-flex justify-content-between">
                        <h5 class="text-light fw-bolder fs-3 mt-0" title="Growth">FDR</h5>
                        <h5>{{ \App\Models\Fdr::totalCount() }}</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\Fdr::fdrBalance() }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-warning">
                    <div class="card-header pb-0 border-bottom bg-warning d-flex justify-content-between">
                        <h5 class="text-light fw-bolder fs-3 mt-0" title="Growth">দৈনিক ঋণ</h5>
                        <h5>{{ \App\Models\DailyLoan::totalCounts() }}</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\DailyLoan::totalBalance() }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->


            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-success-lighten">
                    <div class="card-header pb-0 border-bottom bg-success-lighten d-flex justify-content-between">
                        <h5 class="text-dark fw-bolder fs-3 mt-0" title="Growth">মাসিক ঋণ</h5>
                        <h5>{{ \App\Models\MonthlyLoan::totalCounts() }}</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\MonthlyLoan::totalBalance() }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
            <div class="col-sm-3">
                <div class="card widget-flat text-center bg-info">
                    <div class="card-header pb-0 border-bottom bg-info">
                        <h5 class="text-light fw-bolder fs-3 mt-0" title="Growth">মুনাফা উত্তোলন</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="mb-1">{{ \App\Models\FdrCollection::sum('profit') }}</h3>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
@endsection
