<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function(){
            var url = "{{ $urlToOpen }}";
                window.open(url, '_blank');
        });
    </script>
</body>
</html>
