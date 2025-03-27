@vite(['resources/css/app.css','resources/js/app.js'])
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
@if(auth()->check())
<section>
    <h2 class="text-2xl font-bold">{{optional($teams->first())->name}}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
    
        @php
            $statuses = [
                'Open' => 'bg-blue-500',
                'In Progress' => 'bg-orange-600',
                'On-hold' => 'bg-purple-600',
                'Others' => 'bg-gray-500'
            ];
        @endphp

        @foreach ($statuses as $status => $bgColor)
            <div class="bg-white border border-gray-200 rounded-lg shadow">
                <div class="{{ $bgColor }} text-white p-4 rounded-t-lg flex justify-between">
                    <h5 class="font-bold">{{ $status }}</h5>
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $status == 'Others' 
                            ? $tickets->whereNotIn('status', ['Open', 'In Progress', 'On-hold', 'Closed', 'Cancelled'])->count() 
                            : $tickets->where('status', $status)->count() 
                        }}
                    </span>
                </div>
                <div class="p-4 overflow-y-auto max-h-72">
                    @foreach ($status == 'Others' 
                        ? $tickets->whereNotIn('status', ['Open', 'In Progress', 'On-hold', 'Closed', 'Cancelled']) 
                        : $tickets->where('status', $status) 
                    as $ticket)
                        <div class="mb-4 border-b pb-2">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="font-semibold text-blue-600 hover:underline">
                                {{ $ticket->description }}
                            </a>
                            <p class="text-sm text-gray-500">{{ Str::limit($ticket->message, 80) }}</p>
                        
                            <!-- Single Row Flex Layout -->
                            <div class="text-xs text-gray-400 flex justify-between items-center">
                                <span>#{{ $ticket->id }}</span>
                                <span>&nbsp;{{ $ticket->lname }}, {{ $ticket->fname }}</span>
                                <span class="ml-auto">{{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>                    
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@endif
@endsection