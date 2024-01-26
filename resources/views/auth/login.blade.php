<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>লগ ইন | রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিমিটেড</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="{{asset('assets/js/hyper-config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">
<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header py-4 text-center bg-primary">
                        <a href="index.html">
                            <h4 class="text-white">
                                রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিমিটেড
                            </h4>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="phone" class="form-label">মোবাইল নং</label>
                                <input class="form-control" type="text" id="phone" name="phone" required="" placeholder="Enter your mobile no">
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('password.request') }}l" class="text-muted float-end"><small>Forgot your password?</small></a>
                                <label for="password" class="form-label">পাসওয়ার্ড</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>
                            </div>

                            <div class="mb-3 mb-0 text-center">
                                <button class="btn btn-primary" type="submit"> লগ ইন করুন </button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->


            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<footer class="footer footer-alt">
     <script>document.write(new Date().getFullYear())</script> © রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিমিটেড।
</footer>
<!-- Vendor js -->
<script src="{{asset('assets/js/vendor.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/js/app.min.js')}}"></script>

</body>
</html>

