<!DOCTYPE html>
<html lang="en">

<head>
    @include('Admin/head')
    <title>Question Bank | Login</title>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            Logo
            {{-- <img src="{{asset('admin_asset/dist/img/logo.png')}}" alt="" width="50%"> --}}
        </div>
        <!-- /.login-logo -->
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
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form class="form-horizontal" action="{{ url('admin/auth') }}" method="post">
                    {{ @csrf_field() }}
                    <div class="input-group mb-3">
                        @if (!Session::has('email'))
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        @endif
                        <span class="text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        @if (!Session::has('password'))
                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                        @endif
                        <span class="text-danger">
                            @error('password')
                                {{ $message }}
                            @enderror
                        </span>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="col-md-12">
                            <div class="checkbox-fade fade-in-primary align-center">
                                Teacher's Signup here! &nbsp;&nbsp;&nbsp;<a href="{{ url('teacher_signup') }}"
                                    class="text-success" style="padding-left: 40px" style="text-decoration:none"> Create
                                    one! </a>
                            </div>

                        </div> --}}
                        <br><br>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        {{-- <span class="text-danger text-center">{{ session()->get('error') }}</span> --}}
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
</body>

</html>
