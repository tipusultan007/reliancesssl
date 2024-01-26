<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Multipurpse" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    <!-- Plugin css -->
    <link rel="stylesheet" href="{{asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.css')}}">
    <link href="{{asset('assets/vendor/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Datepicker css -->
    <link href="{{asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Theme Config Js -->
    <script src="{{asset('assets/js/hyper-config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app-saas.css')}}?v={{ date('YmdHis') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Select2 css -->

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
    </style>
</head>

<body>
<!-- Begin page -->
<div class="wrapper">


    <!-- ========== Topbar Start ========== -->
    @include('partials.topbar')
    <!-- ========== Topbar End ========== -->

    <!-- ========== Left Sidebar Start ========== -->
    @include('partials.sidemenu')
    <!-- ========== Left Sidebar End ========== -->


    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            @yield('content')
            <!-- container -->

        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        © <script>document.write(new Date().getFullYear())</script>  রিলায়েন্স শ্রমজীবী সমবায় সমিতি লিমিটেড।
                    </div>

                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- Vendor js -->
<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/moment.js')}}"></script>
<script src="{{asset('assets/vendor/select2/js/select2.min.js')}}"></script>
<!-- Bootstrap Datepicker js -->
<script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<!-- App js -->
<script src="{{asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('assets/js/app.min.js')}}"></script>
<script src="{{asset('assets/vendor/sweetalert2/sweetalert2.min.js')}}"></script>
<!--  Select2 Js -->

@yield('scripts')
</body>
</html>
