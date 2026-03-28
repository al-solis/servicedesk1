<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Incident & Support Management System')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/flowbite.min.js', 'resources/js/select2.min.js', 'resources/js/chart.js', 'resources/js/apexcharts.min.js', 'resources/js/preline.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    @include('layouts.navbar') <!-- Include the navbar -->
    <div class="container">

        <div class="content-area" id="content-area">
            @yield('content') <!-- Main content will be replaced dynamically -->
        </div>
    </div>

    {{-- @include('layouts.footer') <!-- Include the footer --> --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts" integrity="sha384-..." crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3" integrity="sha384-..." crossorigin="anonymous">
    </script>

</body>

</html>
