@extends('../Student/layout')
@section('content')
    <div class="app-content container-fluid center-layout mt-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb bread-heading"><a href="{{route('student-dashboard')}}">
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
                                @include('Student.questionDetailContent')
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
                        $(this).addClass('btn-info text-white')
                        $(this).siblings().removeClass('btn-info text-white')
                    } else if (type == 'revision') {
                        $('.non-ppt').addClass('d-none')
                        $('.ppt').removeClass('d-none')
                        $(this).addClass('btn-info text-white')
                        $(this).siblings().removeClass('btn-info text-white')
                    }
                })
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
                    var formData = $(this).parent().serialize();
                    console.log('hello');
                    $.post({
                        url: '{{ route('fetch-question-detail') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: formData, // Replace 'your_route_name' with the actual route name in Laravel
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
                    if ($(this).hasClass('complete')) {
                        $(this).addClass('bg-success text-white');
                        $('.revisit').removeClass('bg-danger text-white');
                        $(this).addClass('d-none')
                        $('.complete-spinner').removeClass('d-none')
                    } else if ($(this).hasClass('revisit')) {
                        $(this).addClass('bg-danger text-white');
                        $('.complete').removeClass('bg-success text-white');
                        $(this).addClass('d-none')
                        $('.revisit-spinner').removeClass('d-none')
                    }
                    $.get({
                        url: url, // Replace 'your_route_name' with the actual route name in Laravel
                        success: function(response) {
                            $('.complete, .revisit').removeClass('d-none')
                            $('.complete-spinner, .revisit-spinner').addClass('d-none');

                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                })
                $(document).on('click', '.update-notes', function() {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var this_update = $(this);
                    var q_id = this_update.attr('q_id');
                    var notes = $('#userinput8').val();


                    $(this).addClass('d-none')
                    $('.update-spinner').removeClass('d-none')
                    var data = {
                        _token: csrfToken,
                        question_id: q_id,
                        notes: notes
                    };
                    $.post({
                        url: '{{ route('update_question_notes') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: data,
                        success: function(response) {
                            this_update.removeClass('d-none')
                            $('.update-spinner').addClass('d-none');
                            console.log(response);
                            Toastify({
                                text: response.message,
                                duration: 3000, // Duration in milliseconds
                                close: true, // Show close button
                                gravity: "bottom", // Position (top, bottom)
                                position: "left", // Position (left, right, center)
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)", // Background color
                                className: "Toastify__toast-theme--dark"
                            }).showToast();
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
