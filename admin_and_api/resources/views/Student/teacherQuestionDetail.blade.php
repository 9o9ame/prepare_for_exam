@extends('../Student/layout')
@section('content')
    <div class="app-content container-fluid center-layout mt-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb bread-heading"><a href="{{ route('student-dashboard') }}">
                                    <li class="breadcrumb-item">Dashboard
                                </a></li>
                                </a>
                                <li class=" active"> &nbsp; | Question
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
                                            <div class="col-md-6 col-sm-12">
                                                <form action="{{ route('fetch-question') }}" method="post"
                                                    id="fetch-question">
                                                    @csrf
                                                    <input type="hidden" name="subject_id"
                                                        value="{{ $request->subject_id ?? '' }}">
                                                    <input type="hidden" name="exam_id"
                                                        value="{{ $request->exam_id ?? '' }}">
                                                    <input type="hidden" name="board_id"
                                                        value="{{ $request->board_id ?? '' }}">
                                                    <input type="hidden" name="topic"
                                                        value="{{ $request->topic ?? '' }}">
                                                    <input type="hidden" name="subtopic"
                                                        value="{{ $request->subtopic ?? '' }}">
                                                    <button type="submit"
                                                        class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'easy') bg-success text-white @endif btn-outline-success E  fetch-question-sub"
                                                        question_type="easy">Easy</button>
                                                    <button type="submit"
                                                        class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'medium') bg-warning text-white @endif btn-outline-warning M fetch-question-sub"
                                                        question_type="medium">Medium</button>
                                                    <button type="submit"
                                                        class="btn mr-1 @if (isset($request->question_type) && $request->question_type == 'hard') bg-danger text-white @endif btn-outline-danger H fetch-question-sub"
                                                        question_type="hard">Hard</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6 d-flex justify-content-end align-items-center ">
                                                <form action="{{ route('generate_pdf') }}" method="post">
                                                    @csrf
                                                    <div id="pdf-inputs">

                                                    </div>
                                                    <button type="submit" class="btn btn-outline-primary btn-sm d-none generate-pdf">Generate PDF</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row mt-5 change-card">
                                            @include('Student.teacherQuestionDetailContent')
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="exam-modal">
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered customModal">
                    <div class="modal-content">
                        <div class="modal-header" style="border: none;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body exam-modal-content">
                            <div class="row">
                                <div class="col-xl-12 col-md-6 col-12">
                                    <div class="card bg-infos">
                                        <div class="card-content">
                                            <div class="card-body pb-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="text-black">
                                                            <p>what is a line?</p>
                                                        </h6>
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
    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.fetch-question-sub', function(event) {
                    event.preventDefault();

                    var question_type_value = $(this).attr('question_type')
                    if (question_type_value) {
                        if ($(this).hasClass('E')) {
                            $(this).addClass('bg-success text-white');
                            $('.M').removeClass('bg-warning text-white');
                            $('.H').removeClass('bg-danger text-white');
                        } else if ($(this).hasClass('M')) {
                            $(this).addClass('bg-warning text-white');
                            $('.E').removeClass('bg-success text-white');
                            $('.H').removeClass('bg-danger text-white');
                        } else if ($(this).hasClass('H')) {
                            $(this).addClass('bg-danger text-white');
                            $('.M').removeClass('bg-warning text-white');
                            $('.E').removeClass('bg-success text-white');
                        }
                        $('.question_type').remove();
                        $('#fetch-question').append(
                            '<input type="hidden" class="question_type" name="question_type" value="' +
                            question_type_value + '">');
                    }

                    var formData = $('#fetch-question').serialize();
                    // var formData = $(this).serialize();
                    // Send AJAX post request
                    $.post({
                        url: '{{ route('fetch-question') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            $('.change-card').html('')
                            $('.change-card').append(response.question_view)

                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                })
                let pross = false;

                $(document).on('click', '.question-check', function() {
                    if (pross) return;
                    pross = true
                    var question_id = $(this).attr('question_id');
                    var inputsId = 'inputs-div-' + question_id;
                    var inputs = '<div class="total-inputs ' + inputsId +
                        '"><input type="hidden"  name="question_id[]" value="' +
                        question_id + '"></div>';
                    if ($('#pdf-inputs').find('.' + inputsId).length) {
                        $('#pdf-inputs').find('.' + inputsId).remove();
                    } else {
                        $('#pdf-inputs').append(inputs);
                    }
                    let checkedCount = $('.total-inputs').length;
                    if (checkedCount > 0) {
                        $('.generate-pdf').removeClass('d-none');
                    } else {
                        $('.generate-pdf').addClass('d-none');
                    }
                    setTimeout(() => {
                        pross = false;
                    }, 100);
                })
            })
        </script>
    @endpush
@endsection
