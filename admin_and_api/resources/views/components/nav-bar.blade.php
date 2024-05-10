@php
if (session()->has('STUDENT_LOGIN')) {
    $email = session()->get('email');
    $user = App\Models\StudentProfile::where('email', $email)->first();
}
@endphp
<nav class="navbar navbar-expand-lg bg-body-tertiary bg-color">
    <div class="container-fluid">
        <img src="{{ asset('assets/images/icons/logo.png') }}" alt="logo.png" class="img-fluid logo">

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 left-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-text" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Exams
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
            </ul>
            <div class="right-header d-flex">
                <div class="user col d-flex justify-content-end">
                    <div class="d-none d-lg-flex align-items-center"><a class="header-count-box"
                            href="/pricingtable">Your Subscription will expire in <strong>1 day</strong></a><a
                            href="/pricingtable"><a class="header-count-box">Exams : <span>{{$user->exam ?? ''}}</span></a></a><a
                            href="/pricingtable"><a class="header-count-box">Subjects : <span>{{$user->subject ?? ''}}</span></a></a><a
                            href="/pricingtable"><a class="header-count-box">Board : <span>{{$user->board ?? ''}}</span></a></a>
                    </div>
                </div>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link  text-dark " href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="user-name nav-text">{{ $user->first_name ?? '' }}
                                {{ $user->last_name ?? '' }}</span>
                            <img src="{{ asset('assets/images/icons/usericon.png') }}" alt="usericon.png"
                                class="img-fluid avatar">
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">User Profile</a></li>
                            <li><a class="dropdown-item" href="#">Subscription History</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a href="{{ url('admin/logout') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Sign out
                                </a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</nav>
