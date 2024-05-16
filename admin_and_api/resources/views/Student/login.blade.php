<!doctype html>
<html lang="en">

<x-admin-header-css></x-admin-header-css>
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.min.css')}}" />
<body>
    <!--wrapper-->
    {{-- <x-nav-bar></x-nav-bar> --}}
    <div class="wrapper">
        <div class="app-content center-layout  siginpage backgroudimages   ">
            <div class="content-wrapper h-100">
                <div class="content-body h-100">
                    <section class="row navbar-flexbox-container  h-100">
                        <div class="col-12 d-flex align-items-center justify-content-center m-0">
                            <div class="col-lg-4 col-md-8 col-10  p-0">
                                <div class="card border-grey border-lighten-3 m-0">
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
                                    <div class="card-header  border-0">
                                        <div class="card-title text-center"><img src="{{('assets/logos/main_logo.png')}}"
                                                alt="branding logo" class="siginlogo"></div>
                                    </div>
                                    <div class="card-content">
                                        <p
                                            class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2">
                                            <span>Sign In to your account</span>
                                        </p>
                                        <div class="card-body pt-0">
                                            <form class="form-horizontal"
                                                action="{{ url('admin/auth') }}" method="post">
                                                @csrf
                                                <label for="username" class="form-entry-heading">Contact No or Email</label>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" placeholder="Contact No or Email" value="{{old('email')}}" name="email" >
                                                    @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <div class="form-control-position">
                                                        <i class="fa-solid fa-phone"></i>
                                                    </div>
                                                </fieldset>
                                                <label for="username" class="form-entry-heading">Enter Password</label>
                                                <div class="position-relative">
                                                    <fieldset class="form-group position-relative has-icon-left">
                                                        <input type="password" class="form-control " name="password" placeholder="password" value="">
                                                        @error('password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="form-control-position">
                                                            <i class="fa-solid fa-lock"></i>
                                                        </div>
                                                    </fieldset>
                                                    <i class="fas fa-eye position-absolute" style="top: 14px; right: 6px;"></i>
                                                </div>
                                                <div class="w-100 d-flex">
                                                    <button class="btn btn-info w-50 my-1 btn-rounded  rounded-pill mx-auto" type="submit">Sign In</button>
                                                </div>
                                            </form>
                                            <p class="text-center">A New Student ? Click here to &nbsp;<a href="{{route('signup')}}">Sign Up</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <x-admin-footer-js></x-admin-footer-js>
</body>

</html>
