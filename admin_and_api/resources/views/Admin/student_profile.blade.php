<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | Manage Student Profile</title>
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
            <img class="animation__shake" src="{{ asset('admin_asset/dist/img/mm.ico') }}" alt="AdminLTELogo" height="60"
                width="60">
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
                            <h1>Manage Profiles</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Manage  Profiles</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Model -->
            <div class="card-body d-flex justify-content-end">
                <a type="button" class="btn btn-primary" href="{{ url('admin/add_student_profilepage') }}">
                    Add
                </a>
            </div>
            {{-- <div class="modal fade" id="modal-default">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add  Profile</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action={{ url('admin/add_student_profile') }} enctype="multipart/form-data"
                                method='post'>
                                @csrf
                                <div class="col-md-12 col-lg-12">
                                    <label>First Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="first_name"
                                            placeholder="Enter the First Name">
                                        <span class="text-danger">
                                            @error('first_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Last Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="last_name"
                                            placeholder="Enter the Last Name">
                                        <span class="text-danger">
                                            @error('last_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Country</label>
                                            <div class="form-group">
                                                <select class="form-control" name="country_name" id="country_name">
                                                    <option value="" style="display: none">Select Country</option>
                                                    @foreach ($country as $country)
                                                        <option value="{{ $country->name }}">
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('country_name')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Country Code</label>
                                            <div class="form-group">
                                                <select class="form-control" name="country_code" id="country_code">
                                                    <option value="">Select Code</option>
                                                    @foreach ($code as $code)
                                                        <option value="{{ $code->phonecode }}">
                                                            {{ $code->phonecode }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('contact')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Contact</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" name="contact"
                                                    placeholder="Enter Your Contact Number">
                                                <span class="text-danger">
                                                    @error('contact')
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
                                            <label>Email</label>
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter Your Email Address">
                                                <span class="text-danger">
                                                    @error('email')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Date of birth</label>
                                            <div class="form-group">
                                                <input type="date" class="form-control" name="date_of_birth">
                                                <span class="text-danger">
                                                    @error('date_of_birth')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>School</label>
                                            <div class="form-group">
                                                <select class="form-control" name="school">
                                                    <option value="" style="display: none">Select School
                                                    </option>
                                                    @foreach ($school as $school)
                                                        <option value="{{ $school->school_name }}">
                                                            {{ $school->school_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('school')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Board</label>
                                            <div class="form-group">
                                                <select class="form-control" name="board">
                                                    <option value="" style="display: none">Select Board</option>
                                                    @foreach ($board as $board)
                                                        <option value="{{ $board->board_name }}">
                                                            {{ $board->board_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('board')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Exam</label>
                                            <div class="form-group">
                                                <select class="form-control" name="exam">
                                                    <option value="" style="display: none">Select Exam</option>
                                                    @foreach ($exam as $exam)
                                                        <option value="{{ $exam->exam_name }}">
                                                            {{ $exam->exam_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('exam')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label>Password</label>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Enter the Password">
                                        <span class="text-danger">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Confirm Password</label>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="confirm_password"
                                            placeholder="Enter the Confirm Password">
                                        <span class="text-danger">
                                            @error('confirm_password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Status</label>
                                    <div class="form-group">
                                        <select class="form-control" name="status">
                                            <option value="" style="display: none">Select Status
                                            </option>
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
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div> --}}
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
                                        <th>First Name</th>
                                        <th>Last Name</th>
										  <th>Type</th>
                                        <th>Country</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Date Of Birth</th>
                                        <th>School</th>
                                        <th>Board</th>
                                        <th>Exam</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $a=1; @endphp
                                    @foreach ($profile as $data)
                                        <tr>
                                            <td>{{ $a++ }}</td>
                                            <td>{{ $data->first_name }}</td>
                                            <td>{{ $data->last_name }}</td>
											  <td>{{ $data->type }}</td>
                                            <td>{{ $data->country_name }}</td>
                                            <td>{{ $data->contact }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->date_of_birth }}</td>
                                            <td>{{ $data->school }}</td>
                                            <td>{{ $data->board }}</td>
                                            <td>{{ $data->exam }}</td>
                                            <td>
                                                @if ($data->status == 'Active')
                                                    <form action={{ url('admin/status_change') . '/' . $data->id }}
                                                        method="post">
                                                        <input type="hidden" id="pid"
                                                            value="{{ $data->id }}" name="pid">
                                                        @method('PUT')
                                                        @csrf
                                                        <input type="text" style="display: none" name="status"
                                                            value="Inactive">
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">Active</button>
                                                    </form>
                                                @else
                                                    <form action={{ url('admin/status_change') . '/' . $data->id }}
                                                        method="post">
                                                        <input type="hidden" id="pid"
                                                            value="{{ $data->id }}" name="pid">
                                                        @method('PUT')
                                                        @csrf
                                                        <input type="text" style="display: none" name="status"
                                                            value="Active">
                                                        <button class="btn btn-danger btn-sm"
                                                            type="submit">InActive</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td><a type="button" class="btn btn-primary btn-sm"
                                                    href="{{ url('admin/update_student_profile/edit/' . $data->id) }}"
                                                    name="update"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger btn-sm deleteBtn"
                                                    href="{{ url('admin/show_student_profile/delete/') . '/' . $data->id }}"
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
