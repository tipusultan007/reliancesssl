@extends('layouts.app')

@section('content')
    <div class="container-fluid my-3">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="div d-flex justify-content-between">
            <h2>কর্মীদের বেতন ফরম </h2>
            <a href="{{ route('payrolls.index') }}" class="btn btn-success fw-bolder text-dark mb-3">কর্মীদের বেতন
                তালিকা</a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('payrolls.store') }}" class="row" method="post">
                            @csrf

                            <div class="mb-3 col-md-4">
                                <label for="user_id" class="form-label">Employee:</label>
                                <select name="user_id" data-placeholder="সিলেক্ট" id="user_id"
                                        data-allow-clear="on" class="form-select select2" data-toggle="select2">
                                    <option value="">সিলেক্ট</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}">
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="salary_month" class="form-label">বেতন প্রদানের মাস</label>
                                <input type="month" name="salary_month" value="{{ $previousMonth }}" id="salary_month"
                                       class="form-control">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="payment_date" class="form-label">বেতন প্রদান তারিখ</label>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" id="payment_date"
                                       class="form-control">
                            </div>

                            <input type="hidden" name="working_days" id="working_days" class="form-control" readonly>

                            <input type="hidden" name="present_days" id="present_days" class="form-control" readonly>

                            <input type="hidden" name="absence_days" id="absence_days" class="form-control" readonly>

                            <input type="hidden" name="leave_days" id="leave_days" class="form-control" readonly>

                            <input type="hidden" name="basic_salary" id="basic_salary" class="form-control">


                            <input type="hidden" name="payable_basic" id="payable_basic" class="form-control" readonly>
                            <div class="mb-3 col-md-3">
                                <label for="present_bonus" class="form-label">উপস্থিতি বোনাস</label>
                                <input type="text" name="present_bonus" id="present_bonus" value="0"
                                       class="form-control">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="tea_allowance" class="form-label">চা/নাস্তা বিল</label>
                                <input type="text" name="tea_allowance" id="tea_allowance" value="0"
                                       class="form-control">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="mobile_bill" class="form-label">মোবাইল বিল</label>
                                <input type="text" name="mobile_bill" id="mobile_bill" value="0" class="form-control">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="other_bill" class="form-label">অন্যান্য বিল</label>
                                <input type="text" name="other_bill" id="other_bill" value="0" class="form-control">
                            </div>
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary fw-bold w-25">সাবমিট</button>
                            </div>

                            <input type="hidden" name="net_salary" id="total">
                            <!-- Add more input fields for other components -->

                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>নাম</th>
                                <td class="name fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>মোট কার্য দিবস</th>
                                <td class="working_days fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>মোট উপস্থিতি</th>
                                <td class="present_days fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>মোট ছুটি</th>
                                <td class="leave_days fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>মোট অনুপস্থিতি</th>
                                <td class="absence_days fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>বেসিক বেতন</th>
                                <td class="basic_salary fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>প্রদেয় বেসিক বেতন</th>
                                <td class="payable_salary fw-bold text-dark"></td>
                            </tr>
                            <tr>
                                <th>মোট বেতন</th>
                                <td class="net_salary fw-bold text-dark"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".alert").fadeTo(2000, 500).slideUp(500, function () {
                $(".alert").slideUp(500);
            });

            $('#user_id').change(function () {
                var employeeId = $(this).val();
                var month = $("#salary_month").val();

                $.ajax({
                    url: '{{ route('payrolls.getEmployeeDays') }}',
                    method: 'GET',
                    dataType: "JSON",
                    data: {user_id: employeeId, salary_month: month},
                    success: function (data) {
                        console.log(data);
                        $('#basic_salary').val(Math.round(data.basic_salary));
                        $('#payable_basic').val(Math.round(data.payable_basic));
                        $('#working_days').val(data.working_days);
                        $('#present_days').val(data.total_present);
                        $('#absence_days').val(data.total_absent);
                        $('#leave_days').val(data.total_leave);
                        $('.name').text(data.name);
                        $('.basic_salary').text(Math.round(data.basic_salary));
                        $('.payable_salary').text(Math.round(data.payable_basic));
                        $('.working_days').text(data.working_days);
                        $('.present_days').text(data.total_present);
                        $('.absence_days').text(data.total_absent);
                        $('.leave_days').text(data.total_leave);
                        updateTotal();
                    }
                });
            });

            // Get references to the input fields and total field
            var salaryMonthInput = $('#salary_month');
            var payableBasicInput = $('#payable_basic');
            var presentBonusInput = $('#present_bonus');
            var teaAllowanceInput = $('#tea_allowance');
            var mobileBillInput = $('#mobile_bill');
            var otherBillInput = $('#other_bill');
            var totalField = $('#total');
            var totalFieldText = $('.total');

            // Listen for input events on the input fields
            salaryMonthInput.on('input', updateTotal);
            payableBasicInput.on('input', updateTotal);
            presentBonusInput.on('input', updateTotal);
            teaAllowanceInput.on('input', updateTotal);
            mobileBillInput.on('input', updateTotal);
            otherBillInput.on('input', updateTotal);


            // Function to update the total field
            function updateTotal() {
                var payableBasic = parseFloat(payableBasicInput.val()) || 0;
                var presentBonus = parseFloat(presentBonusInput.val()) || 0;
                var teaAllowance = parseFloat(teaAllowanceInput.val()) || 0;
                var mobileBill = parseFloat(mobileBillInput.val()) || 0;
                var otherBill = parseFloat(otherBillInput.val()) || 0;

                var total = payableBasic + presentBonus + teaAllowance + mobileBill + otherBill;
                totalField.val(Math.round(total));
                totalFieldText.text(Math.round(total));
                $(".net_salary").text(Math.round(total));
            }
        });
    </script>
@endsection
