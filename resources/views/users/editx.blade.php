@extends('layouts.app')
@section('navbar-content')    
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update Profile</h2>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form class="divide-y divide-gray-200 lg:col-span-9" action="{{ route('users.update', $users->id) }}" method="POST">
                @csrf
                @method('PUT')
                    <!-- Profile section -->
                    <div class="py-6 px-4 sm:p-6 lg:pb-8">
                        <div>
                            <h2 class="text-lg font-medium leading-6 text-gray-900">Profile</h2>
                            <p class="mt-1 text-sm text-gray-500">This information will be displayed publicly so be careful what
                                you share.</p>
                        </div>

                        <div class="mt-6 flex flex-col lg:flex-row">
                            <div class="flex-grow space-y-6">
                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="empid" class="block text-sm font-medium text-gray-700">Employee ID</label>
                                        <input type="text" name="empid" id="empid" autocomplete="employee-id" 
                                            value="{{ old('empid', $users->empid) }}" 
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            {{-- <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">email:</span> --}}
                                            <input type="text" name="email" id="email" autocomplete="email" 
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                                value="{{ old('email', $users->email) }}">
                                        </div>
                                    </div>
                                </div>
                                

                                <div>
                                    <label for="about" class="block text-sm font-medium text-gray-700">About</label>
                                    <div class="mt-1">
                                        <textarea id="about" name="about" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex-grow lg:mt-0 lg:ml-6 lg:flex-shrink-0 lg:flex-grow-0">
                                <p class="text-sm font-medium text-gray-700" aria-hidden="true">Photo</p>
                                <div class="mt-1 lg:hidden">
                                    <div class="flex items-center">
                                        <div class="inline-block h-12 w-12 flex-shrink-0 overflow-hidden rounded-full"
                                        aria-hidden="true">
                                        <img class="h-full w-full rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=320&amp;h=320&amp;q=80" alt="">
                                        </div>
                                        <div class="ml-5 rounded-md shadow-sm">
                                        <div
                                            class="group relative flex items-center justify-center rounded-md border border-gray-300 py-2 px-3 focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2 hover:bg-gray-50">
                                            <label for="mobile-user-photo" class="pointer-events-none relative text-sm font-medium leading-4 text-gray-700">
                                            <span>Change</span>
                                            <span class="sr-only"> user photo</span>
                                            </label>
                                            <input id="mobile-user-photo" name="user-photo" type="file" class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0">
                                        </div>
                                        </div>
                                    </div>
                            </div>

                            <div class="relative hidden overflow-hidden rounded-full lg:block">
                                <img class="relative h-40 w-40 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=4&amp;w=320&amp;h=320&amp;q=80" alt="">
                                <label for="desktop-user-photo" class="absolute inset-0 flex h-full w-full items-center justify-center bg-black bg-opacity-75 text-sm font-medium text-white opacity-0 focus-within:opacity-100 hover:opacity-100">
                                    <span>Change</span>
                                    <span class="sr-only"> user photo</span>
                                    <input type="file" id="desktop-user-photo" name="user-photo" class="absolute inset-0 h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0">
                                </label>
                            </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-12 gap-6">
                            <div class="col-span-12 sm:col-span-12 grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label for="fname" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="fname" id="fname" autocomplete="given-name" 
                                        value="{{ old('fname', $users->fname) }}" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                        
                                <div>
                                    <label for="mname" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="mname" id="mname" autocomplete="middle-name" 
                                        value="{{ old('mname', $users->mname) }}" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                        
                                <div>
                                    <label for="lname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" name="lname" id="lname" autocomplete="family-name" 
                                        value="{{ old('lname', $users->lname) }}" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                            </div>
                        
                            <div class="col-span-12">
                                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                                <input type="text" name="url" id="url" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        
                            <div class="col-span-12 sm:col-span-6">
                                <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                                <input type="text" name="company" id="company" autocomplete="organization" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Privacy section -->
                    <div class="divide-y divide-gray-200 pt-6">
                        <div class="px-4 sm:px-6">
                        <div>
                            <h2 class="text-lg font-medium leading-6 text-gray-900">Privacy</h2>
                            <p class="mt-1 text-sm text-gray-500">Ornare eu a volutpat eget vulputate. Fringilla commodo amet.
                            </p>
                        </div>
                        <ul role="list" class="mt-2 divide-y divide-gray-200">
                            <li class="flex items-center justify-between py-4" x-data="{ on: true }">
                            <div class="flex flex-col">
                                <p class="text-sm font-medium text-gray-900" id="privacy-option-1-label">Available to hire</p>
                                <p class="text-sm text-gray-500" id="privacy-option-1-description">Nulla amet tempus sit
                                accumsan. Aliquet turpis sed sit lacinia.</p>
                            </div>
                            <button type="button" class="bg-gray-200 relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" role="switch" aria-checked="true" x-ref="switch" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'bg-teal-500': on, 'bg-gray-200': !(on) }" aria-labelledby="privacy-option-1-label" aria-describedby="privacy-option-1-description" :aria-checked="on.toString()" @click="on = !on">
                                <span aria-hidden="true" class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"></span>
                            </button>
                            </li>
                            <li class="flex items-center justify-between py-4" x-data="{ on: false }">
                            <div class="flex flex-col">
                                <p class="text-sm font-medium text-gray-900" id="privacy-option-2-label">Make account private
                                </p>
                                <p class="text-sm text-gray-500" id="privacy-option-2-description">Pharetra morbi dui mi mattis
                                tellus sollicitudin cursus pharetra.</p>
                            </div>
                            <button type="button" class="bg-gray-200 relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" role="switch" aria-checked="false" x-ref="switch" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'bg-teal-500': on, 'bg-gray-200': !(on) }" aria-labelledby="privacy-option-2-label" aria-describedby="privacy-option-2-description" :aria-checked="on.toString()" @click="on = !on">
                                <span aria-hidden="true" class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"></span>
                            </button>
                            </li>
                            <li class="flex items-center justify-between py-4" x-data="{ on: true }">
                            <div class="flex flex-col">
                                <p class="text-sm font-medium text-gray-900" id="privacy-option-3-label">Allow commenting</p>
                                <p class="text-sm text-gray-500" id="privacy-option-3-description">Integer amet, nunc hendrerit
                                adipiscing nam. Elementum ame</p>
                            </div>
                            <button type="button" class="bg-gray-200 relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" role="switch" aria-checked="true" x-ref="switch" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'bg-teal-500': on, 'bg-gray-200': !(on) }" aria-labelledby="privacy-option-3-label" aria-describedby="privacy-option-3-description" :aria-checked="on.toString()" @click="on = !on">
                                <span aria-hidden="true" class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"></span>
                            </button>
                            </li>
                            <li class="flex items-center justify-between py-4" x-data="{ on: true }">
                            <div class="flex flex-col">
                                <p class="text-sm font-medium text-gray-900" id="privacy-option-4-label">Allow mentions</p>
                                <p class="text-sm text-gray-500" id="privacy-option-4-description">Adipiscing est venenatis enim
                                molestie commodo eu gravid</p>
                            </div>
                            <button type="button" class="bg-gray-200 relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" role="switch" aria-checked="true" x-ref="switch" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'bg-teal-500': on, 'bg-gray-200': !(on) }" aria-labelledby="privacy-option-4-label" aria-describedby="privacy-option-4-description" :aria-checked="on.toString()" @click="on = !on">
                                <span aria-hidden="true" class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" x-state:on="Enabled" x-state:off="Not Enabled" :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"></span>
                            </button>
                            </li>
                        </ul>
                        </div>                      
                    </div>   
                    
                    

                    
                    <div class="mt-4 flex justify-end py-4 px-4 sm:px-6 space-x-4">
                        <a href="{{ route('users.index') }}" class="text-gray-700 inline-flex items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            Cancel
                        </a>
                        <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Update User
                        </button>
                    </div>
            </form>
        </div>
      </section>
@endsection