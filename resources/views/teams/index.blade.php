<html lang="en"> <!-- Sets language to English -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

{{-- <style>
    .card-header {
        padding: 0.75rem 1rem;
    }
    .card-body::-webkit-scrollbar {
        width: 6px;
    }
    .card-body::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 3px;
    }
    .card-title {
        font-size: 1rem;
        font-weight: 600;
    }
    .border-bottom:last-child {
        border-bottom: none !important;
    }
</style> --}}
@extends('layouts.navbar')
@section('navbar-content')
    @if (auth()->check())
        <section id="ticket-dashboard">
            @include('teams.partials.dashboard')
        </section>
    @endif

    <script>
        setInterval(() => {
            fetch('/teams/dashboard')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('ticket-dashboard').innerHTML = html;
                });
        }, 10000); // 10 seconds
    </script>
@endsection
