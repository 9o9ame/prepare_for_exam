@extends('../Student/layout')
@section('content')
    <section>
        <div class="main-content position-relative">
            <div class="p-3 p-xxl-5 bg-primary after-header">
                <div class="container-fluid px-0 pb-md-5 mb-md-5">
                    <div class="row pb-5 mb-5">
                        <div class="col-md-10 col-lg-12 col-xxl-6 text-center p-4 pb-0 pricing p-md-5 mx-auto">
                            <h1 class="display-4 dash-headings text-white">Pricing...</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-3 px-xxl-5 pb-3 pb-xxl-5 pricing-cards">
                <div class="container-fluid px-0 top-less">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-monthly" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <div class="card profile-card-with-cover pull-up w-100">
                                        <div class="card-content card-deck text-center">
                                            @foreach ($subscriptions as $subscription)
                                                <div class="card ">
                                                    <div class="card-header pb-0">
                                                        <h2 class="my-0 font-weight-bold">
                                                            {{ $subscription->subscription_name ?? '' }}</h2>
                                                    </div>
                                                    <div class="card-body">
                                                        <h1 class="pricing-card-title">
                                                            ${{ $subscription->subscription_price ?? '' }}<small
                                                                class="text-muted">/ mo</small>
                                                        </h1>
                                                        <ul class="list-unstyled mt-2 mb-2">
                                                            <li class=" font-weight-bold">Validity :
                                                                {{ $subscription->sv_month ?? '' }} Month</li>
                                                            <li class="font-weight-bold">No. Of Boards :
                                                                {{ $subscription->no_of_board ?? '' }}</li>
                                                            <li class="font-weight-bold">No. Of Exams :
                                                                {{ $subscription->no_of_exam ?? '' }}</li>
                                                            <li class=" font-weight-bold">No. Of Subjects :
                                                                {{ $subscription->no_of_subject ?? '' }}</li>
                                                        </ul>
                                                        <form action="{{ route('create_order_request') }}" method="post"
                                                            id="subscription">
                                                            @csrf
                                                            <input type="hidden" name="subscription_id"
                                                                value="{{ $subscription->id ?? '' }}">
                                                            <button type="submit"
                                                                class="btn btn-primary w-50 my-1 btn-rounded  rounded-pill mx-auto purchase-btn"><span>Purchase</span></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
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
                // $(document).on('click', '.update-subject', function(event) {
                //     event.preventDefault();
                //     // Retrieve CSRF token value from meta tag
                //     var csrfToken = $('meta[name="csrf-token"]').attr('content');

                //     // Define your arguments
                //     var exam_id = $(this).attr('exam_id');
                //     var subject_id = $(this).attr('subject_id');
                //     $('.subjects').removeClass('subject-active');
                //     $('.subject-' + subject_id).addClass('subject-active');

                //     // Create data object with CSRF token and arguments
                //     var data = {
                //         _token: csrfToken,
                //         exam_id: exam_id,
                //         subject_id: subject_id
                //     };

                //     // Send AJAX post request
                //     $.post({
                //         url: '{{ route('update-boards') }}', // Replace 'your_route_name' with the actual route name in Laravel
                //         data: data,
                //         success: function(response) {
                //             // Handle successful response
                //             $('.board_view').html('');
                //             $('.subscription-div').addClass('d-none');
                //             $('.counter').text(0);
                //             $('.board_view').append(response.board_view);
                //             console.log(response);
                //         },
                //         error: function(xhr, status, error) {
                //             // Handle errors
                //             console.error(xhr.responseText);
                //         }
                //     });
                // });
                // $(document).on('click', '.subject-div', function() {
                //     $(this).toggleClass("activate");
                //     var activeCount = $('.subject-div.activate').length;
                //     var exam_id = $(this).attr('exam_id');
                //     var subject_id = $(this).attr('subject_id');
                //     var board_id = $(this).attr('board_id');
                //     var inputsId = 'inputs-div-' + exam_id + '-' + subject_id + '-' + board_id;
                //     if (activeCount >= 1) {
                //         $('.subscription-div').removeClass('d-none');
                //         var inputs = '<div class="' + inputsId + '"><input type="hidden" id="exam_id-' +
                //             exam_id + '" name="exam_id[]" value="' +
                //             exam_id + '"><input type="hidden" id="subject_id-' + subject_id +
                //             '" name="subject_id[]" value="' + subject_id +
                //             '"><input type="hidden" id="board_id-' + board_id + '" name="board_id[]" value="' +
                //             board_id + '"></div>';

                //         // Check if div with specified class already exists
                //         // alert(inputsId)
                //         if ($('#subscription-inputs').find('.' + inputsId).length) {
                //             $('#subscription-inputs').find('.' + inputsId).remove();
                //         } else {
                //             $('#subscription-inputs').append(inputs);
                //         }

                //         $('.counter').text(activeCount);
                //     } else {
                //         if ($('#subscription-inputs').find('.' + inputsId).length) {
                //             $('#subscription-inputs').find('.' + inputsId).remove();
                //         }
                //         $('.subscription-div').addClass('d-none');
                //     }
                // });


                $(document).on('submit', '#subscription', function(event) {
                    event.preventDefault();
                    var formData = $('#subscription').serialize();

                    // Send AJAX post request
                    $.post({
                        url: '{{ route('create_order_request') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: formData,
                        success: function(response) {
                            console.log(response.status);
                            if (response.status == true) {
                                window.location.href = response.url;

                            } else {
                                alert(response.message);
                            }

                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
