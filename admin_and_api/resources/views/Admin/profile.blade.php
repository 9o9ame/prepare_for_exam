<!DOCTYPE html>
<html lang="en">

<head>
    <title>Question Bank | User Profile</title>
    @include('Admin/head')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
  <img class="animation__shake" src="{{asset('admin_asset/dist/img/mm.ico')}}" alt="AdminLTELogo" height="60" width="60">
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
                            <h1>User Profile</h1>
                        </div>
                        <div class="col-sm-6">
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
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active">User Profile</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-pane">
                                <form class="form-horizontal" action="{{url('/admin/passwordsave')}}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="enteroldpassword" class="col-sm-2 col-form-label">Enter Old Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="oldpassword" id="enteroldpassword" placeholder="Enter Old Password">
                                            <span class="text-danger">
                                                @error('oldpassword'){{ $message }}@enderror
                                            </span </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="confirmnewpassword" class="col-sm-2 col-form-label">Enter New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Enter New Password">
                                            <span class="text-danger">
                                                @error('newpassword'){{ $message }}@enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="enternewpassword" class="col-sm-2 col-form-label">Enter Confirmed New Password</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="confirmpassword" id="enterconfirmpassword" placeholder="Enter Confirm New Password">
                                            <span class="text-danger">
                                                @error('confirmpassword'){{ $message }}@enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success mr-3">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
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
    <!-- AdminLTE App -->
    <script src="{{asset('admin_asset/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('admin_asset/dist/js/demo.js')}}"></script>
</body>

</html>