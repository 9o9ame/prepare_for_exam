<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | Manage Previous Year Paper</title>
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
                            <h1>Manage Previous Year Paper</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Manage Previous Year Paper</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Model -->
            <div class="card-body d-flex justify-content-end">
                <a type="button" class="btn btn-primary" href="{{ url('admin/add_previous_year_paperpage') }}"> Add
                </a>
            </div>
            {{-- <div class="modal fade" id="modal-default">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Previous Year Paper</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action={{ url('admin/add_previous_year_paper') }} enctype="multipart/form-data"
                                method='post'>
                                @csrf
                                <div class="col-md-12 col-lg-12">
                                    <label>Series</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="series"
                                            placeholder="Enter the Series No. ( Ex- Series-1 )">
                                        <span class="text-danger">
                                            @error('series')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Month :</label><br>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><input type="checkbox" name="month[]" value="Jan-Feb">
                                                    Jan-Feb</label><br>
                                                <label><input type="checkbox" name="month[]" value="Feb-Mar">
                                                    Feb-Mar</label><br>
                                                <label><input type="checkbox" name="month[]" value="Mar-April">
                                                    Mar-April</label><br>
                                                <label><input type="checkbox" name="month[]" value="April-May">
                                                    April-May</label><br>
                                                <label><input type="checkbox" name="month[]" value="May-June">
                                                    May-June</label><br>
                                                <label><input type="checkbox" name="month[]" value="June-July">
                                                    June-July</label><br>
                                            </div>
                                            <div class="col-md-6" style="text-align:center">
                                                <label><input type="checkbox" name="month[]" value="July-Aug">
                                                    July-Aug</label><br>
                                                <label><input type="checkbox" name="month[]" value="Aug-Sep">
                                                    Aug-Sep</label><br>
                                                <label><input type="checkbox" name="month[]" value="Sep-Oct">
                                                    Sep-Oct</label><br>
                                                <label><input type="checkbox" name="month[]" value="Oct-Nov">
                                                    Oct-Nov</label><br>
                                                <label><input type="checkbox" name="month[]" value="Nov-Dec">
                                                    Nov-Dec</label><br>
                                                <label><input type="checkbox" name="month[]" value="Dec-Jan">
                                                    Dec-Jan</label>
                                            </div>
                                        </div>
                                        <span class="text-danger">
                                            @error('month')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Year</label>
                                    <div class="form-group">
                                        <select class="form-control" name="year" id="ddlYears">
                                        </select>
                                        <span class="text-danger">
                                            @error('year')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Subject Code</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="subject_code"
                                            placeholder="Enter Subject Code">
                                        <span class="text-danger">
                                            @error('subject_code')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <label>Subject</label>
                                    <div class="form-group">
                                        <select class="form-control" name="subject_name">
                                            <option value="" style="display: none">Select Subject</option>
                                            @foreach ($subject as $subject)
                                                <option value="{{ $subject->subject_name }}">
                                                    {{ $subject->subject_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('subject_name')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Previous Paper Link</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="link"
                                            placeholder="Paste the Paper Link here">
                                        <span class="text-danger">
                                            @error('link')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Answersheet Link</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="answer_sheet"
                                            placeholder="Paste the Answersheet Link here">
                                        <span class="text-danger">
                                            @error('answer_sheet')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Marks</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="marks"
                                            placeholder="Enter The Marks">
                                        <span class="text-danger">
                                            @error('marks')
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
                                        <th>Series No.</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Exam Name</th>
										<th>Subject Name</th>
										<th>Board Name</th>
                                        <th>Previous Paper Link</th>
                                        <th>Answer Sheet Link</th>
                                        <th>Marks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $a=1; @endphp
                                    @foreach ($paper as $data)
                                        <tr>
                                            <td>{{ $a++ }}</td>
                                            <td>{{ $data->series }}</td>
                                            <td>{{ $data->month }}</td>
                                            <td>{{ $data->year }}</td>
											<td>{{ $data->exam->exam_name }}</td>
											<td>{{ $data->subject->subject_name }}</td>
                                            <td>{{ $data->board->board_name }}</td>
                                            <td><a href="{{ $data->link }}" width="1920px"
                                                    height="1080px">View</a>
                                            </td>
                                            <td><a href="{{ $data->answer_sheet }}" width="1920px"
                                                    height="1080px">View</a>
                                            </td>
                                            </td>
                                            <td>{{ $data->marks }}</td>

                                            <td>
                                                <a type="button" class="btn btn-primary btn-sm"
                                                    href="{{ url('admin/update_previous_year_paper/edit/' . $data->id) }}"
                                                    name="update"><i class="fa fa-edit"></i></a>

                                                <a class="btn btn-danger btn-sm deleteBtn"
                                                    href="{{ url('admin/show_previous_year_paper/delete/') . '/' . $data->id }}"
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
    <script type="text/javascript">
        window.onload = function() {
            //Reference the DropDownList.
            var ddlYears = document.getElementById("ddlYears");

            //Determine the Current Year.
            var currentYear = (new Date()).getFullYear();

            //Loop and add the Year values to DropDownList.
            for (var i = 1950; i <= currentYear; i++) {
                var option = document.createElement("OPTION");
                option.innerHTML = i;
                option.value = i;
                ddlYears.appendChild(option);
            }
        };
    </script>
</body>

</html>
