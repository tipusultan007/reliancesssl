@extends('layouts.app')

@section('content')
    <style>
        .rotate-header {
            transform: rotate(-45deg);
        }

        .signature{
            display: none;
        }
        @media print {
            body {
                visibility: hidden;
            }

            table.salary-sheet, table.salary-sheet * {
                visibility: visible;
            }

            table.salary-sheet {
                position: absolute;
                left: 0;
                top: 0;
            }
            .signature{
                display: block;
            }
            .action {
                display: none;
            }
        }
    </style>
    @php
        $month = !empty(request('salary_month'))?request('salary_month'): date('Y-m');
    @endphp
    <div class="container-fluid my-3">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="div d-flex justify-content-between">
            <h2>কর্মচারী বেতন তালিকা</h2>
            <div>
                <form action="">
                    <div class="input-group">
                        <input type="month" class="form-control fw-bold" value="{{ $month }}" name="salary_month">
                        <button type="submit" class="btn btn-primary">সাবমিট</button>
                    </div>
                </form>
            </div>
            <div class="btn-group">
                <a href="{{ route('payrolls.create') }}" class="btn btn-success fw-bolder text-light mb-3">বেতন ফরম</a>
                <button class="btn btn-warning fw-bolder text-dark mb-3" onclick="printTable()">প্রিন্ট করুণ</button>
            </div>

        </div>
        <div class="card">
            <div class="card-body">

                <table class="table text-dark table-bordered salary-sheet">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="12">
                            <h2>রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড</h2>
                            <p>১ নং সাইড হিন্দু পাড়া, বন্দর, চট্টগ্রাম</p>
                            <h3><u>কর্মচারী বেতন শীট</u></h3>
                            <h4>{{ \Carbon\Carbon::parse($month)->translatedFormat('F-Y') }}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th class="">কর্মচারী</th>
                        <th class="">কার্য দিবস</th>
                        <th class="">বেসিক</th>
                        <th class="">উপস্থিত</th>
                        <th class="">ছুটি</th>
                        <th class="">অনুপস্থিত</th>
                        <th class="">প্রদেয় বেসিক</th>
                        <th class="">উপস্থিতি বোনাস</th>
                        <th class="">চা ভাতা</th>
                        <th class="">অন্যান্য বিল</th>
                        <th class="">নীট বেতন</th>
                        <th class="signature">স্বাক্ষর</th>
                        <th class="action">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->user->name }}</td>
                            <td>{{ $payroll->working_days }}</td>
                            <td>{{ $payroll->basic_salary }}</td>
                            <td>{{ $payroll->present_days}}</td>
                            <td>{{ $payroll->leave_days}}</td>
                            <td>{{ $payroll->absence_days}}</td>
                            <td>{{ $payroll->payable_basic}}</td>
                            <td>{{ $payroll->present_bonus}}</td>
                            <td>{{ $payroll->tea_allowance}}</td>
                            <td>{{ $payroll->other_bill}}</td>
                            <td>{{ $payroll->net_salary}}</td>
                            <td class="signature"></td>
                            <td class="action">
                                <div class="dropdown card-widgets">
                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        <i class="ri-more-fill"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <!-- item-->
                                        <a href="{{ route('payrolls.edit', $payroll->id) }}" class="dropdown-item"><i
                                                class="mdi mdi-pencil me-1"></i>এডিট</a>
                                        <!-- item-->
                                        <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST"
                                              class="mb-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this payroll record?')">
                                                <i class="mdi mdi-delete me-1"></i>ডিলেট
                                            </button>
                                        </form>

                                        <!-- item-->
                                    </div>
                                </div>


                            </td>
                        </tr>
                    @endforeach

                    <!-- Add more rows for other employees here -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@section('scripts')

    <script>
        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
            $(".alert").slideUp(500);
        });
        function printTable() {
            window.print();
        }
    </script>
@endsection
