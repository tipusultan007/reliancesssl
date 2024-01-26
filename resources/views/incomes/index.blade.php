@extends('layouts.master')
@section('vendor-css')
    <!-- Datatables css -->
    <link href="{{asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet"
          type="text/css"/>

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
                    <h4 class="page-title"> সকল আয়</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                            নতুন আয় এন্ট্রি করুন
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="incomeForm">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="" class="form-label">আয়ের ধরনঃ</label>
                                <select class="form-control select2" id="category_id" name="category_id"
                                        data-placeholder="Select" data-toggle="select2"
                                        required>
                                    <option value="">Select</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}"> {{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="" class="form-label">পরিমাণ</label>
                                <input type="number" name="amount" id="amount" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label for="" class="form-label">বিবরণ</label>
                                <input type="text" name="description" id="description" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="date"> তারিখ </label>
                                <input id="date" name="date" type="date" class="form-control"
                                       value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group mb-2 d-flex align-items-end">
                                <button class="btn btn-success" id="submit">তৈরি করুন</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-white">
                            আয়ের ধরনের তালিকা
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table incomes-table">
                            <thead>
                            <tr>
                                <th>আয়ের ধরন</th>
                                <th>বিবরণ</th>
                                <th>তারিখ</th>
                                <th>পরিমাণ</th>
                                <th>#</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div> <!-- container -->

    <!-- Modal Trigger Code -->
    <!-- Modal Content Code -->
    <div class="modal fade" tabindex="-1" id="modalDefault">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
                <div class="modal-header">
                    <h5 class="modal-title">আয় আপডেট ফরম</h5>
                </div>
                <form id="incomeEditForm">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group mb-2">
                            <label for="" class="form-label">আয়ের ধরনঃ</label>
                            <select class="form-control select2" id="edit_category_id" name="category_id"
                                    data-placeholder="Select" data-toggle="select2"
                                    required>
                                <option value="">Select</option>
                                @foreach($categories as $key => $category)
                                    <option value="{{ $key }}"> {{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="form-label">পরিমাণ</label>
                            <input type="number" name="amount" id="edit_amount" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label" for="date"> তারিখ </label>
                            <input id="edit_date" name="date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-primary update">আপডেট</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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


        $("#submit").on("click", function () {
            var $this = $("#submit"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#incomeForm").serializeArray();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ route('incomes.store') }}",
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                    $("#name").val("");
                    $.NotificationApp.send("Success", "Data submission success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                    $(".incomes-table").DataTable().destroy();
                    loadData();
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#name").val("");
                    //$("#createAppModal").modal("hide");
                    $.NotificationApp.send("Error", "Data submission failed", "bottom-right", "rgba(0,0,0,0.2)", "error")
                }
            })
        })
        $(document).on("click", ".edit", function () {
            var id = $(this).data('id');
            var category = $(this).data('category-id');
            var amount = $(this).data('amount');
            var date = $(this).data('date');
            $("#edit_id").val(id);
            $("#edit_category_id").val(category).trigger('change');
            $("#edit_amount").val(amount);
            $("#edit_date").val(date);
            $("#modalDefault").modal("show");
        })
        $(".update").on("click",function () {
            var $this = $(".update"); //submit button selector using ID
            var $caption = $this.html();// We store the html content of the submit button
            var formData = $("#incomeEditForm").serializeArray();
            var id = $("#incomeEditForm input[name='id']").val();

            $.ajax({
                method: 'POST',
                data: formData,
                url: "{{ url('incomes') }}/"+id,
                beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                    $this.attr('disabled', true).html("Processing...");
                },
                success: function (data) {
                    $this.attr('disabled', false).html($caption);
                        $.NotificationApp.send("Success", "Data update success", "bottom-right", "rgba(0,0,0,0.2)", "success")
                        $(".incomes-table").DataTable().destroy();
                         loadData();
                    $("#modalDefault").modal("hide");
                },
                error: function () {
                    $this.attr('disabled', false).html($caption);
                    $("#modalDefault").modal("hide");
                    $.NotificationApp.send("Error","Data submission failed","bottom-right","rgba(0,0,0,0.2)","error")
                }
            })
        })

        loadData();

        function loadData() {
            $('.incomes-table').DataTable({
                "proccessing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('dataIncomes') }}"
                },
                columns: [
                    {data: 'category'},
                    {data: 'description'},
                    {data: 'date'},
                    {data: 'amount'},
                    {data: 'action'},
                ],
                buttons: ["copy", "print"],

            });
        }

        function deleteConfirmation(id) {
            swal.fire({
                title: "আপনি কি নিশ্চিত?",
                icon: 'question',
                text: "আপনি এটি ফিরিয়ে আনতে পারবেন না!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: '#0acf97',
                cancelButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছে ফেলুন!',
                cancelButtonText: 'না, বাতিল করুন',
                reverseButtons: !0
            }).then(function (e) {

                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('incomes')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                $(".incomes-table").DataTable().destroy();
                                loadData();
                                $.NotificationApp.send("Success", results.message, "bottom-right", "rgba(0,0,0,0.2)", "success")
                            } else {
                                $.NotificationApp.send("Error", results.message, "bottom-right", "rgba(0,0,0,0.2)", "error")
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }

    </script>

@endsection


