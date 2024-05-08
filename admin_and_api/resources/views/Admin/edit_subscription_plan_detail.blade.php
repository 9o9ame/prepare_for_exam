<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | Add Subcription Plan Details</title>
    @include('Admin/head')
    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        table.dataTable thead .sorting {
            background-image: none !important;
        }

        table.dataTable thead .sorting_asc {
            background-image: none !important;
        }

        table.dataTable thead .sorting_desc {
            background-image: none !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('admin_asset/dist/img/mm.ico') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div>
        <!-- Navbar -->
        @include('Admin/navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('Admin/sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Subcription Plan Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Edit Subcription Plan Details</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <form action={{ url('admin/update_subscription_plan') . '/' . $result->id }}
                            enctype="multipart/form-data" method='post'>
                            <input type="hidden" id="pid" value="{{ $result->id }}" name="pid">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subscription For</label>
                                        <div class="form-group">
                                            <select class="form-control" name="plan_for">
                                                <option value="{{ $result->plan_for }}" style="display: none">
                                                    {{ $result->plan_for }}</option>
                                                <option value="Student">Student</option>
                                                <option value="Teacher">Teacher</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('plan_for')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subscription Plan Name</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="subscription_name"
                                                placeholder="Enter the Subscription Plan"
                                                value="{{ $result->subscription_name }}">
                                            <span class="text-danger">
                                                @error('subscription_name')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subscription Plan Type</label>
                                        <div class="form-group">
                                            <select class="form-control" name="subscription_type">
                                                <option value="{{ $result->subscription_type }}" style="display: none">
                                                    {{ $result->subscription_type }}</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Diamond">Diamond</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('subscription_type')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subscription Price</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="subscription_price"
                                                placeholder="Enter The Subscription Price"
                                                value="{{ $result->subscription_price }}">
                                            <span class="text-danger">
                                                @error('subscription_price')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subscription Validity ( Month )</label>
                                        <div class="form-group">
                                            <select class="form-control" name="sv_month">
                                                <option value="{{ $result->sv_month }}" style="display: none">
                                                    {{ $result->sv_month }}</option>
                                                <option value="1">1 Months</option>
                                                <option value="6">6 Months</option>
                                                <option value="12">12 Months</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('sv_month')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Country</label>
                                        <div class="form-group">
                                            <select class="form-control" name="country" id="country_name">
                                                <option value="{{ $result->country }}" style="display: none">
                                                    {{ $result->country }}</option>
                                                @foreach ($country as $country)
                                                <option value="{{ $country->name }}">
                                                    {{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">
                                                @error('country')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>No. of Board</label>
                                        <div class="form-group">
                                            <select class="form-control" name="no_of_board">
                                                @if($result->no_of_board =='1')
                                                <option value="{{ $result->no_of_board }}" style="display: none">
                                                    1 Board</option>
                                                @else
                                                <option value="{{ $result->no_of_board }}" style="display: none">
                                                    Unlimited Boards</option>
                                                @endif
                                                <option value="1">1 Board</option>
                                                <option value="-1">Unlimited Boards</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('no_of_board')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>No. of Exam</label>
                                        <div class="form-group">
                                            <select class="form-control" name="no_of_exam">
                                                @if($result->no_of_exam =='1')
                                                <option value="{{ $result->no_of_exam }}" style="display: none">
                                                    1 Exam</option>
                                                @else
                                                <option value="{{ $result->no_of_exam }}" style="display: none">
                                                    Unlimited Exams</option>
                                                @endif
                                                <option value="1">1 Exam</option>
                                                <option value="-1">Unlimited Exams</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('no_of_exam')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>No. of Subject</label>
                                        <div class="form-group">
                                            <select class="form-control" name="no_of_subject">
                                                @if($result->no_of_exam =='1')
                                                <option value="{{ $result->no_of_subject }}" style="display: none">
                                                    1 Subject</option>
                                                @else
                                                <option value="{{ $result->no_of_subject }}" style="display: none">
                                                    Unlimited Subjects</option>
                                                @endif
                                                <option value="1">1 Subject</option>
                                                <option value="-1">Unlimited Subjects</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('no_of_subject')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Status</label>
                                        <div class="form-group">
                                            <select class="form-control" name="status">
                                                <option value="{{ $result->status }}" style="display: none">
                                                    {{ $result->status }}</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">InActive</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('status')
                                                {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>

                        <!-- /.card-body -->
                        <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        @include('Admin/footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('admin_asset/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('admin_asset/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin_asset/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin_asset/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('admin_asset/dist/js/demo.js') }}"></script>
    <!-- Page specific script -->
    <!-- Yajra DataTables
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!--End Yajra DataTables-->

    <!-- <script>
        $(function() {
            var table = $('.yajra').DataTable({});
        });
    </script>  -->

    <script>
        $(function() {
            $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    initComplete: function() {
                        // Apply the search
                        // this.api().columns().every(function() {
                        //     var that = this;
                        //     $('input', this.footer()).on('keyup change clear', function() {
                        //         if (that.search() !== this.value) {
                        //             that
                        //                 .search(this.value)
                        //                 .draw();
                        //         }
                        //     });
                        // });
                    }
                })
                .buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')
        });
        // $(document).ready(function() {
        //     // Setup - add a text input to each footer cell
        //     $('#example1 tfoot th').each(function() {
        //         var title = $(this).text();
        //         $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        //     });
        // });
    </script>
    <script>
        $(document).ready(function() {
            $('#country_name').change(function() {
                let id = $(this).val();
                $('#country_code').html('<option value="">Select Code</option>')
                // alert(id);
                $.ajax({
                    url: '/fetchcountrycode',
                    type: 'post',
                    data: 'id=' + id + '&_token={{ csrf_token() }}',
                    success: function(result) {
                        $('#country_code').html(result)

                    }
                });
            });
        });
    </script>
</body>

</html>