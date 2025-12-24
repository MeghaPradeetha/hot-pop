<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/heart-logo.png') }}">

    <!-- Scripts -->
    <!-- Google tag (gtag.js) : For advertise-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11453411855"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-11453411855');
    </script>
    <!-- Favicon-->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset ('country_code/style.css') }}"> --}}
    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        .iti {
            width: 100%;
        }
    </style>

    @vite(['resources/sass/main.scss'])

</head>

<body class="fixed-bgs" id="page-top">

    <!-- Header Section Ends -->
    @yield('content')

    @include('layouts.footer')

    {{-- <script src="{{ asset ('country_code/script.js') }}"></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script> --}}
    @stack('js')

    <script>
        function togglePasswordVisibility(FieldId) {
            var passwordField = document.getElementById(FieldId);
            var icon = document.getElementById(FieldId + '_img');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>

</body>

</html>
