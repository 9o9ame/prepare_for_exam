<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.min.css')}}" />
    <title>Thanks for Payment</title>
</head>

<body>
    <div class="container"
        style="display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column;"><img
            src="{{ asset('assets/images/payment_success.webp') }}" alt="payment_success" style="width: 250px;">
        <h4 style="margin-top: 40px; text-align: center; line-height: 1.5; display: flex; flex-direction: column;">
            <strong>Payment Successful!</strong><br> Your payment has been successfully processed. <a
                href="{{ route('student-dashboard') }}"><button class="text-capitalize  btn btn-outline-primary mt-4">Go
                    to Dashboard</button></a>
        </h4>
    </div>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
</body>

</html>
