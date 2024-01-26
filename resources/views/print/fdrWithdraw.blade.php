<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিমিটেড</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Multipurpse" name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    <script src="{{asset('assets/js/hyper-config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style"/>
    @yield('vendor-css')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__clear {
            cursor: pointer;
            float: right;
            font-weight: bold;
            height: 37px;
            width: 35px;
            margin-right: 20px;
            padding: 0 0px;
        }

        table.u-details th, table.u-details td {
            padding: 0;
        }
    </style>
</head>

<body onload="printpage()">
<!-- Begin page -->
<div class="wrapper">
    <div class="content">
        <div id="printDetails">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">FDR উত্তোলন</h4>
                </div>
                <div class="card-body">
                    
                    <table class="w-50 mx-auto">
                        <tr>
                            <td>নাম</td>
                            <td>{{ $data->member->name }}</td>
                        </tr>
                        <tr>
                            <td>হিসাব নং</td>
                            <td>{{ $data->fdr->account_no }}</td>
                        </tr>
                        <tr>
                            <td>তারিখ</td>
                            <td>{{ date('d/m/Y',strtotime($data->date)) }}</td>
                        </tr>
                    </table>
                    <table style="width: 50%;margin: 0 auto">

                            <tr>
                                <td style="border-bottom: 1px dashed #ccc"> উত্তোলন</td>
                                <td style="width: 100px;border: 1px solid #ccc; padding-right: 10px"
                                    class="text-end"> {{ $data->amount }}</td>
                            </tr>


                            <tr>
                                <td style="border-bottom: 1px dashed #ccc"> অবশিষ্ট জমা</td>
                                <td style="width: 100px;border: 1px solid #ccc; padding-right: 10px" class="text-end">
                                    {{ $data->remain }}
                                </td>
                            </tr>



                        <tr>
                            <td style="padding-right: 10px;text-align: right;font-weight: bold"> মোট</td>
                            <td style="width: 100px;border: 1px solid #ccc; padding-right: 10px;font-weight: bold"
                                class="text-end"> {{ $data->amount }}
                            </td>
                        </tr>
                    </table>
                    <div class="w-50 mx-auto">
                        <p class="mb-1">আদায়কারী: {{ $data->user->name }}</p>
                        <p class="mb-1">TRX ID: {{ $data->trx_id }}</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    ©
                    <script>document.write(new Date().getFullYear())</script>
                    রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড।
                </div>

            </div>
        </div>
    </footer>
    <!-- end Footer -->

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->
<script>

    function printpage() {
        window.print();
    }
</script>
</body>
</html>
