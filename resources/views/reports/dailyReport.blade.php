@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <h4>দৈনিক আয়/ব্যয়</h4>
        <div class="card">
            <div class="card-body">
                <form action="{{ url('daily-report') }}">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label" for="date1"> শুরুর তারিখ </label>
                            <div class="position-relative" id="datepicker1">
                                <input type="text" class="form-control date1" value="{{ date('d/m/Y') }}"
                                       data-provide="datepicker" data-date-autoclose="true"
                                       data-date-container="#datepicker1" required>
                                <input id="date1" name="date1" type="hidden" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label" for="date2"> শেষ তারিখ </label>
                            <div class="position-relative" id="datepicker2">
                                <input type="text" class="form-control date2" value="{{ date('d/m/Y') }}"
                                       data-provide="datepicker" data-date-autoclose="true"
                                       data-date-container="#datepicker2" required>
                                <input id="date2" name="date2" type="hidden" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button class="btn btn-success w-100" type="submit" id="submit">সাবমিট</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xxl-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Money History</h4>
                    </div>

                    <div class="card-body pt-0">
                        <div class="border border-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="font-18 mb-1">আয়</p>
                                    <h3 class="text-primary my-0">{{ $totalIncome }} টাকা</h3>
                                </div>
                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-success rounded-circle h3 my-0">
                                                        <i class="mdi mdi-arrow-up-bold-outline"></i>
                                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="border border-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="font-18 mb-1">ব্যয়</p>
                                    <h3 class="text-danger my-0">{{ $totalExpense }} টাকা</h3>
                                </div>
                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-danger rounded-circle h3 my-0">
                                                        <i class="mdi mdi-arrow-down-bold-outline"></i>
                                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0 bg-success">
                        <h4 class="card-title text-white">আয়</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            @foreach($data['income'] as $key =>$income)
                                @if($income>0)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        <td class="text-end">{{ $income }}</td>
                                    </tr>
                                @endif
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0 bg-danger">
                        <h4 class="card-title text-white">ব্যয়</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            @foreach($data['expense'] as $key=> $expense)
                                @if($expense>0)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        <td class="text-end">{{ $expense }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning pb-0">
                        <h4 class="card-title text-black">মাঠকর্মীদের আদায়</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead class="table-dark">
                            <tr>
                                <th>মাঠকর্মী'র নাম</th>
                                <th>সঞ্চয়</th>
                                <th>ঋণ</th>
                                <th>লভ্যাংশ</th>
                                <th>অন্যান্য</th>
                                <th class="text-end">সর্বমোট</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trx as $key=> $item)
                                @if($item['total']>0)
                                    <tr>
                                        <th>{{ $item['name'] }}</th>
                                        <td>{{ $item['savings'] }}</td>
                                        <td>{{ $item['loan_collection'] }}</td>
                                        <td>{{ $item['interest'] }}</td>
                                        <td>{{ $item['other'] }}</td>
                                        <td class="text-end">{{ $item['total'] }} টাকা</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
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
