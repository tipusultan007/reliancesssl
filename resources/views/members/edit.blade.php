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
                    <h4 class="page-title">সদস্যের তথ্য আপডেট</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-3"> তথ্য আপডেট ফরম</h4>
                        <form id="accountForm" class="form-horizontal">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="id" id="id" value="{{ $member->id }}">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="" class="form-label">নাম</label>
                                    <input type="text" id="name" name="name" value="{{ $member->name }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="father_name"> পিতার/স্বামীর নাম</label>
                                    <input type="text" id="father_name" name="father_name"
                                           value="{{ $member->father_name }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="mother_name">মাতার নাম </label>
                                    <input type="text" id="mother_name" name="mother_name"
                                           class="form-control" value="{{ $member->mother_name }}">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="birth_date"> জন্মতারিখ </label>
                                    <input id="birth_date" name="birth_date" type="date"
                                           value="{{ $member->birth_date }}" class="form-control">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="gender">লিঙ্গ </label>
                                    <select class="form-control select2" id="gender" name="gender"
                                            data-toggle="select2" data-placeholder="Select">
                                        <option value="male" {{ $member->gender== 'male'? "selected":"" }}>পুরুষ
                                        </option>
                                        <option value="female" {{ $member->gender== 'female'? "selected":"" }}>মহিলা
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="marital_status">বৈবাহিক অবস্থা </label>
                                    <select class="form-control select2" id="marital_status"
                                            name="marital_status" data-placeholder="Select"
                                            data-toggle="select2">
                                        <option
                                            value="married" {{ $member->marital_status == "married"?"selected":"" }}>
                                            বিবাহিত
                                        </option>
                                        <option
                                            value="unmarried" {{ $member->marital_status == "unmarried"?"selected":"" }}>
                                            অবিবাহিত
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="spouse_name"> স্বামী/স্ত্রীর নাম </label>
                                    <input type="text" id="spouse_name" name="spouse_name"
                                           class="form-control" value="{{ $member->spouse_name }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="phone"> মোবাইল নং </label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                           value="{{ $member->phone }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="occupation"> পেশা </label>
                                    <input type="text" id="occupation" name="occupation"
                                           class="form-control" value="{{ $member->occupation }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="present_address"> বর্তমান ঠিকানা </label>
                                    <input type="text" id="present_address" name="present_address"
                                           class="form-control" value="{{ $member->present_address }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="permanent_address"> স্থায়ী ঠিকানা</label>
                                    <input type="text" id="permanent_address" name="permanent_address"
                                           class="form-control" value="{{ $member->permanent_address }}">
                                </div>
                                <input type="hidden" id="nationality" name="nationality"
                                       value="Bangladeshi">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="photo"> সদস্যের ছবি </label>
                                    <input type="file" id="photo" name="photo" class="form-control">
                                    @if(isset($member) && $member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}" alt="Preview"
                                             style="max-width: 100px; max-height: 100px;">
                                        <a href="javascript:;" class="btn btn-outline-danger btn-sm deleteButton"><i class="uil uil-trash"></i></a>
                                    @endif
                                </div>

                                <!-- Signature -->
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="signature"> স্বাক্ষর </label>
                                    <input type="file" id="signature" name="signature" class="form-control">
                                    @if(isset($member) && $member->signature)
                                        <img src="{{ asset('storage/' . $member->signature) }}" alt="Preview"
                                             style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger deleteButton">Delete</button>
                                    @endif
                                </div>

                                <!-- NID Number -->
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="nid_no"> জাতীয় পরিচয়পত্র নং </label>
                                    <input type="number" id="nid_no" name="nid_no" class="form-control"
                                           value="{{ old('nid_no', isset($member) ? $member->nid_no : '') }}">
                                </div>

                                <!-- NID Image -->
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="nid"> জাতীয় পরিচয়পত্র (ছবি) </label>
                                    <input type="file" id="nid" name="nid" class="form-control">
                                    @if(isset($member) && $member->nid)
                                        <img src="{{ asset('storage/' . $member->nid) }}" alt="Preview"
                                             style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger deleteButton">Delete</button>
                                    @endif
                                </div>

                                <!-- Birth ID -->
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="birth_id"> জন্মসনদ </label>
                                    <input type="file" name="birth_id" id="birth_id" class="form-control">
                                    @if(isset($member) && $member->birth_id)
                                        <img src="{{ asset('storage/' . $member->birth_id) }}" alt="Preview"
                                             style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger deleteButton">Delete</button>
                                    @endif
                                </div>

                                <!-- Join Date -->
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="join_date"> তারিখ </label>
                                    <input id="join_date" name="join_date"
                                           value="{{ old('join_date', isset($member) ? $member->join_date : date('Y-m-d')) }}"
                                           type="date" class="form-control">
                                </div>
                            </div> <!-- end row -->
                            <hr>
                            <h3>পরিচয়দানকারী</h3>
                            <hr>
                            @php
                                $members = \App\Models\Member::all();
                            @endphp
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="exist_member_id">সকল সদস্য</label>
                                    <select class="form-control select2" id="exist_member_id"
                                            name="exist_member_id" data-allow-clear="on"
                                            data-placeholder="Select" data-toggle="select2">
                                        <option value="">Select</option>
                                        @foreach($members as $item)
                                            <option value="{{ $item->id }}" >{{ $item->name }}
                                                - {{ $item->father_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="introducer_father">পিতার
                                        নাম</label>
                                    <input type="text" id="introducer_father"
                                           name="introducer_father" class="form-control" value="{{ $introducer->introducer_father }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="introducer_mobile">
                                        মোবাইল নং </label>
                                    <input type="text" id="introducer_mobile"
                                           name="introducer_mobile" class="form-control" value="{{ $introducer->introducer_mobile }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="introducer_name">পূর্ণনাম</label>
                                    <input type="text" id="introducer_name" name="introducer_name"
                                           class="form-control" value="{{ $introducer->introducer_name }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="introducer_address">
                                        পূর্ণ ঠিকানা </label>
                                    <input type="text" id="introducer_address"
                                           name="introducer_address" class="form-control" value="{{ $introducer->introducer_address }}">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label" for="introducer_signature"> স্বাক্ষর </label>
                                    <input type="file" id="introducer_signature" name="introducer_signature" class="form-control">
                                    @if(isset($member) && $member->introducer_signature)
                                        <img src="{{ asset('storage/' . $member->introducer_signature) }}" alt="Preview" style="max-width: 100px; max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger deleteButton">Delete</button>
                                    @endif
                                </div>

                                <div class="col-md-12 my-3 d-flex justify-content-center">
                                    <button class="btn btn-primary w-25 fw-bolder" type="button" id="btn-submit">
                                        সাবমিট
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
@endsection
@section('scripts')

    <!-- Bootstrap Wizard Form js -->
    <script src="{{asset('assets/vendor/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>

    <!-- Wizard Form Demo js -->
    <script src="{{asset('assets/js/pages/demo.form-wizard.js')}}"></script>
    <!-- Toastr Demo js -->
    <script src="{{asset('assets/js/pages/demo.toastr.js')}}"></script>
    <script>
        $('.birth_date')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#birth_date').val(e.format('yyyy-mm-dd'));
            });
        $('.join_date')
            .datepicker({format: 'dd/mm/yyyy'})
            .on('changeDate', function (e) {
                $('#join_date').val(e.format('yyyy-mm-dd'));
            });

        $("#btn-submit").on("click", function () {
            var id = '{{ $member->id }}';
            var $this = $("#btn-submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            const formData = new FormData();
            var x = $("form").serializeArray();
            $.each(x, function (i, field) {
                formData.append(field.name, field.value);
            });
            formData.append('nid', $("#nid")[0].files[0]);
            formData.append('birth_id', $("#birth_id")[0].files[0]);
            formData.append('photo', $("#photo")[0].files[0]);
            formData.append('signature', $("#signature")[0].files[0]);
            formData.append('introducer_signature', $("#introducer_signature")[0].files[0]);
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                enctype: 'multipart/form-data',
                url: "{{ url('members') }}/" + id,
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                    //$(".spinner").hide();
                    $("form").trigger('reset');
                    $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")

                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error", "Data submission failed", "rgba(0,0,0,0.2)", "error")
                }
            })
        })

        $("#exist_member_id").on("select2:select", function (e) {
            let id = e.params.data.id;
            $.ajax({
                url: "{{ url('get-member') }}/" + id,
                dataType: "JSON",
                success: function (data) {
                    $("#introducer_name").val(data.member.name);
                    $("#introducer_father").val(data.member.father_name);
                    $("#introducer_mobile").val(data.member.phone);
                    $("#introducer_address").val(data.member.present_address);
                }
            })
        })
        $("#exist_member_id").on("select2:clear", function (e) {
            $("#introducer_name").val("");
            $("#introducer_father").val("");
            $("#introducer_mobile").val("");
            $("#introducer_address").val("");
        })

        $('input[type="file"]').on('change', function () {
            var input = this;
            var preview = $(this).siblings('img');
            var deleteButton = $(this).siblings('.deleteButton');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    preview.attr('src', e.target.result).show();
                    deleteButton.show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        });

        // Add event handler for all delete buttons
        $('.deleteButton').on('click', function () {
            var input = $(this).siblings('input[type="file"]');
            var preview = $(this).siblings('img');

            input.val(''); // Clear the file input
            preview.attr('src', '').hide();
            $(this).hide();
        });
    </script>
@endsection
