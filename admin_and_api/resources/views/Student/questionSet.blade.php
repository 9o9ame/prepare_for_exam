@extends('../Student/layout')
@section('content')
    <div class="app-content container-fluid center-layout mt-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb bread-heading"><a href="/">
                                    <li class="breadcrumb-item"><a>Dashboard</a></li>
                                </a>
                                <li class="breadcrumb-item active"> &nbsp; | Question</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12"><button type="button"
                                                    class="btn mr-1 btn-success">Easy</button><button type="button"
                                                    class="btn mr-1 btn-outline-warning">Medium</button><button
                                                    type="button" class="btn mr-1 btn-outline-danger">Hard</button></div>
                                            <div class="col-md-6  d-flex justify-content-end"><button type="button"
                                                    class="btn btn-sm btn-info mr-1">Exam Style Questions</button><button
                                                    type="button" class="btn btn-sm btn-outline-info mr-1">Revision
                                                    Notes</button></div>
                                        </div>
                                        <div class="row mt-2">
                                            @foreach ($question as $key => $q)
                                                <div class="col-xl-12 col-md-6 col-12">
                                                    <div class="card cursor-pointer">
                                                        <div class="card-content">
                                                            <div class="card-body pb-1 cursor-pointer">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h4 class="mb-1"><i class="cc XRP"
                                                                                title="XRP"></i>Question
                                                                            {{ $key + 1 }}</h4>
                                                                        <h6 class="text-black">
                                                                            <p>{!! $q->question ?? '' !!}</p>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
