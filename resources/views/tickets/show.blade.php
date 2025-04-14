<link rel="stylesheet" href="{{ asset('/assets/admin/css/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@extends('layouts.app')
@section('navbar-content')    
    <section class="bg-white dark:bg-gray-900">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Ticket Information</h2>
        <form action="#">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Ticket Info -->
                <div class="space-y-4 ml-2 mt-2">
                    {{-- Move all the fields like Subject, Support Type, Priority, Status, Team, Assigned Users here --}}
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

                    <div class="flex flex-wrap gap-4 justify-between">
                        <div class="flex-1 min-w-[90px] md:min-w-[100px]">
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
                        <div class="flex-1 min-w-[90px] md:min-w-[100px]">
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
                                                    <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold mr-2
                                                        user-color" style="--user-color: {{ $userColor }};">
                                                        {{ strtoupper(substr($user->fname, 0, 1)) }}{{ strtoupper(substr($user->lname, 0, 1)) }}
                                                    </span>
                                                @endif    
        
                                                <!-- Popover -->
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block w-auto px-3 py-2 text-sm text-white bg-gray-500 rounded-lg shadow-lg whitespace-nowrap">
                                                    {{ $user->lname }}, {{ $user->fname}} 
                                                    {{-- {{substr($user->mname, 0,1)}} --}}
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
                        </div>
                    </div>                     
                </div>
        
                <!-- Right Column: Support History -->
                <div>
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
                                    <span class="w-8 h-8 flex justify-center items-center rounded-full text-white font-bold 
                                        user-color" style="--user-color: {{ $userColor }};">
                                        {{ strtoupper(substr(optional($detail->user)->fname, 0, 1)) }}{{ strtoupper(substr(optional($detail->user)->lname, 0, 1)) }}
                                    </span>
                                @endif
                                <!-- Chat Bubble -->
                                <div class="flex flex-col w-full max-w-[450px] leading-1.5 bg-gray-50 dark:bg-gray-700 rounded-xl p-4 shadow-md">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ optional($detail->user)->lname ?? 'System' }},
                                            {{ optional($detail->user)->fname ?? '' }}
                                            {{-- {{ optional($detail->user)->mname ? substr(optional($detail->user)->mname, 0, 1) . '.' : '' }} --}}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $detail->date_created }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-normal text-gray-900 dark:text-white">
                                        {{ $detail->message }}
                                    </p>                                        
                                    {{-- @foreach ($ticketImage as $images)
                                        @if ($images->user_id == $detail->user_id)
                                            <div class="relative group mt-2 w-32 h-32">
                                                <img src="{{ asset('storage/' . $images->img_path) }}" 
                                                    class="w-32 h-32 object-cover rounded-lg border cursor-pointer transition-transform duration-200 hover:scale-105"
                                                    onclick="enlargeImage('{{ asset('storage/' . $images->img_path) }}')"
                                                    alt="Uploaded Image">
                                                <!-- Tooltip -->
                                                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs rounded-lg px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    Enlarge
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach --}}

                                    <!-- Display Uploaded Images -->
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($ticketFile as $images)
                                            @if ($images->user_id == $detail->user_id && $images->file_type == 'image')
                                                <div class="relative group w-24 h-24">
                                                    <img src="{{ asset('storage/' . $images->file_path) }}" 
                                                        class="w-24 h-24 object-cover rounded-lg border cursor-pointer transition-transform duration-200 hover:scale-105"
                                                        onclick="enlargeImage('{{ asset('storage/' . $images->file_path) }}')"
                                                        alt="Uploaded Image">
                                                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs rounded-lg px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        Enlarge
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                     <!-- Display Uploaded Files with Icons -->
                                    <div class="mt-2">
                                        @foreach ($ticketFile as $file)
                                            @if ($file->user_id == $detail->user_id && $file->file_type == 'document')
                                                <div class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-sm">
                                                    @php
                                                        $fileExtension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                                        $icon = match ($fileExtension) {
                                                            'pdf' => 'fa-file-pdf text-red-500',
                                                            'doc', 'docx' => 'fa-file-word text-blue-500',
                                                            'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                            'ppt', 'pptx' => 'fa-file-powerpoint text-orange-500',
                                                            'txt' => 'fa-file-alt text-gray-500',
                                                            default => 'fa-file text-gray-400',
                                                        };
                                                    @endphp

                                                    <i class="fas {{ $icon }} text-xl"></i>
                                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" 
                                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                                        {{-- {{ pathinfo($file->file_path, PATHINFO_BASENAME) }} --}}
                                                        {{ $file->file_name }}
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>
        
            <div class="flex justify-end mr-2">
                <a href="{{ route('tickets.index') }}" class="mb-4 mr-2 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Close
                </a>
            </div>
        </form>
        
    </section>

<!-- Image Modal -->
<div id="imageModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 flex justify-center items-center hidden z-50">
    <img id="modalImage" class="max-w-full max-h-full rounded-lg">
    <button class="absolute top-5 right-5 text-white text-2xl font-bold" onclick="closeModal()">✕</button>
</div>

<script>
    function enlargeImage(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection
