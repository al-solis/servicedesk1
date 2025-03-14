@extends('layouts.app')
@section('navbar-content')    
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update Category</h2>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('support.update', $supportTeam->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $supportTeam->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Input support team name" required>
                    </div>  
                </div>
                
                <!-- Searchable User List -->
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Members</label>

                    <!-- Search Input -->
                    <input type="text" id="searchInput" placeholder="Search users..." class="w-full p-2 mb-2 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    
                    <!-- Checkbox List -->
                    <ul id="userList" class="h-48 px-3 pb-3 overflow-y-auto border border-gray-300 rounded-md dark:border-gray-600">
                        @foreach($users as $user)
                        <li class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input type="checkbox" id="user-{{ $user->id }}" name="users[]" value="{{ $user->id }}" 
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                {{ in_array($user->id, $teamMembers) ? 'checked' : '' }}>
                            <label for="user-{{ $user->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex-grow">
                                {{ $user->lname }}, {{ $user->fname }} {{ substr($user->mname, 0, 1) }}
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('support.index') }}" class="text-gray-700 inline-flex items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        Cancel
                    </a>
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Update Team
                    </button>
                </div>
            </form>
        </div>
      </section>
      <script>
        // Search Functionality
        document.getElementById('searchInput').addEventListener('input', function () {
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
    </script>
@endsection
