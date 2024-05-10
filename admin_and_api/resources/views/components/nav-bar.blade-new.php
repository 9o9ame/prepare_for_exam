@php
    if (session()->has('STUDENT_LOGIN')) {
        $email = session()->get('email');
        $user = App\Models\StudentProfile::where('email', $email)->first();
    }
@endphp
<nav class="navbar navbar-expand-lg navbar-light bg-light"><img src="{{ asset('assets/images/icons/logo.png') }}"
        class="main-logo cursor-pointer" alt="logo"><button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
        aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item dropdown show"><a class="nav-link dropdown-toggle ml-5" href="#"
                    id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">Exams<i class="fa-solid fa-chevron-down"></i></a>
                <ul class="dropdown-menu show" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">IGCSE</a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li class="dropdown-item">Maths<ul class="dropdown-menu dropdown-submenu">
                                    <li class="dropdown-item cursor-pointer">Edexcel</li>
                                    <li class="dropdown-item cursor-pointer">CIE</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a class="dropdown-item" href="#">A Level</a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li class="dropdown-item">Maths<ul class="dropdown-menu dropdown-submenu">
                                    <li class="dropdown-item cursor-pointer">CBSE</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a class="dropdown-item" href="#">International A Level</a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li class="dropdown-item">Maths<ul class="dropdown-menu dropdown-submenu">
                                    <li class="dropdown-item cursor-pointer">Edexcel</li>
                                    <li class="dropdown-item cursor-pointer">CIE</li>
                                </ul>
                            </li>
                            <li class="dropdown-item">Physics<ul class="dropdown-menu dropdown-submenu">
                                    <li class="dropdown-item cursor-pointer">Edexcel</li>
                                    <li class="dropdown-item cursor-pointer">CIE</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a class="dropdown-item" href="#">10th Grade (Indian Boards)</a>
                        <ul class="dropdown-menu dropdown-submenu">
                            <li class="dropdown-item">Maths<ul class="dropdown-menu dropdown-submenu">
                                    <li class="dropdown-item cursor-pointer">CBSE</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="user col d-flex justify-content-end">
            <div class="d-none d-lg-flex align-items-center"><a class="header-count-box" href="/pricingtable">Your
                    Subscription will expire in <strong>1 day</strong></a><a href="/pricingtable"><a
                        class="header-count-box">Exams : <span>3</span></a></a><a href="/pricingtable"><a
                        class="header-count-box">Subjects : <span>3</span></a></a><a href="/pricingtable"><a
                        class="header-count-box">Board : <span>2</span></a></a></div>
        </div>
        <div class="dropdown"><a class="dropdown-toggle nav-link dropdown-user-link" data-toggle="dropdown"><span
                    class="mr-1 user-name text-bold-700">{{$user->first_name ?? ''}} {{$user->last_name ?? ''}}</span><img src="{{asset('assets/images/icons/usericon.png')}}" class="avatar avatar-online" alt="usericon"></a>
            <div class="dropdown-menu dropdown-menu-right"><a class="" href="/user-profile"><a
                        class="dropdown-item">User Profile</a></a><a class="" href="/subscription"><a
                        class="dropdown-item">Subscription History</a></a>
                <p class="d-lg-none d-flex dropdown-item">Your Stats:</p>
                <div class="d-lg-none d-flex align-items-center flex-column" style="gap: 10px;"><a
                        class="header-count-box" href="/pricingtable">Your Subscription will expire in <strong>1
                            day</strong></a><a class="header-count-box d-block w-75" href="/pricingtable">Exams :
                        <span>3</span></a><a class="header-count-box d-block w-75" href="/pricingtable">Subjects :
                        <span>3</span></a><a class="header-count-box d-block w-75" href="/pricingtable">Board :
                        <span>2</span></a></div><a class="dropdown-item">Sign Out</a>
            </div>
        </div>
    </div>
</nav>
