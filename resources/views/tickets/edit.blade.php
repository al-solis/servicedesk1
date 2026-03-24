<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@extends('layouts.app')
@section('navbar-content')
    @if (auth()->check())
        <section class="bg-white dark:bg-gray-900">
            <h2 class="mb-4 ml-2 text-xl font-bold text-gray-900 dark:text-white">Update Ticket</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Ticket Info -->
                    <div class="space-y-2 ml-2">
                        <div class="sm:col-span-2">
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                            <input type="text" name="description" id="description"
                                value="{{ old('description', $ticket->description) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Type product name" required="">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Support Type</label>
                            <select id="type" name="type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach ($supportTypes as $type)
                                    <option value="{{ $type->id }}" {{ $ticket->type == $type->id ? 'selected' : '' }}>
                                        {{ $type->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-4 justify-between">
                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                                <select id="priority" name="priority"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required>
                                    <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required @if (Auth::user()->usertype == 'User') disabled @endif>
                                    <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="On-hold" {{ $ticket->status == 'On-hold' ? 'selected' : '' }}>On-hold
                                    </option>
                                    <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed
                                    </option>
                                    <option value="Cancelled" {{ $ticket->status == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>

                                @if (Auth::user()->usertype == 'User')
                                    <input type="hidden" name="status" value="{{ $ticket->status }}">
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 justify-between">
                            <!-- Team Selection -->
                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label for="team"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-300">Support Team</label>
                                <select id="team" name="team_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    single @if (Auth::user()->usertype == 'User') disabled @endif>
                                    <option value="">Select Support Team</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            {{ in_array($team->id, $assignedTeamIds) ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                    {{-- <x-input-error :messages="$errors->get('team_id')" class="mt-2" /> --}}
                                </select>
                                @if (Auth::user()->usertype == 'User')
                                    <input type="hidden" name="team_id" value="{{ implode(',', $assignedTeamIds) }}">
                                @endif
                            </div>

                            <div class="flex-1 min-w-[90px] md:min-w-[100px]">
                                <label for="dropdownSearchUser"
                                    class="block text-sm font-medium text-gray-900 dark:text-gray-300">Support
                                    Member</label>
                                <button id="dropdownSearchUser" data-dropdown-toggle="dropdownSearch"
                                    data-dropdown-placement="bottom"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-20 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    type="button" @if (Auth::user()->usertype == 'User') disabled @endif>
                                    Click to select
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>

                                <div id="dropdownSearch"
                                    class="z-10 hidden bg-white rounded-lg shadow-sm w-60 dark:bg-gray-700">
                                    {{-- <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Support Name</label> --}}

                                    <!-- Search Input -->
                                    <input type="text" id="searchInput" placeholder="Search users..."
                                        class="w-full p-2 mb-2 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                    <!-- Checkbox List -->
                                    <ul id="userList"
                                        class="h-48 px-3 pb-3 overflow-y-auto border border-gray-300 rounded-md dark:border-gray-600">
                                        @foreach ($users as $user)
                                            <li class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <input type="checkbox" id="user-{{ $user->id }}" name="users[]"
                                                    value="{{ $user->id }}"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                                    {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}>
                                                <label for="user-{{ $user->id }}"
                                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex-grow">
                                                    {{ $user->lname }}, {{ $user->fname }}
                                                    {{-- {{ substr($user->mname, 0, 1) }} --}}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @if ($ticket->user_id == Auth::user()->id && Auth::user()->usertype != 'Administrator')
                            <div class="col-span-2">
                                <label for="message"
                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                @php
                                    // Fetch the latest message of the current user for this ticket
                                    $userMessage = $ticket->details->where('user_id', Auth::user()->id)->last();
                                @endphp
                                <textarea id="message" name="message" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('message', $userMessage ? $userMessage->message : '') }}
                            </textarea>

                                <!-- Image Upload -->
                                <div class="sm:col-span-2">
                                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Upload
                                        Images</label>
                                    <input type="file" name="images[]" multiple accept="image/*"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>

                                <!-- File Upload (PDF, Excel, etc.) -->
                                <div class="sm:col-span-2">
                                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Upload
                                        Files</label>
                                    <input type="file" name="files[]" multiple
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.zip,.rar"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                            </div>
                        @endif

                        <!-- Allow new message only if logged-in name ≠ edited name -->
                        @if ($ticket->user_id != Auth::user()->id or Auth::user()->usertype != 'User')
                            <div class="sm:col-span-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                                <textarea id="message" name="message" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Write support message here."></textarea>
                            </div>

                            <!-- Image Upload -->
                            <div class="sm:col-span-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Upload
                                    Images</label>
                                <input type="file" name="images[]" multiple accept="image/*"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>

                            <!-- File Upload (PDF, Excel, etc.) -->
                            <div class="sm:col-span-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Upload
                                    Files</label>
                                <input type="file" name="files[]" multiple
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.zip,.rar"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        @endif

                    </div>



                    <!-- Right Column: Support History -->
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Support History</label>

                        <div class="space-y-4 max-h-[650px] overflow-y-auto pr-2">
                            @foreach ($ticket->details as $detail)
                                <div class="flex items-start gap-2.5">
                                    <!-- Profile Image (Replace with dynamic image if available) -->
                                    {{-- <img class="w-8 h-8 rounded-full" 
                                src="{{ optional($detail->user)->profile_picture ? Storage::url($detail->user->profile_picture) : asset('assets/admin/img/undraw_profile.svg') }}" 
                                alt="User Image"> --}}
                                    @php
                                        $userColor = '#' . substr(md5($detail->user->id), 0, 6);
                                    @endphp
                                    @if ($detail->user->profile_picture)
                                        <img class="w-8 h-8 rounded-full" {{-- src="{{ optional($detail->user)->profile_picture ? Storage::url($detail->user->profile_picture) : asset('assets/admin/img/undraw_profile.svg') }}"  --}}
                                            src="{{ optional($detail->user)->profile_picture ? asset('storage/' . $detail->user->profile_picture) : asset('assets/admin/img/undraw_profile.svg') }}"
                                            alt="User Image">
                                    @else
                                        <span
                                            class="w-8 h-8 flex justify-center items-center rounded-full text-white font-bold 
                                        user-color"
                                            style="--user-color: {{ $userColor }};">
                                            {{ strtoupper(substr(optional($detail->user)->fname, 0, 1)) }}{{ strtoupper(substr(optional($detail->user)->lname, 0, 1)) }}
                                        </span>
                                    @endif

                                    <!-- Chat Bubble -->
                                    <div
                                        class="flex flex-col w-full max-w-[450px] leading-1.5 bg-gray-50 dark:bg-gray-700 rounded-xl p-4 shadow-md">
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
                                                        <span
                                                            class="absolute bottom-0 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs rounded-lg px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity">
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
                                                    <div
                                                        class="flex items-center gap-2 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-sm">
                                                        @php
                                                            $fileExtension = pathinfo(
                                                                $file->file_path,
                                                                PATHINFO_EXTENSION,
                                                            );
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
                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                            target="_blank"
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
                <div class="flex justify-end space-x-4 mr-2">
                    <a href="{{ route('tickets.index') }}"
                        class="mb-4 text-gray-700 inline-flex items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        Cancel
                    </a>
                    <button type="submit"
                        class="mb-4 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Update Ticket
                    </button>
                </div>
            </form>

        </section>
        <!-- Image Modal -->
        <div id="imageModal"
            class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-80 flex justify-center items-center hidden z-50">
            <img id="modalImage" class="max-w-full max-h-full rounded-lg">
            <button class="absolute top-5 right-5 text-white text-2xl font-bold" onclick="closeModal()">✕</button>
        </div>

        <script>
            // Search Functionality
            document.getElementById('searchInput').addEventListener('input', function() {
                let searchValue = this.value.toLowerCase();
                let users = document.querySelectorAll('#userList li');

                users.forEach(user => {
                    let username = user.querySelector('label').textContent.toLowerCase();
                    if (username.includes(searchValue)) {
                        user.style.display = '';
                    } else {
                        user.style.display = 'none';
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const status = document.getElementById('status');
                const team = document.getElementById('team');
                const userCheckboxes = document.querySelectorAll('input[name="users[]"]');

                form.addEventListener('submit', function(event) {
                    const selectedUsers = Array.from(userCheckboxes).filter(checkbox => checkbox.checked);

                    // Check if status is not 'Open' and either no team is selected or no users are selected
                    if (status.value !== 'Open' && (team.value === '' || selectedUsers.length === 0)) {
                        event.preventDefault(); // Prevent form submission
                        alert('Please select a Support Team and Support Member before updating the ticket.');
                    }
                });
            });

            function deleteImage(imageId) {
                if (confirm('Are you sure you want to delete this image?')) {
                    fetch(`/ticket-images/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Failed to delete image');
                            }
                        });
                }
            }

            function enlargeImage(src) {
                document.getElementById('modalImage').src = src;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('imageModal').classList.add('hidden');
            }
        </script>
    @else
        <p>Please <a href="{{ route('login') }}">login</a> to access tickets.</p>
    @endif
@endsection
