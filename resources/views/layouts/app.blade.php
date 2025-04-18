<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Online IT Service Desk')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}
    
</head>
<body>   
    @include('layouts.navbar') <!-- Include the navbar -->  
    <div class="container">       

        <div class="content-area" id="content-area">  
            @yield('content') <!-- Main content will be replaced dynamically -->
        </div>
    </div> 
    
    {{-- @include('layouts.footer') <!-- Include the footer --> --}}    
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
</body>
</html>
