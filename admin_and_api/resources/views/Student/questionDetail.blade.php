@extends('../Student/layout')
@section('content')
    <div class="app-content container-fluid center-layout mt-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb bread-heading"><a href="route('student-dashboard')">
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
                        <div class="col-md-12 change-card">
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
                                                        class="btn mr-1 btn-outline-success fetch-question-sub"
                                                        question_type="easy">Easy</button>
                                                    <button type="submit"
                                                        class="btn mr-1 btn-outline-warning fetch-question-sub"
                                                        question_type="medium">Medium</button>
                                                    <button type="submit"
                                                        class="btn mr-1 btn-outline-danger fetch-question-sub"
                                                        question_type="hard">Hard</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6  d-flex justify-content-end">
                                                <button type="button" class="btn btn-sm btn-info mr-1 style"
                                                    types="question">Exam Style
                                                    Questions</button>
                                                <button type="button" class="btn btn-sm btn-outline-info mr-1 style"
                                                    types="revision">RevisionNotes</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2 question_detail">
                                            @include('Student.questionDetailContent')
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
    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('click', '.style', function() {
                    var type = $(this).attr('types')
                    if (type == 'question') {
                        $('.ppt').addClass('d-none')
                        $('.non-ppt').removeClass('d-none')
                    } else if (type == 'revision') {
                        $('.non-ppt').addClass('d-none')
                        $('.ppt').removeClass('d-none')
                    }
                })
                $(document).on('click', '.fetch-question-sub', function(event) {
                    event.preventDefault();

                    var question_type_value = $(this).attr('question_type')
                    $('.question_type').remove();
                    $('#fetch-question').append(
                        '<input type="hidden" class="question_type" name="question_type" value="' +
                        question_type_value + '">');

                    var formData = $('#fetch-question').serialize();
                    // var formData = $(this).serialize();
                    // Send AJAX post request
                    $.post({
                        url: '{{ route('fetch-question') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            $('.question_detail').html('')
                            $('.question_detail').append(response.question_view)

                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                })
                $(document).on('click', '.mark-scheme', function() {
                    var showHideElement = $('.show-hide'); // Store the jQuery object
                    var showHideText = showHideElement.text(); // Get the text of the element
                    
                    if (showHideText == 'Show') {
                        showHideElement.text('Hide');
                        $('.mark-scheme-content').removeClass('d-none');
                    } else {
                        showHideElement.text('Show');
                        $('.mark-scheme-content').addClass('d-none');
                    }
                });
                $(document).on('click', '.question-detail', function() {
                    var url = $(this).attr('url')
                    $.get({
                        url: url, // Replace 'your_route_name' with the actual route name in Laravel
                        success: function(response) {
                            // Handle successful response
                            console.log(response);
                            $('.change-card').html('')
                            $('.change-card').append(response.view_card)
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                })
                $(document).on('click', '.mark-as', function() {
                    var url = $(this).attr('url')
                    $.get({
                        url: url, // Replace 'your_route_name' with the actual route name in Laravel
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                })

            })
        </script>
    @endpush
@endsection
