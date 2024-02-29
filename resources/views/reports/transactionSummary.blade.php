@extends('layouts.master')
@section('content')
    <style>
        .table caption {
            caption-side: top;
            text-align: center;
            font-weight: 700;
            color: #000;
        }

        /* Styles for the loader spinner */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite; /* Animation */
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        table td {
            font-size: 14px;
        }
    </style>

    <h3>আয়-ব্যয় রিপোর্ট</h3>
    <div class="card">
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label" for="date1"> শুরুর তারিখ </label>
                        <div class="position-relative" id="datepicker1">
                            <input id="date1" name="date1" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label" for="date2"> শেষ তারিখ </label>
                        <div class="position-relative" id="datepicker2">
                            <input id="date2" name="date2" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button class="btn btn-success w-100" type="button" id="submit">সাবমিট</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Tables for different types -->
    <div class="container-fluid my-3" id="tablesContainer">
        <div class="row gx-2">
            @foreach ($categoryGroups as $type => $categories)
                @php
                    if ($type=="income")
                        {
                            $title = "নগদ গ্রহণ সমূহ";
                            $caption = "নগদ গ্রহণ";
                        }elseif ($type=="expense"){
                            $title = "নগদ প্রদান সমূহ";
                            $caption = "নগদ প্রদান";
                        }elseif ($type=="office_income"){
                            $title = "আয় সমূহ";
                            $caption = "আয়";
                        }else{
                            $title = "ব্যয় সমূহ";
                            $caption = "ব্যয়";
                        }
                @endphp
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm table-bordered" id="{{ $type }}Table">
                                <caption>{{ $caption }}</caption>
                                <thead>
                                    <tr>
                                        <th class="text-dark">{{ $title }}</th>
                                        <th class="text-end text-dark">টাকা</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="loader"></div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            report();
            function report(date1='',date2='') {
                const tablesContainer = $('#tablesContainer');
                const loader = $('.loader');
                const url = '{{url('data-transaction-summary')}}'; // Adjust the URL

                loader.show();

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    data: {date1: date1,date2: date2},
                    success: function (data) {
                        loader.hide();

                        const categoryGroups = data.categoryGroups;
                        const categoriesWithSum = data.categoriesWithSum;
                        const totalSum = data.totalSum;

                        Object.keys(categoryGroups).forEach(function (type) {
                            const tableId = type + 'Table'; // Construct table ID
                            const table = $('#' + tableId);
                            const tableBody = $('#' + tableId + ' tbody'); // Select table body
                            const categories = categoryGroups[type];

                            categories.forEach(function (category) {
                                const sum = categoriesWithSum[category.id] || 0;

                                // Only append if sum is greater than 0
                                if (sum > 0) {
                                    const row = $('<tr>');
                                    const categoryNameCell = $('<td>').text(category.name);
                                    const categorySumCell = $('<td class="text-end">').text(sum.toFixed(2));

                                    row.append(categoryNameCell);
                                    row.append(categorySumCell);
                                    tableBody.append(row);
                                }
                            });

                            // Calculate and display sum in the footer
                            const footerRow = $('<tr>');
                            const footerLabelCell = $('<td class="text-end text-dark fw-bold">').text('সর্বমোট');
                            const footerSumCell = $('<td class="text-end fw-bold text-dark">').text(tableBody.find('td:nth-child(2)').toArray().reduce((acc, cell) => acc + parseFloat(cell.textContent), 0));

                            footerRow.append(footerLabelCell);
                            footerRow.append(footerSumCell);
                            table.find('tfoot').append(footerRow);
                        });

                    },
                    error: function (error) {
                        loader.hide();
                        console.error('Error fetching data:', error);
                    }
                });
            }

            $("#submit").on("click",function () {
                let date1 = $("#date1").val();
                let date2 = $("#date2").val();
                $("tbody").empty();
                $("tfoot").empty();
                report(date1, date2);
            })
        });
    </script>
@endsection
