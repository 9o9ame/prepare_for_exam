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
                            <h1>Add Student Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Add Student Profile</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

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
                        <form action={{ url('admin/add_student_profile') }} enctype="multipart/form-data"
                            method='post'>
                            @csrf
                            <div class="col-md-12 col-lg-12">
                                <label>First Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="first_name"
                                        placeholder="Enter the First Name" value="{{ old('first_name') }}">
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
                                        placeholder="Enter the Last Name" value="{{ old('last_name') }}">
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
                                                {{-- @foreach ($code as $code)
                                                        <option value="{{ $code->phonecode }}">
                                                            {{ $code->phonecode }}</option>
                                                    @endforeach --}}
                                            </select>
                                            <span class="text-danger">
                                                @error('country_code')
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
                                                placeholder="Enter Your Contact Number" value="{{ old('contact') }}">
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
                                                placeholder="Enter Your Email Address" value="{{ old('email') }}">
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
                                            <input type="date" class="form-control" name="date_of_birth"
                                                value="{{ old('date_of_birth') }}">
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
