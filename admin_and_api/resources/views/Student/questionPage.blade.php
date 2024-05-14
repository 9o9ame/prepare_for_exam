@extends('../Student/layout')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb  bread-heading"><a href="{{ route('student-dashboard') }}">
                                    <li class="breadcrumb-item">Dashboard</li>
                                </a>
                                <li class="breadcrumb-item active">&nbsp; | Sub Topic</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <section id="clpsAnimation" class="clpsAnimation">
                    <div class="row">
                        <div class="col-md-12">
                            <div classname="card">
                                <div classname="card-content">
                                    <div classname="card-body">
                                        @foreach ($data as $topic)
                                            <div class="accordion mb-2" id="accordionExample">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwo"><button
                                                            class="accordion-button collapsed" type="button"
                                                            data-toggle="collapse"
                                                            data-target="#collapseTwo{{ $topic->id ?? '' }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapseOne">{{ $topic->topic ?? '' }}</button>
                                                    </h2>
                                                    <div id="collapseTwo{{ $topic->id ?? '' }}"
                                                        class="accordion-collapse collapse" aria-labelledby="headingOne"
                                                        data-parent="#accordionExample" style="">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <ul>
                                                                        @foreach ($topic->subtopic as $subtopic)
                                                                            <li>
                                                                                <form action="{{ route('fetch-question') }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    <input type="hidden" name="subject_id"
                                                                                        value="{{ $subject_id ?? '' }}">
                                                                                    <input type="hidden" name="exam_id"
                                                                                        value="{{ $exam_id ?? '' }}">
                                                                                    <input type="hidden" name="board_id"
                                                                                        value="{{ $board_id ?? '' }}">
                                                                                    <input type="hidden" name="topic"
                                                                                        value="{{ $topic->topic ?? '' }}">
                                                                                    <input type="hidden" name="subtopic"
                                                                                        value="{{ $subtopic->sub_topic ?? '' }}">
                                                                                    <input type="hidden"
                                                                                        name="question_type"
                                                                                        value="{{ $subtopic->question_type ?? '' }}">
                                                                                        <button type="submit" class="link-button">{{ $subtopic->sub_topic ?? '' }}</button>
                                                                                </form>

                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
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
                </section>
            </div>
        </div>
    </div>
@endsection
