@vite(['resources/css/app.css','resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

<link rel="stylesheet" href="{{ asset('/assets/admin/css/style.css') }}">

@extends('layouts.navbar')

@section('navbar-content')

@if(auth()->check())
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form action="{{ route('tickets.index')}}" method="GET" class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" name= "search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" 
                            value="{{ request()->query('search') }}">
                        </div>
                        <button type="submit" class="hidden mt-4 w-full shrink-0 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:mt-0 sm:w-auto">Search</button>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button id="createModalButton" data-modal-target="createModal" data-modal-toggle="createModal" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        New Ticket
                    </button>
                    {{-- <div class="flex items-center space-x-3 w-full md:w-auto">
                        <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                            <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                            Actions
                        </button>
                        <div id="actionsDropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mass Edit</a>
                                </li>
                            </ul>
                            <div class="py-1">
                                <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete all</a>
                            </div>
                        </div>
                        <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                            <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                        <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose brand</h6>
                            <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                <li class="flex items-center">
                                    <input id="apple" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="apple" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Apple (56)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="fitbit" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="fitbit" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Microsoft (16)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="razor" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="razor" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Razor (49)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="nikon" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="nikon" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nikon (12)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="benq" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="benq" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">BenQ (74)</label>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="overflow-x-auto" style="min-height: 650px;">              
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-[100px]">ID</th>
                            <th scope="col" class="px-4 py-3 w-[200px]">Requested By</th>
                            <th scope="col" class="px-4 py-3 w-[300px]">Subject</th>
                            <th scope="col" class="px-4 py-3 w-[100px]">Priority</th>
                            <th scope="col" class="px-4 py-3 w-[200px]">Assigned to</th>
                            <th scope="col" class="px-4 py-3 w-[150px]">Created</th>
                            <th scope="col" class="px-4 py-3 w-[120px] text-center">Status</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">Actions</span>
                            </th>                                                      
                        </tr>
                    </thead>

                </thead>
                <tbody class="min-h-[200px]">
                    @foreach($tickets as $ticket)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 w-[100px]">{{ $ticket->id }}</td>
                            <td class="px-4 py-3 w-[200px] flex items-center">
                                @php
                                    $userColor = '#' . substr(md5($ticket->user->id), 0, 6);
                                @endphp
                                @if($ticket->user->profile_picture)
                                    <!-- Display the profile picture if it exists -->
                                    <img class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800" 
                                        src="{{ Storage::url($ticket->user->profile_picture) }}" 
                                        alt="{{ $ticket->user->lname }}">
                                @else
                                    <!-- Display the user's initials in a colored circle if no profile picture -->
                                    <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold mr-2 
                                        user-color" style="--user-color: {{ $userColor }};">
                                        {{ strtoupper(substr($ticket->user->fname, 0, 1)) }}{{ strtoupper(substr($ticket->user->lname, 0, 1)) }}
                                    </span>
                                @endif

                                <!-- Display the user's full name -->
                                <span class="text-gray-900 dark:text-white">
                                    &nbsp; {{ $ticket->user->lname }}, {{ $ticket->user->fname }} {{ strtoupper(substr($ticket->user->mname, 0, 1)) }}.
                                </span>
                            </td>
                            <td class="px-4 py-3 w-[300px]">{{ $ticket->description }}</td>
                            <td class="px-4 py-3 w-[100px] text-center">
                                <span class="inline-flex justify-center items-center w-[70px] h-[14px] px-4 py-2 rounded-md font-semibold text-xs
                                    @if ($ticket->priority == 'High') animate-pulse @endif" 
                                    style="background-color: 
                                    @if($ticket->priority == 'Low') #d4edda; color: #155724;
                                    @elseif($ticket->priority == 'Medium') #fff3cd; color: #856404 ;
                                    @elseif($ticket->priority == 'High') #f8d7da; color: #721c24;
                                    @endif">
                                    {{ $ticket->priority }}
                                </span>
                            </td>

                            <td class="px-4 py-3 w-[200px] flex -space-x-6 rtl:space-x-reverse">
                                @if ($ticket->assignedUsers && $ticket->assignedUsers->count() > 0)
                                    @foreach ($ticket->assignedUsers as $assignedUser)
                                        <div class="relative group">
                                            @php
                                                // Generate unique color for initials
                                                $assignedColor = '#' . substr(md5($assignedUser->id), 0, 6);
                                            @endphp
                                
                                            <div class="flex items-center space-x-2">
                                                @if ($assignedUser->profile_picture)
                                                    <!-- Display profile picture -->
                                                    <img class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800"
                                                        src="{{ Storage::url($assignedUser->profile_picture) }}" 
                                                        alt="{{ $assignedUser->lname }}">
                                                @else
                                                    <!-- Display initials in colored circle -->
                                                    <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold mr-2"
                                                        style="background-color: {{ $assignedColor }};">
                                                        {{ strtoupper(substr($assignedUser->fname, 0, 1)) }}{{ strtoupper(substr($assignedUser->lname, 0, 1)) }}
                                                    </span>
                                                @endif
                                
                                                <!-- Display name -->
                                                {{-- <span class="text-gray-900 dark:text-white">
                                                    {{ $assignedUser->lname }}, {{ $assignedUser->fname }} {{ strtoupper(substr($assignedUser->mname, 0, 1)) }}.
                                                </span> --}}
                                                <!-- Popover -->
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block w-auto px-3 py-2 text-sm text-white bg-gray-500 rounded-lg shadow-lg whitespace-nowrap">
                                                    {{ $assignedUser->lname }}, {{ $assignedUser->fname}} {{substr($assignedUser->mname, 0,1)}}
                                                    <div class="absolute left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-500 rotate-45 bottom-[-4px]"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- <span class="text-gray-400 dark:text-gray-600 text-sm">Unassigned</span> --}}
                                @endif
                            </td>
                            
                            

                            <td class="px-4 py-3 w-[180px]">{{ \Carbon\Carbon::parse($ticket->date_created)->format('d M Y')}}</td>
                            <td class="px-4 py-3 w-[120px] text-center">
                                <span class="inline-flex justify-center items-center w-[93px] h-[14px] px-4 py-2 rounded-md font-semibold text-xs" 
                                    style="background-color: 
                                    @if($ticket->status == 'Open') #cce5ff; color: #004085;
                                    @elseif($ticket->status == 'In Progress') #d6d8d9; color: #383d41;
                                    @elseif($ticket->status == 'Closed') #d4edda; color: #155724;
                                    @elseif($ticket->status == 'On-hold') #e2d9f3; color: #4a148c;
                                    @elseif($ticket->status == 'Cancelled') #f8d7da; color: #721c24;
                                    @endif">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 w-[120px] text-center justify-end">
                                <div class="relative inline-block">
                                    <button id="dropdown-btn-{{ $ticket->id }}" data-dropdown-toggle="dropdown-menu-{{ $ticket->id }}" 
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                                            type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-menu-{{ $ticket->id }}" 
                                        class="hidden absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg divide-y divide-gray-100 dark:bg-gray-700 dark:divide-gray-600 z-50">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                            <li>
                                                <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                style="{{ $user->usertype == 'User' ? 'pointer-events: none;' : '' }}">
                                                Assign
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" style="pointer-events: none">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </td>                        
                        </tr>
                    @endforeach

                </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0 mb-4 mr-4">
                {{ $tickets->links() }}
            </div>

            {{-- <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px">
                    <li>
                        <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Previous</span>
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                    </li>
                    <li>
                        <a href="#" aria-current="page" class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-blue-600 bg-blue-50 border border-blue-300 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Next</span>
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav> --}}

            
        </div>
    </div>


<!-- Create modal -->
<div id="createModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    New Ticket
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="createModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
                <!-- Modal body -->
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf                    
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                            <input type="text" name="subject" id="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter short description" required="">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Elaborate support here" required></textarea>                    
                        </div>

                        <!-- Support Type -->
                        <div class="flex-1 min-w-[90px] md:min-w-[90px]">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support Type</label>
                            <select name="support_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="">Select Support Type</option>
                                @foreach($supportTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>                            
                        </div>       

                        <div class="flex flex-wrap gap-4 justify-between">
                            <!-- Priority -->
                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                                <select id="priority" name="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">                                
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>                                
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">                                
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="On-hold">On-hold</option>
                                    <option value="Closed">Closed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                         
                        
                        {{-- <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                            <select id="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">                                
                                <<option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>                                
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support Type</label>
                            <select name="support_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="">Select Support Type</option>
                                @foreach($supportTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>                            
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>                                
                                <<option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="On-hold">On-hold</option>
                                <option value="Closed">Closed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div> --}}
                        
                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Add ticket
                    </button>
                </form>
            </div>
        </div>
</div>
<!-- End create ticket -->

<!-- Show ticket modal -->
{{-- <div id="showModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Ticket Details
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="showModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
                <!-- Modal body -->
                <form action= "" method = "">
                    @csrf                    
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label for="Subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                            <input type="text" name="description" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ old('description', $ticket->description) }}">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description', $ticket->description) }}</textarea>                    
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support Type</label>
                            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="">Select Support Type</option>
                                @foreach($supportTypes as $type)
                                    <option value="{{ $type->id }}" {{ $ticket->type == $type->id ? 'selected' : '' }}>{{ $type->description }}</option>
                                @endforeach
                            </select>                            
                        </div>
                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>                                
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="On-hold" {{ $ticket->status == 'On-hold' ? 'selected' : '' }}>On-hold</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Messages</label>
                            <ul class="list-group mb-3">
                                @foreach($ticket->details as $detail)
                                    <li class="list-group-item">
                                        <strong>
                                            {{ optional($detail->user)->lname ?? 'System' }},
                                            {{ optional($detail->user)->fname ?? '' }},
                                            {{ optional($detail->user)->mname ? substr(optional($detail->user)->mname, 0, 1) . '.' : '' }}
                                            :</strong> 
                                        {{ $detail->message }}
                                        <br>
                                        <small class="text-muted">{{ $detail->date_created }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>


                    </div>
                    <button type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    data-modal-toggle="showModal">
                        Close
                    </button>
                </form>
            </div>
        </div>
</div> --}}
<!-- End view modal -->

</section>
@else
    <p>Please <a href="{{ route('login') }}">login</a> to access tickets.</p>
@endif
@endsection