@extends('../Student/layout')
@section('content')
    <section>
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-body">
                    <div class="content-header row">
                        <div class="content-header-left col-md-6 col-12 mb-2">
                            <div class="row breadcrumbs-top d-block">
                                <div class="breadcrumb-wrapper col-12">
                                    <ol class="breadcrumb  bread-heading"><a href="{{route('student-dashboard')}}">
                                            <li class="breadcrumb-item">Dashboard</li>
                                        </a>
                                        <li class="breadcrumb-item active"> &nbsp; | Subject</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end"></div>
                    </div>
                    <div class="row all-contacts">
                        <div class="col-md-3">
                            <h1 class="dash-heading my-3">Subjects</h1>
                            <div class="subject-scroll">
                                <div class="card1">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row mt-1">
                                                <div class="col-md-12 cursor-pointer ">
                                                    <div class="card pull-up">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">Maths</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cursor-pointer ">
                                                    <div class="card pull-up">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">English
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cursor-pointer ">
                                                    <div class="card pull-up">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">Physics
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cursor-pointer ">
                                                    <div class="card pull-up">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">Chemistry
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cursor-pointer ">
                                                    <div class="card pull-up">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">Biology
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h1 class="dash-heading my-3">Boards</h1>
                            <div class="subject-scroll">
                                <div class="card1">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row mt-1">
                                                <div class="col-xl-3 col-lg-6 col-12 cursor-pointer ">
                                                    <div class="card pull-up" style="background-color: rgb(255, 255, 255);">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">Edexcel
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-6 col-12 cursor-pointer ">
                                                    <div class="card pull-up" style="background-color: rgb(255, 255, 255);">
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <div class="media d-flex">
                                                                    <div class="media-body text-left">
                                                                        <h3 class="info text-center subject-name">CIE</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
