<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
        <img src="{{ asset('admin_asset/dist/img/mm.ico') }}" alt="AdminLTE Logo" class="brand-image">
        <span class="brand-text font-weight-light" style="letter-spacing:2px">Question Bank</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('admin_asset/dist/img/user.png') }}" class="elevation-2 img-circle" alt="User Image">
            </div>
            <div class="info">
                <a class="d-block">Admin</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Query
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/fetchbystatus/all') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Queries</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/pendingqueries') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pending</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/completedqueries') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Completed</p>
                            </a>
                        </li>
                        @foreach ($status as $st)
                            <li class="nav-item">
                                <a href="{{ url('admin/fetchbystatus') }}/{{ $st->status }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ ucfirst($st->status) }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li> --}}
                {{-- @if (Auth::user()->role == 'admin') --}}
                <li class="nav-item">
                    <a href="{{ url('admin/show_student_profile') }}" class="nav-link">
                        <i class="nav-icon fas fa-id-badge"></i>
                        <p>
                            Student Profile
                        </p>
                    </a>
                </li>
                {{-- @endif --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Setup
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/show_school') }}" class="nav-link">
                                <i class="fas fa-building nav-icon"></i>
                                <p>School</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/show_exam') }}" class="nav-link">
                                <i class="fas fa-newspaper nav-icon"></i>
                                <p>Exam</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/show_board') }}" class="nav-link">
                                <i class="fas fa-graduation-cap nav-icon"></i>
                                <p>Board</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/show_subject') }}" class="nav-link">
                                <i class="fa fa-book nav-icon"></i>
                                <p>Subject</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/show_class') }}" class="nav-link">
                                <i class="fa fa-copyright nav-icon"></i>
                                <p>Class</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/show_country') }}" class="nav-link">
                                <i class="fas fa-flag-checkered nav-icon"></i>
                                <p>Country</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/show_country') }}" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Country
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ url('admin/show_question_set') }}" class="nav-link">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Question Set
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/show_previous_year_paper') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Previous Year Paper
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/show_revision_notes') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Revision Notes
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/profile') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/subscription_plan_detail') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Subscription Plan
                        </p>
                    </a>
                </li>
				 <li class="nav-item">
                    <a href="{{ url('admin/upload') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                           Upload Section
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/show_video') }}" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Video
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/adminmanagement') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Admin Management
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/adminemail') }}" class="nav-link">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Admin Email
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/emailsubscriber') }}" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Email Subscribers
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/show_testimonial') }}" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Testimonials
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/show_clientlogo') }}" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Client Logo
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/show_resume') }}" class="nav-link">
                        <i class="nav-icon far fa-address-book"></i>
                        <p>
                            Resume
                        </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ url('admin/loan') }}" class="nav-link"> --}}
                {{-- <i class="nav-icon far fa-user"></i> --}}
                {{-- <i class="nav-icon far fa-address-book"></i>
                        <p>
                            Loan Enquiry
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
