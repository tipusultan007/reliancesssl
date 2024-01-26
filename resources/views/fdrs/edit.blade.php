@extends('layouts.master')

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
                    <h4 class="page-title">নতুন এফ.ডি.আর </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        @php
                            $str = $fdr->account_no;
                            $trimmed = trim($str,'FDR');
                        @endphp
                        <h4 class="header-title mb-3"> FDR আবেদন ফরম</h4>
                        <form class="needs-validation" action="{{ route('fdr.update',$fdr->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            @php
                                                $members = \App\Models\Member::select('id','name','father_name')->get();
                                            @endphp
                                            <label class="form-label" for="account_no">সদস্য</label>
                                            <select class="form-control select2" id="member_id" name="member_id" data-placeholder="Select" data-toggle="select2">
                                                <option value="">Select</option>
                                                @foreach($members as $item)
                                                    <option value="{{ $item->id }}" {{ $fdr->member_id == $item->id?"selected":"" }}>{{ $item->name }} - {{ $item->father_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="account_no">হিসাব নং</label>
                                                <input type="number" class="form-control" id="account_no" min="0" value="{{ intval($trimmed) }}" name="account_no">
                                                <div id="result"></div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="fdr_amount">FDR পরিমাণ</label>
                                                <input type="number" class="form-control" id="fdr_amount" name="fdr_amount" value="{{ $fdr->fdr_amount }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group">
                                                <label class="form-label" for="profit_rate">FDR ধরন</label>
                                                <select name="type" id="type" class="form-select select2">
                                                    <option value="monthly" {{ $fdr->type == "monthly"?"selected":"" }}>মাসিক মুনাফা ভিত্তিক</option>
                                                    <option value="interim" {{ $fdr->type == "interim"?"selected":"" }}>কালান্তিক মুনাফা ভিত্তিক</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-sm-12 mb-2">
                                            <div class="form-group " >
                                                <label class="form-label" for="date"> তারিখ </label>
                                                <input id="date" class="form-control" name="date" type="date" value="{{ $fdr->date }}">

                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-sm-12 mb-2">
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
                                        <input type="text" class="form-control" id="nominee_name" name="nominee_name" value="{{ $fdr->nominee->nominee_name??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_address">ঠিকানা</label>
                                        <input type="text" class="form-control" id="nominee_address" name="nominee_address" value="{{ $fdr->nominee->nominee_address??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_mobile">মোবাইল</label>
                                        <input type="text" class="form-control" id="nominee_mobile" name="nominee_mobile" value="{{ $fdr->nominee->nominee_mobile??"" }}">
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
                                        <input id="birth_date1" name="birth_date" class="form-control" type="date" value="{{ $fdr->nominee->birth_date??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_relation">সম্পর্ক </label>
                                        <input type="text" class="form-control" id="nominee_relation" name="nominee_relation" value="{{ $fdr->nominee->nominee_relation??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_percentage">অংশ</label>
                                        <input type="number" class="form-control" id="nominee_percentage" name="nominee_percentage" value="{{ $fdr->nominee->nominee_percentage??"" }}">
                                    </div>
                                </div>
                            </div>
                            <h4>নমিনী -০২</h4>
                            <div class="row mb-3 nominee">
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_name1">নাম</label>
                                        <input type="text" class="form-control" id="nominee_name1" name="nominee_name1" value="{{ $fdr->nominee->nominee_name1??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_address1">ঠিকানা</label>
                                        <input type="text" class="form-control" id="nominee_address1" name="nominee_address1" value="{{ $fdr->nominee->nominee_address1??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_mobile1">মোবাইল</label>
                                        <input type="text" class="form-control" id="nominee_mobile1" name="nominee_mobile1" value="{{ $fdr->nominee->nominee_mobile1 ??""}}">
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
                                        <input id="birth_date2" name="birth_date1" class="form-control" type="date" value="{{ $fdr->nominee->birth_date1??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_relation1">সম্পর্ক </label>
                                        <input type="text" class="form-control" id="nominee_relation1" name="nominee_relation1" value="{{ $fdr->nominee->nominee_relation1??"" }}">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-sm-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nominee_percentage1">অংশ</label>
                                        <input type="number" class="form-control" id="nominee_percentage1" name="nominee_percentage1" value="{{ $fdr->nominee->nominee_percentage1??"" }}">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" id="btn-submit">সাবমিট করুন</button>
                            <button type="button" class="btn btn-danger">Reset</button>
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
        $("#type").on("change",function () {
            var type = $(this).val();

            if (type === "interim")
            {
                $(".monthly_profit_rate").hide();
            }else {
                $(".monthly_profit_rate").show();
            }
        })

        /*$("#btn-submit").on("click",function () {
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
                url: "{{ route('fdr.store') }}",
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
                        window.location.href = "{{ url('fdr') }}";
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
        })*/
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
                    url: "{{ url('existFdrAccount') }}/" + account_digit,
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


