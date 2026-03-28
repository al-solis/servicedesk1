<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- ID -->
        <div>
            <x-input-label for="empid" :value="__('Employee ID')" />
            <x-text-input id="empid" class="block mt-1 w-full" type="number" name="empid" :value="old('empid')" required
                autofocus autocomplete="employee id" />
            <x-input-error :messages="$errors->get('empid')" class="mt-2" />
        </div>
        <!-- Last Name -->
        <div class="mt-1">
            <x-input-label for="lname" :value="__('Last Name')" />
            <x-text-input id="lname" class="block mt-1 w-full" type="text" name="lname" :value="old('lname')"
                required autofocus autocomplete="lname" />
            <x-input-error :messages="$errors->get('lname')" class="mt-2" />
        </div>
        <!-- First Name -->
        <div class="mt-1">
            <x-input-label for="fname" :value="__('First Name')" />
            <x-text-input id="fname" class="block mt-1 w-full" type="text" name="fname" :value="old('fname')"
                required autofocus autocomplete="fname" />
            <x-input-error :messages="$errors->get('fname')" class="mt-2" />
        </div>
        <!-- Middle Name -->
        <div class="mt-1">
            <x-input-label for="mname" :value="__('Middle Name')" />
            <x-text-input id="mname" class="block mt-1 w-full" type="text" name="mname" :value="old('mname')"
                autofocus autocomplete="mname" />
            <x-input-error :messages="$errors->get('mname')" class="mt-2" />

            <!-- Email Address -->
            <div class="mt-1">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-1">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-1">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                {{-- <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button> --}}
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ __('Register') }}
                </button>
            </div>
    </form>
</x-guest-layout>
