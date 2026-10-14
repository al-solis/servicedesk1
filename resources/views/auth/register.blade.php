<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create your account</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Fill in your details to register as a client.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
        @csrf

        {{-- Client details --}}
        <fieldset>
            <legend class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Client details
            </legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Client ID -->
                <div>
                    <x-input-label for="empid" :value="__('Client ID')" />
                    <x-text-input id="empid" class="mt-1 block w-full" type="number" name="empid"
                        :value="old('empid')" required autofocus autocomplete="off" />
                    <x-input-error :messages="$errors->get('empid')" class="mt-2" />
                </div>

                <!-- Company -->
                <div>
                    <x-input-label for="company" :value="__('Company')" />
                    <x-text-input id="company" class="mt-1 block w-full" type="text" name="company"
                        :value="old('company')" required autocomplete="organization" />
                    <x-input-error :messages="$errors->get('company')" class="mt-2" />
                </div>
            </div>
        </fieldset>

        {{-- Name --}}
        <fieldset>
            <legend class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Your name
            </legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Last Name -->
                <div>
                    <x-input-label for="lname" :value="__('Last Name')" />
                    <x-text-input id="lname" class="mt-1 block w-full" type="text" name="lname"
                        :value="old('lname')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('lname')" class="mt-2" />
                </div>

                <!-- First Name -->
                <div>
                    <x-input-label for="fname" :value="__('First Name')" />
                    <x-text-input id="fname" class="mt-1 block w-full" type="text" name="fname"
                        :value="old('fname')" required autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('fname')" class="mt-2" />
                </div>

                <!-- Middle Name -->
                <div class="sm:col-span-2">
                    <x-input-label for="mname" :value="__('Middle Name')" />
                    <x-text-input id="mname" class="mt-1 block w-full" type="text" name="mname"
                        :value="old('mname')" autocomplete="additional-name" />
                    <x-input-error :messages="$errors->get('mname')" class="mt-2" />
                </div>
            </div>
        </fieldset>

        {{-- Account --}}
        <fieldset>
            <legend class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Account
            </legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Email Address -->
                <div class="sm:col-span-2">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="relative mt-1">
                        <x-text-input id="password" class="block w-full pr-10" type="password" name="password" required
                            autocomplete="new-password" />
                        <button type="button" data-toggle-password="password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                            aria-label="Show password" aria-pressed="false">
                            <svg data-eye class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg data-eye-off class="hidden h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <div class="relative mt-1">
                        <x-text-input id="password_confirmation" class="block w-full pr-10" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                        <button type="button" data-toggle-password="password_confirmation"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                            aria-label="Show password" aria-pressed="false">
                            <svg data-eye class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg data-eye-off class="hidden h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </fieldset>

        {{-- Actions --}}
        <div>
            <button type="submit" id="registerButton"
                class="inline-flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ __('Register') }}
            </button>

            <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ __('Already registered?') }}
                <a href="{{ route('login') }}"
                    class="font-medium text-blue-700 underline hover:text-blue-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:text-blue-400">
                    {{ __('Log in') }}
                </a>
            </p>
        </div>
    </form>

    <script>
        // Show / hide password
        document.querySelectorAll('[data-toggle-password]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = document.getElementById(btn.dataset.togglePassword);
                const show = input.type === 'password';

                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show);
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                btn.querySelector('[data-eye]').classList.toggle('hidden', show);
                btn.querySelector('[data-eye-off]').classList.toggle('hidden', !show);
            });
        });

        // Prevent double submits
        document.getElementById('registerForm').addEventListener('submit', function() {
            const button = document.getElementById('registerButton');
            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');
        });
    </script>
</x-guest-layout>
