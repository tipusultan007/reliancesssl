@extends('layouts.master')
@section('vendor-css')
    <!-- Datatables css -->
    <link href="{{asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />


@endsection
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Form Wizard</li>
                        </ol>
                    </div>
                    <h4 class="page-title">নতুন মাসিক সঞ্চয়</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
@php
    $dailySavings = \App\Models\MonthlySaving::latest('account_no')->first('account_no');
    if ($dailySavings)
        {
            $str = $dailySavings->account_no;
    $trimmed = trim($str,'DPS');
    $number = $trimmed+1;
        }else{
        $number = 1;
        }
    @endphp
                        <h4 class="header-title mb-3"> সঞ্চয় আবেদন ফরম</h4>
                            {{--action="{{ route('monthly-savings.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);"  --}}
                        <form class="needs-validation" >
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xl-12 col-sm-12 mb-2">
                                                    @php
                                                        $members = \App\Models\Member::all();
                                                    @endphp
                                                    <label class="form-label" for="account_no">সদস্য</label>
                                                    <select class="form-control select2" id="member_id" name="member_id"
                                                            data-placeholder="Select"
                                                            data-toggle="select2"
                                                    data-allow-clear="on">
                                                        <option value="">Select</option>
                                                        @foreach($members as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->father_name }}</option>
                                                        @endforeach
                                                    </select>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="account_no">হিসাব নং</label>
                                                <input type="number" class="form-control" id="account_no" min="0" value="{{ $number }}" name="account_no">
                                            <div id="result"></div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="monthly_amount">মাসিক কিস্তির পরিমাণ (টাকা)</label>
                                                {{--<select class="form-control select2" id="monthly_amount" name="monthly_amount" data-placeholder="Select" data-toggle="select2">
                                                    <option value="100">১০০ টাকা</option>
                                                    <option value="200">২০০ টাকা</option>
                                                    <option value="300">৩০০ টাকা</option>
                                                    <option value="400">৪০০ টাকা</option>
                                                    <option value="500">৫০০ টাকা</option>
                                                    <option value="1000">১০০০ টাকা</option>
                                                    <option value="1500">১৫০০ টাকা</option>
                                                    <option value="2000">২০০০ টাকা</option>
                                                </select> --}}
                                                <input type="number" class="form-control" name="monthly_amount">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="duration">প্রকল্প মেয়াদ</label>
                                                <select class="form-control select2" id="duration" name="duration" data-placeholder="Select" data-toggle="select2">
                                                    <option value="1">১ বছর</option>
                                                    <option value="2">২ বছর</option>
                                                    <option value="3">৩ বছর</option>
                                                    <option value="4">৪ বছর</option>
                                                    <option value="5">৫ বছর</option>
                                                    <option value="6">৬ বছর</option>
                                                    <option value="7">৭ বছর</option>
                                                    <option value="8">৮ বছর</option>
                                                    <option value="9">৯ বছর</option>
                                                    <option value="10">১০ বছর</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group " >
                                                <label class="form-label" for="date"> তারিখ </label>
                                                <div class="position-relative" id="datepicker1">
                                                    <input id="date" name="date" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="notes">মন্তব্য</label>
                                                <input type="text" class="form-control" id="notes" name="notes">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="header-title mt-3 mb-2"> মনোনীত ব্যক্তি/ব্যক্তিবর্গের তথ্য</h4>
                                </div>
                            </div>
                            <h4>নমিনী -০১</h4>
                            <div class="row mb-3 nominee">
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_name">নাম</label>
                                            <input type="text" class="form-control" id="nominee_name" name="nominee_name" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_address">ঠিকানা</label>
                                            <input type="text" class="form-control" id="nominee_address" name="nominee_address" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_mobile">মোবাইল</label>
                                            <input type="text" class="form-control" id="nominee_mobile" name="nominee_mobile" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_photo">নমিনী ছবি</label>
                                        <input type="file" class="form-control" id="nominee_photo" name="nominee_photo">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_nid">এন আই ডি</label>
                                            <input type="file" class="form-control" id="nominee_nid" name="nominee_nid">
                                    </div>
                                </div>

                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group" >
                                        <label class="form-label" for="birth_date1">জন্ম তারিখ </label>
                                        <div class="position-relative" id="datepicker2">
                                            <input id="birth_date1" name="birth_date" type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_relation">সম্পর্ক </label>
                                            <input type="text" class="form-control" id="nominee_relation" name="nominee_relation" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_percentage">অংশ</label>
                                        <input type="number" class="form-control" id="nominee_percentage" name="nominee_percentage" value="">
                                    </div>
                                </div>
                            </div>
                            <h4>নমিনী -০২</h4>
                            <div class="row mb-3 nominee">
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_name1">নাম</label>
                                        <input type="text" class="form-control" id="nominee_name1" name="nominee_name1" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_address1">ঠিকানা</label>
                                        <input type="text" class="form-control" id="nominee_address1" name="nominee_address1" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_mobile1">মোবাইল</label>
                                        <input type="text" class="form-control" id="nominee_mobile1" name="nominee_mobile1" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_photo1">নমিনী ছবি</label>
                                        <input type="file" class="form-control" id="nominee_photo1" name="nominee_photo1">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_nid1">এন আই ডি</label>
                                        <input type="file" class="form-control" id="nominee_nid1" name="nominee_nid1">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group" >
                                        <label class="form-label" for="birth_date2">জন্ম তারিখ </label>
                                        <div class="position-relative" id="datepicker3">
                                            <input id="birth_date2" name="birth_date1" type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_relation1">সম্পর্ক </label>
                                        <input type="text" class="form-control" id="nominee_relation1" name="nominee_relation1" value="">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_percentage1">অংশ</label>
                                        <input type="number" class="form-control" id="nominee_percentage1" name="nominee_percentage1" value="">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" id="btn-submit" type="button">সাবমিট করুন</button>
                            <button type="button" class="btn btn-danger" onclick="resetForm(this.form);">Reset</button>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-center text-white">সদস্য তথ্য</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center avatar">

                        </p>
                        <table class="table table-sm details">

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
@section('scripts')

    <!-- Datatables js -->
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

    <script src="{{asset('assets/js/pages/demo.toastr.js')}}"></script>
    <script>
        $('.date')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#date').val(e.format('yyyy-mm-dd'));
            });

        $('.birth_date1')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#birth_date1').val(e.format('yyyy-mm-dd'));
            });
        $('.birth_date2')
            .datepicker({ format: 'dd/mm/yyyy' })
            .on('changeDate', function(e){
                $('#birth_date2').val(e.format('yyyy-mm-dd'));
            });
        $("#btn-submit").on("click",function () {
            var $this = $("#btn-submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            const formData = new FormData();
            var x = $("form").serializeArray();
            $.each(x, function(i, field){
                formData.append(field.name, field.value);
            });
            formData.append('nominee_photo',$("#nominee_photo")[0].files[0]);
            formData.append('nominee_photo1',$("#nominee_photo1")[0].files[0]);
            formData.append('nominee_nid',$("#nominee_nid")[0].files[0]);
            formData.append('nominee_nid1',$("#nominee_nid1")[0].files[0]);

            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                enctype: 'multipart/form-data',
                url: "{{ route('monthly-savings.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                    if (data.error)
                    {
                        $.NotificationApp.send("Error",data.error[0],"bottom-right","rgba(0,0,0,0.2)","error")
                    }else {
                        $.NotificationApp.send("Success",data.success,"bottom-right","rgba(0,0,0,0.2)","success")
                        window.location.href = "{{ url('monthly-savings') }}";
                        $("form").trigger('reset');
                    }
                    //console.log(data)
                    //$(".spinner").hide();

                   // window.location.href = "{{ url('monthly-savings') }}"
                    //$.NotificationApp.send("Message","Data submission success","bottom-right","rgba(0,0,0,0.2)","success")
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error","Data submission failed","rgba(0,0,0,0.2)","error")
                }
            })
        })

        $("#member_id").on("select2:select",function (e) {
            let id = e.params.data.id;
            let path = "{{ asset('uploads') }}";
            $(".details").empty();
            $(".avatar").empty();
            $.ajax({
                url: "{{ url('get-member') }}/"+id,
                dataType: "json",
                success: function (data) {

                    if (data.member.photo !="") {
                        $(".avatar").append(`
                              <img src="${path}/${data.member.photo}" alt="image" class="img-fluid avatar-md">
                    `);
                    }
                    $('.details').append(`
                    <tr>
<td><b> নামঃ </b></td>  <td>${data.member.name}</td>
</tr>
<tr>
<td><b> মোবাইলঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>
<tr>
<td><b> পিতার নামঃ </b></td>  <td>${data.member.father_name}</td>
</tr>
<tr>
<td><b> এন আইডিঃ </b></td>  <td>${data.member.nid_no}</td>
</tr>                    `);

                    if (data.daily_savings>0)
                    {
                        $('.details').append(`
                    <tr>
<td><b> দৈনিক সঞ্চয়ঃ </b></td>  <td>${data.daily_savings}</td>
</tr>
                   `);
                    }

                    if (data.daily_loans>0)
                    {
                        $('.details').append(`
                    <tr>
<td><b> দৈনিক ঋনঃ </b></td>  <td>${data.daily_loans}</td>
</tr>
                   `);
                    }
                }
            })
        })


        var checkForm = function(form) { /* Submit button was clicked */

            console.log(hi);
            form.myButton.disabled = true;
            form.myButton.value = "Please wait...";
            return true;
        };

        var resetForm = function(form) { /* Reset button was clicked */
            form.myButton.disabled = false;
            form.myButton.value = "Submit";
        };



        $("#account_no").on("change", function () {
            // Print entered value in a div box
            let account_digit = $(this).val();
            if ($(this).val() != "") {
                $("#result").empty();
                $.ajax({
                    url: "{{ url('existMonthlyAccount') }}/" + account_digit,
                    success: function (data) {
                        console.log(data)
                        if (data == 1) {
                            $("#result").removeClass("text-success");
                            $("#result").addClass("text-danger");
                            $("#result").text("হিসাব নম্বরটি ব্যবহার হয়েছে। অন্য নম্বর ব্যবহার করুন।")
                        } else {
                            $("#result").removeClass("text-danger");
                            $("#result").addClass("text-success");
                            $("#result").text("হিসাব নম্বরটি ব্যবহার করা যাবে।")
                        }
                    }
                })
            } else {
                $("#result").removeClass("text-success");
                $("#result").addClass("text-danger");
                $("#result").text("হিসাব নম্বর লিখুন।")
            }

        });
    </script>



@endsection


