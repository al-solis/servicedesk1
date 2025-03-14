@extends('layouts.app')
@section('navbar-content')    
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{$articles->title}}</h2>
            <form action="#">
                <div>
                    <span class="inline-block rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300 md:mb-0"> {{ $articles->category->description}} </span>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                        <div class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">
                            @php
                                $userColor = '#' . substr(md5($articles->user->id), 0, 6);
                            @endphp
                    
                            @if($articles->user->profile_picture)
                                <img class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800" 
                                    src="{{ Storage::url($articles->user->profile_picture) }}" 
                                    alt="{{ $articles->user->lname }}">
                            @else
                                <span class="w-10 h-10 flex justify-center items-center rounded-full text-white font-bold" 
                                    style="background-color: {{ $userColor }};">
                                    {{ strtoupper(substr($articles->user->fname, 0, 1)) }}{{ strtoupper(substr($articles->user->lname, 0, 1)) }}
                                </span>
                            @endif
                        &nbsp; Added by {{ $articles->user->lname }}, {{ $articles->user->fname }} {{ substr($articles->user->mname,0,1) }}. on <time datetime="2021-01-06">{{ $articles->created_at->format('M d, Y') }} {{ $articles->created_at->format('h:i:sa') }}</time>
                        {{-- <a href="#" class="text-gray-900 hover:underline dark:text-white">Bonnie Green</a> --}}
                    </p>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">                    
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            {!! nl2br(e($articles->content)) !!}
                        </p>
                    </div>  
                </div>
                <div class="flex justify-end mt-6">
                    <a href="{{ route('articles.article-index') }}"  class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Close
                    </a>
                </div>
            </form>
        </div>
      </section>
@endsection
