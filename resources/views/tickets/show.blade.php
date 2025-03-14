@extends('layouts.app')
@section('navbar-content')    
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Ticket Information</h2>
            <form action="#">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                        <input type="text" name="description" id="description" value="{{ old('description', $ticket->description) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Input subject" required="">
                    </div>                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support Type</label>
                        <select id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach($supportTypes as $type)
                                <option value="{{ $type->id }}" {{ $ticket->type == $type->id ? 'selected' : '' }}>
                                    {{ $type->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-4 justify-between">
                        <div class="flex-1 min-w-[90px] md:min-w-[100px]">                
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                            <select id="priority" name="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required 
                                >
                                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>                                
                            </select>
                        </div>      

                        <div class="flex-1 min-w-[90px] md:min-w-[100px]">                
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required 
                                @if(Auth::user()->usertype == 'User') disabled @endif>                    
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="On-hold" {{ $ticket->status == 'On-hold' ? 'selected' : '' }}>On-hold</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <!-- Team Selection -->
                        <label for="team" class="block text-sm font-medium text-gray-900 dark:text-gray-300">Support Team</label>
                        <select id="team" name="team_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" single>
                            <option value="">Select Support Team</option>
                            @foreach($teams as $team)                                
                                <option value="{{ $team->id }}" {{ in_array($team->id, $assignedTeamIds) ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- User Profile Bubble List -->
                    <div class="flex -space-x-4 rtl:space-x-reverse mt-2">                        
                        {{-- <label for="member" class="block text-sm font-medium text-gray-900 dark:text-gray-300">Assigned to Individual</label> --}}
                        @if (!empty($assignedUsers) && $assignedUsers->count() > 0)
                            <div id="member" class="flex -space-x-4 rtl:space-x-reverse mt-2">
                                @foreach($assignedUsers->take(4) as $user)
                                    <div class="relative group">
                                        @php
                                            $userColor = '#' . substr(md5($user->user_id), 0, 6);
                                        @endphp
                                        @if ($user->profile_picture)
                                            <img class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800 cursor-pointer" 
                                                src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : asset('assets/admin/img/undraw_profile.svg') }}" 
                                                alt="{{ $user->id }}">
                                        @else
                                            <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold mr-2" 
                                                style="background-color: {{ $userColor }};">
                                                {{ strtoupper(substr($user->fname, 0, 1)) }}{{ strtoupper(substr($user->lname, 0, 1)) }}
                                            </span>
                                        @endif    

                                        <!-- Popover -->
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block w-auto px-3 py-2 text-sm text-white bg-gray-500 rounded-lg shadow-lg whitespace-nowrap">
                                            {{ $user->lname }}, {{ $user->fname}} {{substr($user->mname, 0,1)}}
                                            <div class="absolute left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-500 rotate-45 bottom-[-4px]"></div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($assignedUsers->count() > 4)
                                    <a class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600 dark:border-gray-800" href="#">
                                        +{{ $assignedUsers->count() - 4 }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support History</label>
                    
                        <div class="space-y-4">
                            @foreach($ticket->details as $detail)
                            <div class="flex items-start gap-2.5">
                                <!-- Profile Image (Replace with dynamic image if available) -->
                                @php
                                    $userColor = '#' . substr(md5($detail->user->id), 0, 6);
                                @endphp
                                @if ($detail->user->profile_picture)
                                    <img class="w-8 h-8 rounded-full" 
                                    src="{{ optional($detail->user)->profile_picture ? Storage::url($detail->user->profile_picture) : asset('assets/admin/img/undraw_profile.svg') }}" 
                                    alt="User Image">
                                @else
                                    <span class="w-8 h-8 flex justify-center items-center rounded-full text-white font-bold" 
                                        style="background-color: {{ $userColor }};">
                                        {{ strtoupper(substr(optional($detail->user)->fname, 0, 1)) }}{{ strtoupper(substr(optional($detail->user)->lname, 0, 1)) }}
                                    </span>
                                @endif
                                <!-- Chat Bubble -->
                                <div class="flex flex-col w-full max-w-[320px] leading-1.5 bg-gray-50 dark:bg-gray-700 rounded-xl p-4 shadow-md">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ optional($detail->user)->lname ?? 'System' }},
                                            {{ optional($detail->user)->fname ?? '' }}
                                            {{ optional($detail->user)->mname ? substr(optional($detail->user)->mname, 0, 1) . '.' : '' }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $detail->date_created }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-normal text-gray-900 dark:text-white">
                                        {{ $detail->message }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
                <div class="flex justify-end mt-6">
                    <a href="{{ route('tickets.index') }}"  class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Close
                    </a>
                </div>
            </form>
        </div>
      </section>
@endsection


{{-- <section>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <div class="container">
    <h2 class="text-lg font-medium text-gray-900">
        Ticket Details
    </h2>
    <div class="mb-3">
        <label for="description" class="form-label">Subject</label>
        <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $ticket->description) }}" required></input>    
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $ticket->description) }}</textarea>          
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="type" class="form-label">Support Type</label>
            <select class="form-control" id="type" name="type" required>
                @foreach($supportTypes as $type)
                    <option value="{{ $type->id }}" {{ $ticket->type == $type->id ? 'selected' : '' }}>
                        {{ $type->description }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">                
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required 
                @if(Auth::user()->usertype == 'User') disabled @endif>                    
                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="On-hold" {{ $ticket->status == 'On-hold' ? 'selected' : '' }}>On-hold</option>
                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Messages</label>
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
    <div class="modal-footer">
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Close</a>        
    </div>    
</div>
</section>
@endsection --}}
