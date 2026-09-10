@extends('layouts.app')

@section('navbar-content')

    <section class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 py-10">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 rounded-full bg-blue-100
                            flex items-center justify-center mr-4">
                        <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21.5 3.5L2.9 10.7c-.9.4-.9 1.2-.2 1.5l4.7 1.8 1.8 5.5c.2.6.4.8.8.8.4 0 .6-.1.9-.4l2.5-2.4 4.9 3.6c.9.5 1.6.1 1.8-.9l3.1-15c.3-1.3-.5-1.9-1.7-1.4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            Telegram Integration
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Connect your ISMS account to Telegram
                        </p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 text-sm text-green-800
                            rounded-lg bg-green-50">
                        {{ session('success') }}
                    </div>
                @endif


                @if ($telegramAccount && $telegramAccount->status === 'Active')
                    <div class="p-5 rounded-lg border
                            border-green-200 bg-green-50">
                        <div class="flex items-center mb-4">
                            <span
                                class="w-3 h-3 bg-green-500
                                     rounded-full mr-2"></span>
                            <span class="font-semibold text-green-800">
                                Connected
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-700">
                            <div>
                                <strong>Telegram:</strong>
                                @if ($telegramAccount->telegram_username)
                                    {{-- @{{ $telegramAccount - > telegram_username }} --}}
                                    <a href="https://t.me/{{ $telegramAccount->telegram_username }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        {{ '@' . $telegramAccount->telegram_username }}
                                    </a>
                                @else
                                    {{ $telegramAccount->telegram_first_name }}
                                @endif
                            </div>

                            <div>
                                <strong>Connected:</strong>
                                {{ optional($telegramAccount->linked_at)->format('M d, Y h:i A') }}
                            </div>
                        </div>


                        <form action="{{ route('telegram.disconnect') }}" method="POST" class="mt-6">
                            @csrf

                            <button type="submit" onclick="return confirm('Disconnect Telegram from your ISMS account?')"
                                class="px-5 py-2.5 text-sm font-medium
                                   text-white bg-red-600 rounded-lg
                                   hover:bg-red-700">
                                Disconnect Telegram
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-5 rounded-lg border
                            border-gray-200 bg-gray-50">
                        @if ($telegramAccount && $telegramAccount->status === 'Blocked')
                            <div class="mb-4 p-3 rounded bg-yellow-50 text-sm text-yellow-800">
                                Your Telegram account was previously disconnected.
                                You can reconnect anytime.
                            </div>
                        @endif

                        <p class="text-sm text-gray-600 mb-6">
                            Connect your ISMS account to
                            <strong>{{ '@' . ENV('TELEGRAM_BOT_USERNAME') }}</strong> to receive:
                        </p>


                        <ul class="space-y-3 text-sm text-gray-700 mb-6">
                            <li>
                                🎫 View your tickets
                            </li>
                            <li>
                                🔎 Check ticket status
                            </li>
                            <li>
                                🔔 Receive automatic ticket updates
                            </li>
                            <li>
                                💬 Receive support replies
                            </li>
                        </ul>

                        <a href="{{ route('telegram.connect') }}"
                            class="inline-flex items-center px-5 py-2.5
                               text-sm font-medium text-white
                               bg-blue-600 rounded-lg
                               hover:bg-blue-700">
                            Connect Telegram
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection
