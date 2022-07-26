<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
<div id="app">
    <nav class="px-2 bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
        <div class="container flex flex-wrap justify-between items-center mx-auto">
            <a href="#" class="flex items-center">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">User Table</span>
            </a>
            <button data-collapse-toggle="mobile-menu" type="button" class="inline-flex justify-center items-center ml-3 text-gray-400 rounded-lg md:hidden hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-500" aria-controls="mobile-menu-2" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="mobile-menu">
            </div>
        </div>
    </nav>
    <div class="m-32">
        @php
            $url = '/users/list';
            $defaultSortField = "created_at";
            $defaultSortDirection = "desc";
            $showEntries=20
        @endphp
        <user-table
            :show_entries="{{json_encode([10,20,50,75,100])}}"
            :columns="{{json_encode($columns)}}"
            :hobbies="{{json_encode($hobbies)}}"
            :url="{{json_encode($url)}}"
            :show_entries_default="{{$showEntries}}"
            :default_sort_field="{{json_encode($defaultSortField)}}"
            :default_sort_direction="{{json_encode($defaultSortDirection)}}"
        />
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
