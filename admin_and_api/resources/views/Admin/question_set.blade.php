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
        /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        margin-left: 23%;
        margin-top: 10%;
        width: 70%;
        }

        /* The Close Button */
        .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        }

        .close:hover,
        .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
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
                            <h1>Manage Question Set</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Manage Question Set</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Model -->
            <div class="card-body d-flex justify-content-end">
                <a type="button" class="btn btn-primary" href="{{ url('admin/add_question_setpage') }}">
                    Add
                </a>
                <a type="button" style="margin-left: 10px" id="myBtn" class="btn btn-primary" href="#">
                    Upload Bulk
                </a>
            </div>
            <!-- The Modal -->
            <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Upload a File</h2>
                <form action={{ url('admin/load_ppt') }} method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="file">Choose a file:</label>
                    <input type="file" id="file" name="file" accept="application/pdf">
                    <button class="btn btn-primary" type="submit">Upload</button>
                </form>
                </div>
            
            </div>
            {{-- <div class="modal fade" id="modal-default">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Question Set</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action={{ url('admin/add_question_set') }} enctype="multipart/form-data"
                                method='post'>
                                @csrf
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
                                        <select class="form-control" name="subject">
                                            <option value="" style="display: none">Select Subject</option>
                                            @foreach ($subject as $subject)
                                                <option value="{{ $subject->id }}">
                                                    {{ $subject->id }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">
                                            @error('subject')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Topic</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="topic"
                                            placeholder="Enter Topic Name">
                                        <span class="text-danger">
                                            @error('topic')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Sub-Topic</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="sub_topic"
                                            placeholder="Enter Sub-Topic Name">
                                        <span class="text-danger">
                                            @error('sub_topic')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Question</label>
                                    <div class="form-group">
                                        <textarea type="text" class="form-control" name="question" id="editor"> </textarea>
                                        <span class="text-danger">
                                            @error('question')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Year</label>
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="year"
                                            placeholder="Enter The Year">
                                        <span class="text-danger">
                                            @error('year')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Marks</label>
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="mark"
                                            placeholder="Enter The Marks">
                                        <span class="text-danger">
                                            @error('mark')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Image/Diagram ( Optional )</label>
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="image">
                                        <span class="text-danger">
                                            @error('image')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label>Exam Style Question</label>
                                    <div class="form-group">
                                        <select class="form-control" name="exam_style_questions">
                                            <option value="" style="display: none">Select Exam Style Question
                                            </option>
                                            <option value="easy">Easy</option>
                                            <option value="medium">Medium</option>
                                            <option value="hard">Hard</option>
                                        </select>
                                        <span class="text-danger">
                                            @error('exam_style_questions')
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
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Topic</th>
                                        <th>Sub-Topic</th>
                                        <th>Question</th>
                                        <th>Mark Schema</th>
                                        <th>Year</th>
                                        <th>Marks</th>
                                        <th>Image/Diagram <br>&nbsp;&nbsp;&nbsp;( Optional )</th>
                                        <th>Exam Question Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $a=1; @endphp
                                    @foreach ($question as $data)
                                        <tr>
                                            <td>{{ $a++ }}</td>
                                            <td>{{ $data->subject_code }}</td>
                                            <td>{{ $data->subject_name }}</td>
                                            <td>{{ $data->topic }}</td>
                                            <td>{{ $data->sub_topic }}</td>
                                            <td>{{ strip_tags($data->question) }}</td>
                                            <td>{{ $data->mark_schema }}</td>
                                            <td>{{ $data->year }}</td>
                                            <td>{{ $data->mark }}</td>
                                            <td>
                                                @if ($data->image == '-')
                                                    {{-- <a href="{{ asset('Images/' . $data->image) }}" width="1920px"
                                                        height="1080px" controls>View</a> --}}
                                                @else
                                                    <a href="{{ asset('Images/' . $data->image) }}" width="1920px"
                                                        height="1080px" controls>View</a>
                                                @endif
                                            </td>
                                            <td>{{ $data->question_type }}</td>
                                            <td>
                                                <a type="button" class="btn btn-primary btn-sm"
                                                    href="{{ url('admin/update_question_set/edit/' . $data->id) }}"
                                                    name="update"><i class="fa fa-edit"></i></a>

                                                <a class="btn btn-danger btn-sm deleteBtn"
                                                    href="{{ url('admin/show_question_set/delete/') . '/' . $data->id }}"
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
    <script src="https://cdn.ckeditor.com/ckeditor5/35.2.1/classic/ckeditor.js"></script>

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
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        
        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        </script>
</body>

</html>
