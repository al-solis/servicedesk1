@extends('layouts.app')
@section('navbar-content')    
<section class="bg-white dark:bg-gray-900">    
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Create New Team</h2>
        <form action="{{ route('support.store') }}" method="POST">
            @csrf
            {{-- @method('PUT') --}}
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Team Name</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter team name" required>
                </div>                  
                
            </div>            
                <!-- Searchable List with Checkboxes -->
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Team Members</label>

                    <!-- Search Input -->
                    <input type="text" id="searchInput" placeholder="Search users..." class="w-full p-2 mb-2 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    
                    <!-- Checkbox List -->
                    <ul id="userList" class="h-48 px-3 pb-3 overflow-y-auto border border-gray-300 rounded-md dark:border-gray-600">
                        @if(!empty($users) && $users->count())
                            @foreach($users as $user)
                                <li class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <input type="checkbox" id="user-{{ $user->id }}" name="users[]" value="{{ $user->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="user-{{ $user->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 flex-grow">
                                        {{ $user->lname }}, {{ $user->fname }} 
                                        {{-- {{ substr($user->mname, 0, 1) }} --}}
                                    </label>
                                </li>
                            @endforeach
                        @else
                            <li class="p-2 text-sm text-gray-500 dark:text-gray-400">No users found.</li>
                        @endif
                    </ul>
                </div>
            
            {{-- Button --}}
            {{-- <div>
                <a href="#" class="flex items-center p-3 text-sm font-medium text-red-600 border-t border-gray-200 rounded-b-lg bg-gray-50 dark:border-gray-600 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-red-500 hover:underline">
                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z"/>
                </svg>
                    Delete user
                </a>
            </div> --}}    
            
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('support.index') }}" class="text-gray-700 inline-flex items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    Cancel
                </a>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Create Team
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
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