@extends('layouts.master')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ড্যাশবোর্ড</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">মাসিক সঞ্চয়</a></li>
                            <li class="breadcrumb-item active">লভ্যাংশ প্রদান</li>
                        </ol>
                    </div>
                    <h4 class="page-title"> মাসিক লভ্যাংশ প্রদান</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-success pb-1">
                        <h4 class="card-title text-white">
                            লভ্যাংশ এন্ট্রি করুন
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add-profits.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="" class="form-label">হিসাব নংঃ</label>
                                <select class="form-control select2" id="account_no" name="account_no"
                                        data-placeholder="হিসাব নং" data-toggle="select2"
                                        required>
                                    <option value=""></option>
                                    @foreach($savings as $saving)
                                        <option value="{{ $saving->account_no }}"> {{ $saving->account_no }} - {{ $saving->member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="type" value="monthly">
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
                            <div class="form-group mb-2">
                                <label class="form-label" for="date"> লভ্যাংশ প্রদান মাস </label>
                                <input id="date" name="year_month" type="month" class="form-control"
                                       value="">
                            </div>
                            <div class="form-group mb-2 d-flex align-items-end">
                                <button class="btn btn-success" type="submit">সাবমিট</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-secondary pb-1">
                        <h4 class="card-title text-white">
                            লভ্যাংশ তালিকা
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>হিসাব নং</th>
                                    <th>পরিমাণ</th>
                                    <th>তারিখ</th>
                                    <th>মাস</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($profits as $profit)
                                <tr>
                                    <td>{{ $profit->account_no }}</td>
                                    <td>{{ $profit->amount }}</td>
                                    <td>{{ date('d/m/Y',strtotime($profit->date)) }}</td>
                                    <td>{{ $profit->year_month??'-' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                data-id="{{ $profit->id }}"
                                                data-account_no="{{ $profit->account_no }}" data-amount="{{ $profit->amount }}"
                                                data-date="{{ $profit->date }}" data-year_month="{{ $profit->year_month??'' }}"
                                                data-description="{{ $profit->description }}"
                                                class="btn btn-info btn-edit me-2 btn-sm" type="button">এডিট</button>
                                            <form
                                                action="{{ route('add-profits.destroy',$profit->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="if(!confirm('ডিলেট করতে চান?')){return false;}"
                                                        class="btn btn-danger btn-sm"><i
                                                        class="fa fa-fw fa-trash"></i>
                                                    ডিলেট
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $profits->links() }}
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
                    <h5 class="modal-title">লভ্যাংশ আপডেট ফরম</h5>
                </div>
                <form id="EditForm" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group mb-2">
                            <label for="" class="form-label">হিসাব নংঃ</label>
                            <input type="text" class="form-control" name="account_no" id="edit_account_no" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="form-label">পরিমাণ</label>
                            <input type="number" name="amount" id="edit_amount" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="form-label">বিবরণ</label>
                            <input type="text" name="description" id="edit_description" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label" for="date"> তারিখ </label>
                            <input id="edit_date" name="date" type="date" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label" for="date"> মাস </label>
                            <input id="edit_year_month" name="year_month" type="month" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary update" >আপডেট</button>
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


        $(document).on("click", ".btn-edit", function () {
            var id = $(this).data('id');
            var account_no = $(this).data('account_no');
            var amount = $(this).data('amount');
            var description = $(this).data('description');
            var year_month = $(this).data('year_month');
            var date = $(this).data('date');

            // Construct the URL based on the profit ID
            var updateUrl = "{{ url('add-profits') }}/"+id;

            // Set the form action attribute
            $('#EditForm').attr('action', updateUrl);

            $("#edit_id").val(id);
            $("#edit_account_no").val(account_no);
            $("#edit_amount").val(amount);
            $("#edit_date").val(date);
            $("#edit_year_month").val(year_month);
            $("#edit_description").val(description);
            $("#modalDefault").modal("show");
        })
        $("#EditForm").on("submit",function () {
            $(this).preventDefault();
            var id = $("#EditForm input[name='id']").val();
            var url = "add-profits/"+id;
            $("#EditForm").attr('action',url);

            $("#EditForm").submit();
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


