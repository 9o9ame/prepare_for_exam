<!DOCTYPE html>
<html lang="en">

<head>
    @include('Admin/head')
    <title>Question Bank | Signup</title>
</head>

<body class="hold-transition login-page">
    <div class="login-box" style="width: 600px">
        <div class="login-logo">
            Logo
            {{-- <img src="{{asset('admin_asset/dist/img/logo.png')}}" alt="" width="50%"> --}}
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body">
                <p class="login-box-msg">Teacher's Sign up to start your session</p>

                <form class="form-horizontal" action="{{ url('register_teacher') }}" method="post">
                    @csrf
                    <input type="text" class="form-control" name="role" value="teacher" style="display: none">
                    <div class="input-group mb-3">
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="col-md-12 col-lg-12">
                                    <label>Country Code</label>
                                    <div class="form-group">
                                        <select class="form-control" name="country_code" id="country_code">
                                            <option value="">Select Code</option>
                                        </select>
                                        <span class="text-danger">
                                            @error('country_code')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12 col-lg-12">
                                    <label>Confirm Password</label>
                                    <div class="form-group 1">
                                        <input type="password" class="form-control" name="confirm_password"
                                            placeholder="Enter the Confirm Password">

                                        <span class="text-danger">
                                            @error('confirm_password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button><br>
                            <p class="text-danger text-center"> I have an already account?&nbsp;&nbsp;&nbsp;<a
                                    href="/" class="text-success">Sign In!</a></p>
                        </div>
                        <span class="text-danger text-center">{{ session()->get('error') }}</span>
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
