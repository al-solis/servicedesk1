@vite(['resources/css/app.css','resources/js/app.js'])
{{-- <script src= "{{ asset('/resources/css/flowbite.min.css') }}"></script>
<script src= "{{ asset('/resources/css/app.css') }}"></script>
<script src= "{{ asset('/resources/css/app.js') }}"></script> --}}

@extends('layouts.navbar')

@section('navbar-content')

@if(auth()->check())
<section class="bg-gray-50 dark:bg-gray-900 h-full p-3 sm:p-5 ">
    {{-- Ticket Cart --}}
    {{-- <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <svg class="w-[43px] h-[43px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.209 3.816a1 1 0 0 0-1.966.368l.325 1.74a5.338 5.338 0 0 0-2.8 5.762l.276 1.473.055.296c.258 1.374-.228 2.262-.63 2.998-.285.52-.527.964-.437 1.449.11.586.22 1.173.75 1.074l12.7-2.377c.528-.1.418-.685.308-1.27-.103-.564-.636-1.123-1.195-1.711-.606-.636-1.243-1.306-1.404-2.051-.233-1.085-.275-1.387-.303-1.587-.009-.063-.016-.117-.028-.182a5.338 5.338 0 0 0-5.353-4.39l-.298-1.592Z"/>
            <path fill-rule="evenodd" d="M6.539 4.278a1 1 0 0 1 .07 1.412c-1.115 1.23-1.705 2.605-1.83 4.26a1 1 0 0 1-1.995-.15c.16-2.099.929-3.893 2.342-5.453a1 1 0 0 1 1.413-.069Z" clip-rule="evenodd"/>
            <path d="M8.95 19.7c.7.8 1.7 1.3 2.8 1.3 1.6 0 2.9-1.1 3.3-2.5l-6.1 1.2Z"/>
          </svg>          
        <a href="{{route('tickets.index', ['search' => 'Open'])}}">
            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">                 
                @if ($tickets->where('status', 'Open')->count() >= 2)
                    There are {{$tickets->where('status','Open')->count()}} open tickets
                @else
                    There is 1 open ticket
                @endif
            </h5>
        </a>
        <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
            There @if ($tickets->where('status', 'Open')->count() >= 2) are open tickets @else is an open ticket @endif that require immediate attention to ensure timely resolution.
        </p>
        <a href="{{route('tickets.index', ['search' => 'Open'])}}" class="inline-flex font-medium items-center text-blue-600 hover:underline">
            See open @if ($tickets->where('status', 'Open')->count() >= 2) tickets @else ticket @endif
            <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
            </svg>
        </a>
    </div> --}}
    
    <div class="flex space-x-4">        
          <!-- Third Card Content Unassigned -->          
          <div class="w-[500px] bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
            <div class="flex justify-between mb-3">
              <div class="flex items-center">
                <div class="flex justify-center items-center">
                  <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Your team's progress</h5>
                  <svg data-popover-target="chart-info" data-popover-placement="bottom" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
                  </svg>
                  <div data-popover id="chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                      <div class="p-3 space-y-2">
                          <h3 class="font-semibold text-gray-900 dark:text-white">Activity growth - Incremental</h3>
                          <p>Report helps navigate cumulative growth of community activities. Ideally, the chart should have a growing trend, as stagnating chart signifies a significant decrease of community activity.</p>
                          <h3 class="font-semibold text-gray-900 dark:text-white">Calculation</h3>
                          <p>For each date bucket, the all-time volume of activities is calculated. This means that activities in period n contain all activities up to period n, plus the activities generated by your community in period.</p>
                          <a href="#" class="flex items-center font-medium text-blue-600 dark:text-blue-500 dark:hover:text-blue-600 hover:text-blue-700 hover:underline">Read more <svg class="w-2 h-2 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg></a>
                      </div>
                      <div data-popper-arrow></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
              <div class="grid grid-cols-5 gap-3 mb-2">
                <dl class="bg-blue-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
                  <dt id="open-tickets" class="w-8 h-8 rounded-full bg-blue-100 dark:bg-gray-500 text-blue-600 dark:text-blue-300 text-sm font-medium flex items-center justify-center mb-1" data-count="{{$tickets->where('status','Open')->count()}}">{{$tickets->where('status','Open')->count()}}</dt>
                  <dd class="text-blue-600 dark:text-blue-300 text-sm font-medium">Open</dd>
                </dl>
                <dl class="bg-orange-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
                  <dt id="inprogress-tickets" class="w-8 h-8 rounded-full bg-orange-100 dark:bg-gray-500 text-orange-600 dark:text-orange-300 text-sm font-medium flex items-center justify-center mb-1" data-count="{{$tickets->where('status','In Progress')->count()}}">{{$tickets->where('status','In Progress')->count()}}</dt>
                  <dd class="text-orange-600 dark:text-orange-300 text-sm font-medium">In Progress</dd>
                </dl>
                <dl class="bg-purple-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
                  <dt id="onhold-tickets" class="w-8 h-8 rounded-full bg-purple-100 dark:bg-gray-500 text-purple-600 dark:text-purple-300 text-sm font-medium flex items-center justify-center mb-1" data-count="{{$tickets->where('status','On-hold')->count()}}">{{$tickets->where('status','On-hold')->count()}}</dt>
                  <dd class="text-purple-600 dark:text-purple-300 text-sm font-medium">On-hold</dd>
                </dl>
                <dl class="bg-teal-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
                  <dt id="closed-tickets" class="w-8 h-8 rounded-full bg-teal-100 dark:bg-gray-500 text-teal-600 dark:text-teal-300 text-sm font-medium flex items-center justify-center mb-1" data-count="{{$tickets->where('status','Closed')->count()}}">{{$tickets->where('status','Closed')->count()}}</dt>
                  <dd class="text-teal-600 dark:text-teal-300 text-sm font-medium">Closed</dd>
                </dl>
                <dl class="bg-red-50 dark:bg-gray-600 rounded-lg flex flex-col items-center justify-center h-[78px]">
                  <dt id="cancelled-tickets" class="w-8 h-8 rounded-full bg-red-100 dark:bg-gray-500 text-red-600 dark:text-red-300 text-sm font-medium flex items-center justify-center mb-1" data-count="{{$tickets->where('status','Cancelled')->count()}}">{{$tickets->where('status','Cancelled')->count()}}</dt>
                  <dd class="text-red-600 dark:text-red-300 text-sm font-medium">Cancelled</dd>
                </dl>                
              </div>
              <button data-collapse-toggle="more-details" type="button" class="hover:underline text-xs text-gray-500 dark:text-gray-400 font-medium inline-flex items-center">Show more details <svg class="w-2 h-2 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
              </button>
              <div id="more-details" class="border-gray-200 border-t dark:border-gray-600 pt-3 mt-3 space-y-2 hidden">
                <dl class="flex items-center justify-between">
                  <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Average task completion rate:</dt>
                  <dd class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                    </svg> 57%
                  </dd>
                </dl>
                <dl class="flex items-center justify-between">
                  <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Days until sprint ends:</dt>
                  <dd class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-600 dark:text-gray-300">13 days</dd>
                </dl>
                <dl class="flex items-center justify-between">
                  <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal">Next meeting:</dt>
                  <dd class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-600 dark:text-gray-300">Thursday</dd>
                </dl>
              </div>
            </div>

            <!-- Radial Chart -->
            <div class="py-6" id="radial-chart"></div>

            <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
              <div class="flex justify-between items-center pt-5">
                <!-- Button -->
                <button
                  id="dropdownDefaultButton"
                  data-dropdown-toggle="lastDaysdropdown"
                  data-dropdown-placement="bottom"
                  class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                  type="button">
                  Last 7 days
                  <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
                </button>
                <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                      </li>
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                      </li>
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 7 days</a>
                      </li>
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 30 days</a>
                      </li>
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 90 days</a>
                      </li>
                    </ul>
                </div>
                <a
                  href="#"
                  class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                  Progress report
                  <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>                 
      

        <div class="w-[250px] h-[220px] p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
          <!-- Open Tickets -->          
              <svg class="w-[43px] h-[43px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M11.209 3.816a1 1 0 0 0-1.966.368l.325 1.74a5.338 5.338 0 0 0-2.8 5.762l.276 1.473.055.296c.258 1.374-.228 2.262-.63 2.998-.285.52-.527.964-.437 1.449.11.586.22 1.173.75 1.074l12.7-2.377c.528-.1.418-.685.308-1.27-.103-.564-.636-1.123-1.195-1.711-.606-.636-1.243-1.306-1.404-2.051-.233-1.085-.275-1.387-.303-1.587-.009-.063-.016-.117-.028-.182a5.338 5.338 0 0 0-5.353-4.39l-.298-1.592Z"/>
                  <path fill-rule="evenodd" d="M6.539 4.278a1 1 0 0 1 .07 1.412c-1.115 1.23-1.705 2.605-1.83 4.26a1 1 0 0 1-1.995-.15c.16-2.099.929-3.893 2.342-5.453a1 1 0 0 1 1.413-.069Z" clip-rule="evenodd"/>
                  <path d="M8.95 19.7c.7.8 1.7 1.3 2.8 1.3 1.6 0 2.9-1.1 3.3-2.5l-6.1 1.2Z"/>
                </svg>          
              <a href="{{route('tickets.index', ['search' => 'Open'])}}">
                  <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">                 
                  {{$tickets->where('status','Open')->count()}}                
                  </h5>
              </a>
              <p class="mb-3 font-normal text-sm text-gray-500 dark:text-gray-400">
                  There @if ($tickets->where('status', 'Open')->count() >= 2) are open tickets @else is an open ticket @endif that require immediate attention to ensure timely resolution.
              </p>
              <a href="{{route('tickets.index', ['search' => 'Open'])}}" class="inline-flex font-medium text-sm items-center text-blue-600 hover:underline">
                  See open @if ($tickets->where('status', 'Open')->count() >= 2) tickets @else ticket @endif
                  <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
                  </svg>
              </a>
          
      </div>
  
      <div class="w-[250px] h-[220px] p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
          <!-- Second Card Content Unassigned -->            
          <svg class="w-[43px] h-[43px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v5a1 1 0 1 0 2 0V8Zm-1 7a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z" clip-rule="evenodd"/>
          </svg>
                   
              <a href="{{route('tickets.index', ['search' => ''])}}">
                  <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">                 
                  {{$unAssignedTickets->count()}}                
                  </h5>
              </a>
              <p class="mb-3 font-normal text-sm text-gray-500 dark:text-gray-400">
                  Unassigned tickets are waiting for support to take ownership and begin working on them.
              </p>
              <a href="{{route('tickets.index', ['search' => ''])}}" class="inline-flex font-medium text-sm items-center text-blue-600 hover:underline">
                  See all tickets
                  <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778"/>
                  </svg>
              </a>
        </div>  


    </div> {{-- End of flex space-x-4 --}}

        {{-- Login Session --}}  
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mt-4">
          <div class="flex items-center justify-between mb-4">
              <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Login session</h5>
              <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                  View all
              </a>
          </div>
          <div class="flow-root">
                  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">                      
                    @foreach ($allUsers as $login)
                      @php
                        $userColor = '#' . substr(md5($login->id), 0, 6);
                      @endphp
                      <li class="py-3 sm:py-4">
                          <div class="flex items-center ">                                                        
                                <div class="shrink-0">
                                    @if($login->profile_picture == null)
                                    <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold" 
                                        style="background-color: {{ $userColor }};">
                                        {{ strtoupper(substr($login->fname, 0, 1)) }}{{ strtoupper(substr($login->lname, 0, 1)) }}
                                    </span>
                                    @else
                                      <img class="w-8 h-8 rounded-full" src="{{ Storage::url($login->profile_picture) }}"  alt="{{$login->lname}}">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{$login->lname}} {{$login->fname}} {{substr($login->mname,0,1)}}.
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        {{$login->email}}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                  {{ \Carbon\Carbon::createFromTimestamp($login->last_activity)->diffForHumans() }}
                              </div>                                        
                          </div>
                      </li>
                    @endforeach    

              </div>
        </div> {{-- End of Login Session --}}

        {{-- Top Support Agents --}}  
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mt-4">
          <div class="flex items-center justify-between mb-4">
              <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Top Support</h5>
              <a href="{{ route('tickets.index', ['search', ''])}}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                  View all
              </a>
          </div>
          <div class="flow-root">
                  <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                      <li class="py-3 sm:py-4">

                          <div class="flex items-center">
                              <div class="shrink-0">
                                  <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-1.jpg" alt="Neil image">
                              </div>
                              <div class="flex-1 min-w-0 ms-4">
                                  <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                      Neil Sims
                                  </p>
                                  <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                      email@windster.com
                                  </p>
                              </div>
                              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                  $320
                              </div>
                          </div>
                      </li>
                      <li class="py-3 sm:py-4">
                          <div class="flex items-center ">
                              <div class="shrink-0">
                                  <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-3.jpg" alt="Bonnie image">
                              </div>
                              <div class="flex-1 min-w-0 ms-4">
                                  <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                      Bonnie Green
                                  </p>
                                  <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                      email@windster.com
                                  </p>
                              </div>
                              <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                  $3467
                              </div>
                          </div>
                      </li>
              </div>
          
      </div> {{-- End of Top Support Agents --}}
    </div> {{-- End of max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 --}}
      
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>       
 
</section>
<script>     
const getChartOptions = () => {
  //Get counts
  const openTickets = parseInt(document.getElementById('open-tickets').dataset.count);
  const inProgressTickets = parseInt(document.getElementById('inprogress-tickets').dataset.count);
  const onHoldTickets = parseInt(document.getElementById('onhold-tickets').dataset.count);
  const closedTickets = parseInt(document.getElementById('closed-tickets').dataset.count);
  const cancelledTickets = parseInt(document.getElementById('cancelled-tickets').dataset.count);

  const TotalTickets = openTickets + inProgressTickets + onHoldTickets + closedTickets + cancelledTickets;

  const openTicketsPercentage = (openTickets / TotalTickets) * 100;
  const inProgressTicketsPercentage = (inProgressTickets / TotalTickets) * 100;
  const onHoldTicketsPercentage = (onHoldTickets / TotalTickets) * 100;
  const closedTicketsPercentage = (closedTickets / TotalTickets) * 100;
  const cancelledTicketsPercentage = (cancelledTickets / TotalTickets) * 100;

  return {
    series: [openTicketsPercentage, inProgressTicketsPercentage, onHoldTicketsPercentage, closedTicketsPercentage, cancelledTicketsPercentage],
    colors: ["#1C64F2", "#FF8A00", "#E2D9F3", "#16BDCA", "#FF6B6B"],
    chart: {
      height: "350px",
      width: "100%",
      type: "radialBar",
      sparkline: {
        enabled: true,
      },
    },
    plotOptions: {
      radialBar: {
        track: {
          background: '#E5E7EB',
        },
        dataLabels: {
          show: false,
        },
        hollow: {
          margin: 0,
          size: "32%",
        }
      },
    },
    grid: {
      show: false,
      strokeDashArray: 4,
      padding: {
        left: 2,
        right: 2,
        top: -23,
        bottom: -20,
      },
    },
    labels: ["Open", "In progress", "On-hold", "Closed", "Cancelled"],
    legend: {
      show: true,
      position: "bottom",
      fontFamily: "Inter, sans-serif",
    },
    tooltip: {
      enabled: true,
      x: {
        show: false,
      },
    },
    yaxis: {
      show: false,
      labels: {
        formatter: function (value) {
          return value + '%';
        }
      }
    }
  }
}

if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.querySelector("#radial-chart"), getChartOptions());
  chart.render();
}

</script>
@else
    <p>Please <a href="{{ route('login') }}">login</a> to access tickets.</p>
@endif
@endsection