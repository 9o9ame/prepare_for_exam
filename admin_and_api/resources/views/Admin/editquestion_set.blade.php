<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | Manage Question Set</title>
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
                            <h1>Edit Student Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Edit Question Set</li>
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
                        <form action={{ url('admin/update_question_set') . '/' . $result->id }}
                            enctype="multipart/form-data" method='post'>
                            <input type="hidden" id="pid" value="{{ $result->id }}" name="pid">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Paper Code</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="subject_code"
                                                value="{{ $result->subject_code }}">
                                            <span class="text-danger">
                                                @error('subject_code')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Add Subject</label>
                                        <div class="form-group">
                                            <select class="form-control" name="subject_name">
                                                @foreach ($subject as $subject)
                                                    <option value="{{ $subject->id }}" <?php if($subject->id == $result->subject_id) {echo "selected";} ?>>
                                                        {{ $subject->subject_name }}
                                                    </option>
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
                                                    <option value="{{ $exam->id }}" <?php if($exam->id == $result->exams[0]->id) {echo "selected";} ?>>
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
                                                    <option value="{{ $board->id }}" <?php if($board->id == $result->boards[0]->id) {echo "selected";} ?>>
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
                                        <label>Add Topic</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="topic"
                                                value="{{ $result->topic }}">
                                            <span class="text-danger">
                                                @error('topic')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Add Sub-Topic</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="sub_topic"
                                                value="{{ $result->sub_topic }}">
                                            <span class="text-danger">
                                                @error('sub_topic')
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
                                        <label>Question</label>
                                        <div class="form-group">
                                            <textarea class="form-control" name="question" value="" id="editor">{{ $result->question }}</textarea>
                                            <span class="text-danger">
                                                @error('question')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Mark Schema</label>
                                        <div class="form-group">
                                            <textarea type="text" class="form-control" name="mark_schema" id="editor1" value="">{{ $result->mark_schema }}</textarea>
                                            <span class="text-danger">
                                                @error('mark_schema')
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
                                        <label>Year</label>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="year"
                                                value="{{ $result->year }}">
                                            <span class="text-danger">
                                                @error('year')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Marks</label>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="mark"
                                                value="{{ $result->mark }}">
                                            <span class="text-danger">
                                                @error('mark')
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
                                        <label>Image/Diagram ( Optional )</label>
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="image"
                                                value="{{ $result->image }}">
                                            <span class="text-danger">
                                                @error('image')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12 col-lg-12">
                                        <label>Exam Question Type</label>
                                        <div class="form-group">
                                            <select class="form-control" name="question_type">
                                                <option value="{{ $result->question_type }}" style="display: none"
                                                    selected>
                                                    {{ $result->question_type }}
                                                </option>
                                                <option value="easy">Easy</option>
                                                <option value="medium">Medium</option>
                                                <option value="hard">Hard</option>
                                            </select>
                                            <span class="text-danger">
                                                @error('question_type')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
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
    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

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
        CKEDITOR.replace( 'editor1' );
	CKEDITOR.replace( 'editor');
    </script>
</body>

</html>
