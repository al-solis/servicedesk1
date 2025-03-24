<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

@extends('layouts.app')
@section('navbar-content')    
<section class="bg-white dark:bg-gray-900">    
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Create New Article</h2>
        <form action="{{ route('kb.store') }}" method="POST">
            @csrf            
            <div>
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                    <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter short title" required>
                </div>               
            </div>   
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Category</label>
                    <select name="support_type_id" id="support_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="">Select status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>                        
                    </select>
                </div>
            </div>

            {{-- <div class="grid gap-4 sm:gap-6 mt-2">
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                    <textarea type="text" name="xxcontent" id="xxcontent" rows='5' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type content" required></textarea>
                </div>                
            </div> --}}

            <div class="grid gap-4 sm:gap-6 mt-2">
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                    <input id="article" type="hidden" name="article">
                    <trix-editor input="article"></trix-editor>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('kb.index') }}" class="text-gray-700 inline-flex items-center bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    Cancel
                </a>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Create Article
                </button>
            </div>            

            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
        </form>
    </div>
  </section> 
  <script>
//    document.addEventListener("DOMContentLoaded", function() {
//     let form = document.querySelector("form");
//     let trixEditor = document.querySelector("trix-editor");
//     let hiddenInput = document.getElementById("article");

//     form.addEventListener("submit", function(event) {
//         event.preventDefault();

//         // Debugging: Log the content to the console
//         console.log("Trix Editor Content:", trixEditor.editor.getDocument().toString());

//         hiddenInput.value = trixEditor.editor.getDocument().toString();

//         // Debugging: Log the hidden input value to the console
//         console.log("Hidden Input Value:", hiddenInput.value);

//         form.submit();
//     });
// });

    document.addEventListener("trix-attachment-add", function(event) {
        let attachment = event.attachment;
        if (attachment.file) {
            let formData = new FormData();
            formData.append("file", attachment.file);
            
            fetch("{{ route('kb.upload') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                attachment.setAttributes({ url: data.url, href: data.url });
            });
        }
    });
    </script>
@endsection