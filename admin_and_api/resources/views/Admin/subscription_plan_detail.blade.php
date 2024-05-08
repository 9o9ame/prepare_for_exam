<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | Subscription Details</title>
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
                            <h1>Subscription Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Subscription Details</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Model -->
            <div class="card-body d-flex justify-content-end">
                <a type="button" class="btn btn-primary" href="{{ url('admin/add_subscription_plan_detail') }}">
                    Add
                </a>
            </div>

            <!-- /Modal Close -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        @if (Session::get('success'))
                        <span class="alert alert-success">
                            {{ Session::get('success') }}
                        </span>
                        @endif
                        @if (Session::get('error'))
                        <span class="alert alert-danger">
                            {{ Session::get('error') }}
                        </span>
                        @endif
                        <div class="card-body" style="overflow-x:scroll">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subscription Plan For</th>
                                        <th>Subscription Plan Name</th>
                                        <th>Subscription Plan Type</th>
                                        <th>Subscription Price</th>
                                        <th>Subscription Validity ( Month )</th>
                                        <th>Country</th>
                                        <th>No. of Board</th>
                                        <th>No. of Exam</th>
                                        <th>No. of Subject</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $a=1; @endphp
                                    @foreach ($subscription as $data)
                                    <tr>
                                        <td>{{ $a++ }}</td>
                                        <td>{{ $data->plan_for }}</td>
                                        <td>{{ $data->subscription_name }}</td>
                                        <td>{{ $data->subscription_type }}</td>
                                        <td>{{ $data->subscription_price }}</td>
                                        <td>{{ $data->sv_month }}</td>
                                        <td>{{ $data->country }}</td>
                                        <td>
                                            @if($data->no_of_board == '1')
                                            <p>1</p>
                                            @else
                                            <p>Unlimited Boards</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->no_of_exam == '1')
                                            <p>1</p>
                                            @else
                                            <p>Unlimited Exams</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->no_of_subject == '1')
                                            <p>1</p>
                                            @else
                                            <p>Unlimited Subjects</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data->status == 'Active')
                                            <form action={{ url('admin/status_change_subs') . '/' . $data->id }}
                                                method="post">
                                                <input type="hidden" id="pid" value="{{ $data->id }}" name="pid">
                                                @method('PUT')
                                                @csrf
                                                <input type="text" style="display: none" name="status" value="Inactive">
                                                <button type="submit" class="btn btn-success btn-sm">Active</button>
                                            </form>
                                            @else
                                            <form action={{ url('admin/status_change_subs') . '/' . $data->id }}
                                                method="post">
                                                <input type="hidden" id="pid" value="{{ $data->id }}" name="pid">
                                                @method('PUT')
                                                @csrf
                                                <input type="text" style="display: none" name="status" value="Active">
                                                <button class="btn btn-danger btn-sm" type="submit">InActive</button>
                                            </form>
                                            @endif
                                        </td>
                                        <td><a type="button" class="btn btn-primary btn-sm"
                                                href="{{ url('admin/update_subscription_plan/edit/' . $data->id) }}"
                                                name="update"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm deleteBtn"
                                                href="{{ url('admin/subscription_plan_detail/delete/') . '/' . $data->id }}"
                                                onclick="return confirm('Do you want to delete Y/N')">
                                                <i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
        </div>
        <!-- /.card-body -->
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->

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
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

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