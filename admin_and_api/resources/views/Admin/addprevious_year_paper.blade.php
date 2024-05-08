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
                            <h1>Add Previous Year Paper</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Add Previous Year Paper</li>
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
                        <form action={{ url('admin/add_previous_year_paper') }} enctype="multipart/form-data"
                            method='post'>
                            @csrf
                            <div class="col-md-12 col-lg-12">
                                <label>Series</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="series"
                                        placeholder="Enter the Series No. ( Ex- Series-1 )" value="{{ old('series') }}">
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
                                        <div class="col-md-4">
                                            <label><input type="checkbox" name="month[]" value="Jan-Feb">
                                                Jan-Feb</label><br>
                                            <label><input type="checkbox" name="month[]" value="Feb-Mar">
                                                Feb-Mar</label><br>
                                            <label><input type="checkbox" name="month[]" value="Mar-April">
                                                Mar-April</label><br>
                                            <label><input type="checkbox" name="month[]" value="April-May">
                                                April-May</label><br>

                                        </div>
                                        <div class="col-md-4" style="text-align:center">
                                            <label><input type="checkbox" name="month[]" value="May-June">
                                                May-June</label><br>
                                            <label><input type="checkbox" name="month[]" value="June-July">
                                                June-July</label><br>
                                            <label><input type="checkbox" name="month[]" value="July-Aug">
                                                July-Aug</label><br>
                                            <label><input type="checkbox" name="month[]" value="Aug-Sep">
                                                Aug-Sep</label><br>

                                        </div>
                                        <div class="col-md-4">
                                            <label><input type="checkbox" name="month[]" value="Sep-Oct">
                                                Sep-Oct</label><br>
                                            <label><input type="checkbox" name="month[]" value="Oct-Nov">
                                                Oct-Nov</label><br>
                                            <label><input type="checkbox" name="month[]" value="Nov-Dec">
                                                Nov-Dec</label><br>
                                            <label><input type="checkbox" name="month[]" value="Dec-Jan">
                                                Dec-Jan</label>
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
                                <div class="row">
                                   
                                    <div class="col-md-6">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Subject</label>
                                            <div class="form-group">
                                                <select class="form-control" name="subject_name">
                                                    <option value="" style="display: none">Select Subject
                                                    </option>
                                                    @foreach ($subject as $subject)
                                                        <option value="{{ $subject->id }}">
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
                                    </div>
									
									<div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Exam</label>
                                        <div class="form-group">
                                            <select class="form-control" name="exam">
                                                <option value="" style="display: none">Select Exam</option>
                                                @foreach ($exam as $exam)
                                                    <option value="{{ $exam->id }}">
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
								
								<div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Board</label>
                                        <div class="form-group">
                                            <select class="form-control" name="board">
                                                <option value="" style="display: none">Select Board</option>
                                                @foreach ($board as $board)
                                                    <option value="{{ $board->id }}">
                                                        {{ $board->board_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">
                                                @error('subject')
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
                                            <label>Previous Paper Link</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="link"
                                                    placeholder="Paste the Paper Link here"
                                                    value="{{ old('link') }}">
                                                <span class="text-danger">
                                                    @error('link')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12 col-lg-12">
                                            <label>Answersheet Link</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="answer_sheet"
                                                    placeholder="Paste the Answersheet Link here"
                                                    value="{{ old('answer_sheet') }}">
                                                <span class="text-danger">
                                                    @error('answer_sheet')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Marks</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="marks"
                                            value="{{ old('marks') }}" placeholder="Enter The Marks">
                                        <span class="text-danger">
                                            @error('marks')
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
