@extends('../Student/layout')
@section('content')
    <div class="app-content container-fluid center-layout mt-2">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb  bread-heading"><a href="{{ route('student-dashboard') }}">
                                    <li class="">Dashboard</li>
                                </a>
                                <li class="breadcrumb-item active">&nbsp; | User Profile</li>
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
                                        <form action="{{ route('students.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="form_type" value="profile">
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="fa-solid fa-user"></i>User Profile</h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label for="projectinput1">First
                                                                Name</label><input type="text" id="projectinput1"
                                                                class="form-control" placeholder="First Name" name="first_name"
                                                                value="{{ $user->first_name ?? '' }}"
                                                                fdprocessedid="c0g30e">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label for="projectinput2">Last
                                                                Name</label><input type="text" id="projectinput2"
                                                                class="form-control" placeholder="Last Name" name="last_name"
                                                                value="{{ $user->last_name ?? '' }}" fdprocessedid="5bxsn">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label
                                                                for="projectinput3">E-mail</label><input type="text"
                                                                id="projectinput3" class="form-control" placeholder="E-mail"
                                                                name="email" disabled=""
                                                                value="{{ $user->email ?? '' }}" fdprocessedid="0dk56">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label for="projectinput4">Contact
                                                                Number</label><input maxlength="10" type="text"
                                                                id="projectinput4" class="form-control" placeholder="Phone"
                                                                name="contact" disabled=""
                                                                value="{{ $user->contact ?? '' }}" fdprocessedid="b418x">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label for="issueinput3">Enter DOB
                                                            </label><input type="date" id="issueinput3"
                                                                class="form-control" name="date_of_birth" data-toggle="tooltip"
                                                                data-trigger="hover" data-placement="top"
                                                                data-title="Date Opened"
                                                                value="{{ $user->date_of_birth ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-end align-items-center">
                                                <button class="btn btn-danger mr-1 switch-update-section"
                                                    type="button">Update Password</button>
                                                <button class="btn btn-primary form-submit" type="submit"
                                                    fdprocessedid="6hjw8z">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 d-none update-password-section">
                        <div class="card-body">
                            <form action="{{ route('students.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="form_type" value="password">
                                <div class="row mt-4">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="website2">Old Password</label>
                                        <input type="text" class="form-control" name="old_password"
                                            placeholder="Enter Old Password" value="" fdprocessedid="2uade4b">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="website2">New Password</label>
                                        <input type="text" class="form-control" name="new_password"
                                            placeholder="Enter New Password" value="" fdprocessedid="jss5ls">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="website2">Confirm New Password</label>
                                        <input type="text" class="form-control" name="confirm_password"
                                            placeholder="Confirm New Password" value="" fdprocessedid="262l77">
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end align-items-center">
                                    <button class="btn btn-danger mr-1 cancel-update-section" fdprocessedid="52xgzq"
                                        type="button">Cancel</button>
                                    <button class="btn btn-primary form-submit" fdprocessedid="crgo3f"
                                        type="submit">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.switch-update-section', function() {
                    $(this).addClass('d-none')
                    $('.update-password-section').removeClass('d-none')
                });
                $(document).on('click', '.cancel-update-section', function() {
                    $('.update-password-section').addClass('d-none')
                    $('.switch-update-section').removeClass('d-none')
                });
                $(document).on('click', '.form-submit', function(event) {
                    event.preventDefault();
                    var formData = $(this).parent().parent().serialize();
                    

                    $.post({
                        url: '{{ route('students.store') }}', // Replace 'your_route_name' with the actual route name in Laravel
                        data: formData,
                        success: function(response) {
                            console.log(response);
                            if (response.result) {
                                Toastify({
                                    text: response.message,
                                    duration: 3000, // Duration in milliseconds
                                    close: true, // Show close button
                                    gravity: "bottom", // Position (top, bottom)
                                    position: "left", // Position (left, right, center)
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)", // Background color
                                    className: "Toastify__toast-theme--dark"
                                }).showToast();
                                $('.update-password-section').addClass('d-none')
                                $('.switch-update-section').removeClass('d-none')
                            } else {
                                Toastify({
                                    text: response.message,
                                    duration: 3000,
                                    close: true,
                                    gravity: 'bottom',
                                    position: 'left',
                                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)',
                                    className: 'Toastify__toast-theme--light'
                                }).showToast();
                            }

                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(xhr.responseText);
                        }
                    });
                });

            })
        </script>
    @endpush
@endsection
