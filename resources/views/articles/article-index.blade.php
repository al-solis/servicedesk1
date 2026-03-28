@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<link rel="stylesheet" href="{{ asset('/assets/admin/css/style.css') }}">

@extends('layouts.navbar')
@section('navbar-content')
    <section class="bg-white py-6 md:py-10 dark:bg-gray-900">
        <div class="w-full max-w-screen-md mx-auto mt-6">
            <div class="px-2 sm:px-4">
                <div class="lg:flex lg:items-center lg:justify-between lg:gap-4">
                    <h2 class="shrink-0 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Knowledge Base
                        Article
                        ({{ count($articles) }})</h2>

                    <form action="{{ route('articles.article-index') }}" method="GET"
                        class="mt-4 w-full gap-4 sm:flex sm:items-center sm:justify-end lg:mt-0">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full flex-1 lg:max-w-sm">
                            <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" name="search"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 ps-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Search" value="{{ request()->query('search') }}">
                        </div>

                        <button type="button"
                            class="hidden mt-4 w-full shrink-0 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:mt-0 sm:w-auto">Ask
                            a question</button>
                    </form>
                </div>

                @foreach ($articles as $article)
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-200 dark:divide-gray-800">
                            <div class="space-y-4 py-6 md:py-8">
                                <div class="grid gap-4">
                                    <div>
                                        <span
                                            class="inline-block rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300 md:mb-0">
                                            {{ $article->category->description }} </span>
                                    </div>
                                    <a href="{{ route('articles.article-show', $article->id) }}"
                                        class="text-xl font-semibold text-gray-900 hover:underline dark:text-white">“{{ $article->title }}”</a>
                                </div>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">
                                    {{ strip_tags(substr($article->content, 0, 150)) }} ...</p>
                                <div
                                    class="flex items-center justify-between text-sm font-medium text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $userColor = '#' . substr(md5($article->user->id), 0, 6);
                                        @endphp

                                        @if ($article->user->profile_picture)
                                            <img class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800"
                                                {{-- src="{{ Storage::url($article->user->profile_picture) }}"  --}}
                                                src="{{ asset('storage/' . $article->user->profile_picture) }}"
                                                alt="{{ $article->user->lname }}">
                                        @else
                                            <span
                                                class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold
                                  user-color"
                                                style="--user-color: {{ $userColor }};">
                                                {{ strtoupper(substr($article->user->fname, 0, 1)) }}{{ strtoupper(substr($article->user->lname, 0, 1)) }}
                                            </span>
                                        @endif

                                        <span>
                                            Added by {{ $article->user->lname }}, {{ $article->user->fname }}
                                            {{ substr($article->user->mname, 0, 1) }}.
                                            on <time
                                                datetime="{{ $article->created_at }}">{{ $article->created_at->format('M d, Y h:i A') }}</time>
                                        </span>
                                    </div>

                                    <a href="{{ route('articles.article-show', $article->id) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Read more
                                    </a>
                                </div>

                            </div>

                            <div>
                @endforeach

                <!-- Pagination Links -->
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0 mb-4 mr-4">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

    </section>
