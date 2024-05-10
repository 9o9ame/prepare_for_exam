@extends('../Student/layout')
@section('content')
    <section class="section-exam-enrolled">
        <div class="app-content content">
            <div class="content-overlay">
                <div class="content-wrapper">
                    <div class="content-header row"></div>
                    <div class="content-body">
                        <div class="row mt-5">
                            <div class="col-md-12 mb-2">
                                <h3 class="dash-heading">Exams Enrolled In</h3>
                            </div>
                            <div class="col-md-3">
                                <div class="card pull-up cursor-pointer bg-gradient-directional-danger">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex justify-content-center">
                                                <div class="media-body text-left">
                                                    <div class="media-body exam-enrolled-content">
                                                        <h3 class="text-white">IGCSE</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card pull-up cursor-pointer bg-gradient-directional-success">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex justify-content-center">
                                                <div class="media-body text-left">
                                                    <div class="media-body exam-enrolled-content">
                                                        <h3 class="text-white">A Level</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card pull-up cursor-pointer bg-gradient-directional-danger">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex justify-content-center">
                                                <div class="media-body text-left">
                                                    <div class="media-body exam-enrolled-content">
                                                        <h3 class="text-white">International A Level</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card pull-up cursor-pointer bg-gradient-directional-success">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex justify-content-center">
                                                <div class="media-body text-left">
                                                    <div class="media-body exam-enrolled-content">
                                                        <h3 class="text-white">10th Grade (Indian Boards)</h3>
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
    <section class="my-status">
        <div class="app-content content">
            <div class="content-overlay">
                <div class="content-wrapper">
                    <div class="content-header row"></div>
                    <div class="content-body">
                        <div class="row mt-5">
                            <div class="col-md-12 mb-2">
                                <h3 class="dash-heading">My Stats</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="dash-heading1">Total Number Of Questions Attempted</h6>
                                                    <h3 class="info dash-sub-heading">3</h3>
                                                </div>
                                                <div>
                                                    <img src="{{ asset('assets/images/my-status/mathques.png') }}"
                                                        class="card-image float-right" alt="">
                                                </div>
                                            </div>
                                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                                <div class="progress-bar bg-gradient-x-info" role="progressbar"
                                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 80%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="dash-heading1">Total Number Of Questions Marked for Revisit
                                                    </h6>
                                                    <h3 class="info dash-sub-heading">1</h3>
                                                </div>
                                                <div><img src="{{ asset('assets/images/my-status/lastques.png') }}"
                                                        class="card-image float-right" alt=""></div>
                                            </div>
                                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                                <div class="progress-bar bg-gradient-x-warning" role="progressbar"
                                                    aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 65%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="dash-heading1">Total Number Of Questions Marked as Completed
                                                    </h6>
                                                    <h3 class="info dash-sub-heading">0</h3>
                                                </div>
                                                <div><img src="{{ asset('assets/images/my-status/totalquess.png') }}"
                                                        class="card-image float-right" alt=""></div>
                                            </div>
                                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                                <div class="progress-bar bg-gradient-x-success" role="progressbar"
                                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 75%;"></div>
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
    <section class="section-exam-enrolled">
        <div class="app-content content">
            <div class="content-overlay">
                <div class="content-wrapper">
                    <div class="content-header row"></div>
                    <div class="content-body">
                        <div class="row mt-5">
                            <div class="col-md-12 mb-2">
                                <h3 class="dash-heading">Shop Exams</h3>
                            </div>
                            @foreach ($all_exam as $index => $exam)
                                <div class="col-md-3" class="exam-name">
                                    <a href="{{ route('student-subject',$exam->id) }}" class="exam-name">
                                        <div
                                            class="card pull-up cursor-pointer {{ $index % 2 == 0 ? 'bg-gradient-directional-danger' : 'bg-gradient-directional-success' }}">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="media d-flex justify-content-center">
                                                        <div class="media-body text-left">
                                                            <div class="media-body exam-enrolled-content">
                                                                <h3 class="text-white">{{ $exam->exam_name ?? '' }}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
