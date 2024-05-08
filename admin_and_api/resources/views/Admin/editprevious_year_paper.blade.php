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
                            <h1>Edit Previous Year Paper</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Edit Previous Year Paper</li>
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
                        <form action={{ url('admin/update_previous_year_paper') . '/' . $result->id }}
                            enctype="multipart/form-data" method='post'>
                            <input type="hidden" id="pid" value="{{ $result->id }}" name="pid">
                            @method('PUT')
                            @csrf
                            <div class="col-md-12 col-lg-12">
                                <label>Series</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="series"
                                        placeholder="Enter the Series No. ( Ex- Series-1 )"
                                        value="{{ $result->series }}">
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
                                            @if (str_contains($result->month, 'Jan-Feb'))
                                                <label><input type="checkbox" name="month[]" value="Jan-Feb" checked>
                                                    Jan-Feb</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Jan-Feb">
                                                    Jan-Feb</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'Feb-Mar'))
                                                <label><input type="checkbox" name="month[]" value="Feb-Mar" checked>
                                                    Feb-Mar</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Feb-Mar">
                                                    Feb-Mar</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'Mar-April'))
                                                <label><input type="checkbox" name="month[]" value="Mar-April" checked>
                                                    Mar-April</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Mar-April">
                                                    Mar-April</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'April-May'))
                                                <label><input type="checkbox" name="month[]" value="April-May" checked>
                                                    April-May</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="April-May">
                                                    April-May</label><br>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            @if (str_contains($result->month, 'May-June'))
                                                <label><input type="checkbox" name="month[]" value="May-June" checked>
                                                    May-June</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="May-June">
                                                    May-June</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'June-July'))
                                                <label><input type="checkbox" name="month[]" value="June-July" checked>
                                                    June-July</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="June-July">
                                                    June-July</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'July-Aug'))
                                                <label><input type="checkbox" name="month[]" value="July-Aug" checked>
                                                    July-Aug</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="July-Aug">
                                                    July-Aug</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'Aug-Sep'))
                                                <label><input type="checkbox" name="month[]" value="Aug-Sep" checked>
                                                    Aug-Sep</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Aug-Sep">
                                                    Aug-Sep</label><br>
                                            @endif
                                        </div>

                                        <div class="col-md-4">

                                            @if (str_contains($result->month, 'Sep-Oct'))
                                                <label><input type="checkbox" name="month[]" value="Sep-Oct" checked>
                                                    Sep-Oct</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Sep-Oct">
                                                    Sep-Oct</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'Oct-Nov'))
                                                <label><input type="checkbox" name="month[]" value="Oct-Nov" checked>
                                                    Oct-Nov</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Oct-Nov">
                                                    Oct-Nov</label><br>
                                            @endif


                                            @if (str_contains($result->month, 'Nov-Dec'))
                                                <label><input type="checkbox" name="month[]" value="Nov-Dec" checked>
                                                    Nov-Dec</label><br>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Nov-Dec">
                                                    Nov-Dec</label><br>
                                            @endif

                                            @if (str_contains($result->month, 'Dec-Jan'))
                                                <label><input type="checkbox" name="month[]" value="Dec-Jan" checked>
                                                    Dec-Jan</label>
                                            @else
                                                <label><input type="checkbox" name="month[]" value="Dec-Jan">
                                                    Dec-Jan</label>
                                            @endif

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
                                        <option value="{{ $result->year }}" selected>
                                            {{ $result->year }}</option>
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
                                        <label>Exam</label>
                                        <div class="form-group">
                                            <select class="form-control" name="exam">
                                                @foreach ($exam as $exam)
												
												@if($exam->id == $result->exam_id)
                                                    <option value="{{ $exam->id }}" selected>{{ $exam->exam_name }}</option>
												@else
													<option value="{{ $exam->id }}" >{{ $exam->exam_name }}</option>
												@endif
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
												@if($board->id == $result->board_id)
                                                    <option value="{{ $board->id }}">
                                                        {{ $board->board_name }}</option>
														@else
															 <option value="{{ $board->id }}">
                                                        {{ $board->board_name }}</option>
														@endif
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
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Subject</label>
                                        <div class="form-group">
                                            <select class="form-control" name="subject_name">
                                               
                                                @foreach ($subject as $subject)
												@if($subject->id == $result->subject_name)
                                                    <option value="{{ $subject->subject_name }}" selected>
                                                        {{ $subject->id }}</option>
														@else
															  <option value="{{ $subject->subject_name }}">
                                                        {{ $subject->id }}</option>
														@endif
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
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Previous Paper Link</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="link"
                                                value="{{ $result->link }}">
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
                                                value="{{ $result->answer_sheet }}">
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
                                        placeholder="Paste the Answersheet Link here" value="{{ $result->marks }}">
                                    <span class="text-danger">
                                        @error('marks')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update
                            changes</button>
                    </div>
                </div>
        </div>

        </form>

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
