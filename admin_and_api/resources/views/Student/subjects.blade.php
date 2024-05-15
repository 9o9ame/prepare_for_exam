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
                                    <ol class="breadcrumb  bread-heading"><a href="{{ route('student-dashboard') }}">
                                            <li class="breadcrumb-item">Dashboard</li>
                                        </a>
                                        <li class="breadcrumb-item active"> &nbsp; | Subject</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="row subscription-div d-none">
                            <div class="col-md-12 d-flex justify-content-end">
                                <form action="{{ route('activate_board') }}" method="post" id="subscription-form">
                                    @csrf
                                    <input type="hidden" name="user_board" id="user_board" value="{{$user->board ?? ''}}">
                                    <div id="subscription-inputs">

                                    </div>
                                    <button type="submit"
                                        class="text-capitalize  btn btn-primary rounded subscription-btn">Activate
                                        Subscription <span class="counter">0</span>
                                    </button>
                                </form>
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
                                                @foreach ($subjects as $key => $subject)
                                                    <div class="col-md-12 cursor-pointer update-subject "
                                                        subject_id="{{ $subject->id ?? '' }}" exam_id="{{ $examId ?? '' }}">
                                                        <div
                                                            class="card pull-up subjects @if ($key == 0) subject-active @endif subject-{{ $subject->id ?? '' }}">
                                                            <div class="card-content">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="media-body text-left">
                                                                            <h3 class="info text-center subject-name">
                                                                                {{ $subject->subject_name ?? '' }}</h3>
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
                        <div class="col-md-9">
                            <h1 class="dash-heading my-3">Boards</h1>
                            <div class="subject-scroll">
                                <div class="card1">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row mt-1 board_view">
                                                @php
                                                    $subjectId = $subjects[0]->id;
                                                    $boards = $subjects[0]
                                                        ->boards()
                                                        ->wherePivot('exam_id', $examId)
                                                        ->get();
                                                @endphp
                                                @include('Student.boards')
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
    @push('scripts')
        <script>
            $(document).ready(function() {
                // const swal = require('sweetalert2');
                $(document).on('click', '.update-subject', function() {
                    // Retrieve CSRF token value from meta tag
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Define your arguments
                    var exam_id = $(this).attr('exam_id');
                    var subject_id = $(this).attr('subject_id');
                    $('.subjects').removeClass('subject-active');
                    $('.subject-' + subject_id).addClass('subject-active');

                    // Create data object with CSRF token and arguments
                    var data = {
                        _token: csrfToken,
                        exam_id: exam_id,
                        subject_id: subject_id
                    };

                    // Send AJAX post request
                    $.post({
                        url: '{{ route('update-boards') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: data,
                        success: function(response) {
                            // Handle successful response
                            $('.board_view').html('');
                            $('.subscription-div').addClass('d-none');
                            $('.counter').text(0);
                            $('.board_view').append(response.board_view);
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                });
                $(document).on('click', '.subject-div', function() {
                    $(this).toggleClass("activate");
                    var activeCount = $('.subject-div.activate').length;
                    // alert(activeCount)
                    var exam_id = $(this).attr('exam_id');
                    var subject_id = $(this).attr('subject_id');
                    var board_id = $(this).attr('board_id');
                    var inputsId = 'inputs-div-' + exam_id + '-' + subject_id + '-' + board_id;
                    if (activeCount >= 1) {
                        $('.subscription-div').removeClass('d-none');
                        var inputs = '<div class="' + inputsId + '"><input type="hidden" id="exam_id-' +
                            exam_id + '" name="exam_id[]" value="' +
                            exam_id + '"><input type="hidden" id="subject_id-' + subject_id +
                            '" name="subject_id[]" value="' + subject_id +
                            '"><input type="hidden" id="board_id-' + board_id + '" name="board_id[]" value="' +
                            board_id + '"></div>';

                        // Check if div with specified class already exists
                        // alert(inputsId)
                        if ($('#subscription-inputs').find('.' + inputsId).length) {
                            $('#subscription-inputs').find('.' + inputsId).remove();
                        } else {
                            $('#subscription-inputs').append(inputs);
                        }

                        $('.counter').text(activeCount);
                    } else {
                        if ($('#subscription-inputs').find('.' + inputsId).length) {
                            $('#subscription-inputs').find('.' + inputsId).remove();
                        }
                        $('.subscription-div').addClass('d-none');
                    }
                });


                $(document).on('submit', '#subscription-form', function(event) {
                    event.preventDefault();
                    var user_board = $('#user_board').val()
                    var board_length = $('input[name="board_id[]"]').length;
                    if (user_board >= board_length) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You want to activate this subscription!",
                            icon: 'warning', // 'warning' replaces 'type'
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, activate it!'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                var formData = $('#subscription-form').serialize();

                                // Send AJAX post request
                                $.post({
                                    url: '{{ route('activate_board') }}', // Replace 'your_route_name' with the actual route name in Laravel
                                    data: formData,
                                    success: function(response) {
                                        console.log(response);
                                        if (response.status) {
                                            swal.fire(
                                                'Activated!',
                                                'Your subscription has been activated.',
                                                'success'
                                            )
                                            window.location.reload();
                                        }

                                        console.log(response);
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle errors
                                        console.error(xhr.responseText);
                                    }
                                });
                            }

                        });
                    } else {
                        Swal.fire({
                            title: "You don't have credits",
                            text: 'Do you want to buy credits?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, buy it!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/fetch-subscription-panel';
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
