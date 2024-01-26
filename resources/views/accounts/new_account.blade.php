@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-primary pb-1">
                    <h3 class="card-title text-white"> আবেদন পত্র</h3>
                </div>
                <div class="card-body">
                    <form id="accountForm" action="{{ route('store.account') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="" class="form-label">নাম</label>
                                <input type="text" id="name" name="name" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="father_name"> পিতার/স্বামীর নাম</label>
                                <input type="text" id="father_name" name="father_name"
                                       class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="mother_name">মাতার নাম </label>
                                <input type="text" id="mother_name" name="mother_name"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="birth_date"> জন্মতারিখ </label>
                                <input id="birth_date" name="birth_date" type="date"
                                       value="{{ date('Y-m-d') }}" class="form-control">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="gender">লিঙ্গ </label>
                                <select class="form-control select2" id="gender" name="gender"
                                        data-toggle="select2" data-placeholder="Select">
                                    <option value="male">পুরুষ</option>
                                    <option value="female">মহিলা</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="marital_status">বৈবাহিক অবস্থা </label>
                                <select class="form-control select2" id="marital_status"
                                        name="marital_status" data-placeholder="Select"
                                        data-toggle="select2">
                                    <option value="married">বিবাহিত</option>
                                    <option value="unmarried">অবিবাহিত</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="spouse_name"> স্বামী/স্ত্রীর নাম </label>
                                <input type="text" id="spouse_name" name="spouse_name"
                                       class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="phone"> মোবাইল নং </label>
                                <input type="text" id="phone" name="phone" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="occupation"> পেশা </label>
                                <input type="text" id="occupation" name="occupation"
                                       class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="present_address"> বর্তমান ঠিকানা </label>
                                <input type="text" id="present_address" name="present_address"
                                       class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label" for="permanent_address"> স্থায়ী ঠিকানা</label>
                                <input type="text" id="permanent_address" name="permanent_address"
                                       class="form-control">
                            </div>
                            <input type="hidden" id="nationality" name="nationality"
                                   value="Bangladeshi">
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="photo"> সদস্যের ছবি </label>
                                <input type="file" id="photo" name="photo" class="form-control">
                                <img src="" alt="Preview"
                                     style="max-width: 100px; max-height: 100px; display: none;">
                                <button type="button" class="btn btn-sm btn-danger deleteButton"
                                        style="display: none;">Delete
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="signature"> স্বাক্ষর </label>
                                <input type="file" id="signature" name="signature" class="form-control">
                                <img src="" alt="Preview"
                                     style="max-width: 100px; max-height: 100px; display: none;">
                                <button type="button" class="btn btn-sm btn-danger deleteButton"
                                        style="display: none;">Delete
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="nid_no"> জাতীয় পরিচয়পত্র নং </label>
                                <input type="number" id="nid_no" name="nid_no" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="nid"> জাতীয় পরিচয়পত্র (ছবি) </label>
                                <input type="file" id="nid" name="nid" class="form-control">
                                <img src="" alt="Preview"
                                     style="max-width: 100px; max-height: 100px; display: none;">
                                <button type="button" class="btn btn-sm btn-danger deleteButton"
                                        style="display: none;">Delete
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="birth_id"> জন্মসনদ </label>
                                <input type="file" name="birth_id" id="birth_id" class="form-control">
                                <img src="" alt="Preview"
                                     style="max-width: 100px; max-height: 100px; display: none;">
                                <button type="button" class="btn btn-sm btn-danger deleteButton"
                                        style="display: none;">Delete
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="join_date"> তারিখ </label>
                                <input id="join_date" name="join_date" value="{{ date('Y-m-d') }}"
                                       type="date" class="form-control">
                            </div>
                        </div> <!-- end row -->



                        <h4 class="bg-primary py-2 text-center text-white">সঞ্চয় হিসাব</h4>

                        <h6 class="font-15">সঞ্চয় হিসাবের ধরণ</h6>
                        <div class="my-2">
                            <div class="form-check form-check-inline">
                                <input type="radio" id="daily_savings" name="type" value="daily" class="form-check-input" checked>
                                <label class="form-check-label" for="daily_savings">দৈনিক সঞ্চয়</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="monthly_savings" name="type" value="monthly" class="form-check-input">
                                <label class="form-check-label" for="monthly_savings">মাসিক সঞ্চয়</label>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-6 daily mb-2">
                                <div class="form-group">
                                    <label for="" class="form-label">দৈনিক হিসাব নং</label>
                                    <input type="number" name="daily_account_no" id="daily_account_no" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 monthly mb-2" style="display: none">
                                <div class="form-group">
                                    <label for="monthly_account_no" class="form-label">মাসিক হিসাব নং</label>
                                    <input type="number" name="monthly_account_no" id="monthly_account_no" class="form-control">
                                </div>
                            </div>

                            <div class="col-xl-6 col-sm-12 mb-2 monthly_amount" style="display: none">
                                <div class="form-group">
                                    <label class="form-label" for="monthly_amount">মাসিক কিস্তির পরিমাণ (টাকা)</label>
                                    <input type="number" class="form-control" name="monthly_amount" id="monthly_amount">
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-12 mb-2 duration" style="display: none">
                                <div class="form-group">
                                    <label class="form-label" for="duration">প্রকল্প মেয়াদ</label>
                                    <select class="form-control select2" id="duration" name="duration" data-placeholder="Select" data-toggle="select2">
                                        <option value=""></option>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">তারিখ</label>
                                    <input type="text" name="date" class="form-control datepicker">
                                </div>
                            </div>
                        </div>
                        <h4 class="bg-primary py-2 text-center text-white">পরিচয়দানকারী</h4>

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
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                            - {{ $item->father_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="introducer_father">পিতার
                                    নাম</label>
                                <input type="text" id="introducer_father"
                                       name="introducer_father" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="introducer_mobile">
                                    মোবাইল নং </label>
                                <input type="text" id="introducer_mobile"
                                       name="introducer_mobile" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="introducer_name">পূর্ণনাম</label>
                                <input type="text" id="introducer_name" name="introducer_name"
                                       class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="introducer_address">
                                    পূর্ণ ঠিকানা </label>
                                <input type="text" id="introducer_address"
                                       name="introducer_address" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" for="introducer_signature">
                                    স্বাক্ষর </label>
                                <input type="file" id="introducer_signature"
                                       name="introducer_signature" class="form-control">
                                <img src="" alt="Preview"
                                     style="max-width: 100px; max-height: 100px; display: none;">
                                <button type="button" class="btn btn-sm btn-danger deleteButton"
                                        style="display: none;">Delete
                                </button>
                            </div>


                        </div>
                        <div class=" d-flex justify-content-center">
                            <button class="btn btn-success w-25 fw-bolder" type="submit" id="btn-submit">সাবমিট</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection

@section('scripts')
    <script>
        $("input[name=type]").on('change',function () {
            var type = $(this).val();

            if (type === 'daily') {
                $(".daily").show();
                $(".monthly").hide();
                $("#daily_account_no").val("");
                $("#monthly_account_no").val("");
                $("#monthly_amount").val("");
                $(".monthly_amount").hide("");
                $(".duration").hide("");
                $(".duration").val("").trigger('change');
            } else if (type === "monthly")
            {
                $(".daily").hide();
                $(".monthly").show();
                $(".monthly_amount").show("");
                $(".duration").show("");
                $("#daily_account_no").val("");
                $("#monthly_account_no").val("");
            }
        })
    </script>
@endsection
