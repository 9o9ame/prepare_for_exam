<!DOCTYPE html>
<html lang="en">

<head>
    <title>MoneyKart | Admin Management</title>
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
            <img class="animation__shake" src="{{asset('admin_asset/dist/img/mm.png')}}" alt="AdminLTELogo" height="60" width="60">
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
                            <h1>Admin Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active">Admin Management</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Model -->

            <div class="card-body d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                    Add
                </button>
            </div>

            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Admin</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action={{url('admin/save_admin_management')}} method='post'>
                                @csrf
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-row">
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="name" placeholder="User Name">
                                                <span class="text-danger">@error('name'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="email" placeholder="Email">
                                                <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact" placeholder="Contact">
                                                <span class="text-danger">@error('contact'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="role" placeholder="Role">
                                                <span class="text-danger">@error('role'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 mb-0">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="Password">
                                                <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        @if(Session::get('success'))
                        <span class="alert alert-success">
                            {{Session::get('success')}}
                        </span>
                        @endif
                        @if(Session::get('error'))
                        <span class="alert alert-danger">
                            {{Session::get('error')}}
                        </span>
                        @endif
                        <div class="card-body" style="overflow-x: scroll;padding-bottom:45px">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Contact No</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $data)
                                    <tr>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->contact}}</td>
                                        <td>{{$data->role}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{$data->id}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <div class="modal fade" id="editModal{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Admin</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action={{url('admin/update_admin_management')}} method='post'>
                                                                <input type="hidden" id="pid" value="{{$data->id}}" name="pid">
                                                                @csrf
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6 mb-0">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="name" value="{{$data->name}}" placeholder="User Name">
                                                                                <span class="text-danger">@error('name'){{ $message }}@enderror</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-6 mb-0">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="email" value="{{$data->email}}" placeholder="Email">
                                                                                <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6 mb-0">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="contact" value="{{$data->contact}}" placeholder="Contact">
                                                                                <span class="text-danger">@error('contact'){{ $message }}@enderror</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-6 mb-0">
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" name="role" value="{{$data->role}}" placeholder="Role">
                                                                                <span class="text-danger">@error('role'){{ $message }}@enderror</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-12 mb-0">
                                                                            <div class="form-group">
                                                                                <input type="password" class="form-control" name="role" value="{{$data->password}}" placeholder="Password">
                                                                                <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="update" class="btn btn-primary">Update changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn btn-danger btn-sm deleteBtn" href="{{url('admin/adminmanagement/delete/') . '/' .$data->id}}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div><!-- /.container-fluid -->
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
    <script src="{{asset('admin_asset/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{asset('admin_asset/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('admin_asset/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('admin_asset/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('admin_asset/dist/js/demo.js')}}"></script>
    <!-- Page specific script -->
    <!-- Yajra DataTables-->
    <!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> -->
    <!--End Yajra DataTables -->

    <!-- <script>
        $(function() {
            var table = $('.yajra').DataTable({});
        });
    </script> -->

    <script>
        $(function() {
            $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    initComplete: function() {
                        // Apply the search
                        this.api().columns().every(function() {
                            var that = this;
                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                    }
                })
                .buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')
        });
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example1 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });
        });
    </script>
</body>

</html>
