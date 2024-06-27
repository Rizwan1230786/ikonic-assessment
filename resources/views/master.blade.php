<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assessment task master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link type="text/css" rel="preload" href="{{ asset('Toaster') }}/toast.style.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <style>
        .field-error {
            color: red
        }
    </style>
</head>

<body>
    @yield('content')
    @include('layouts.scripts')
    @stack('footer_scripts')
</body>

</html>
