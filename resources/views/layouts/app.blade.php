<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Class') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Mobile Menu Toggle Button -->
    <div class="md:hidden flex justify-between items-center p-8 bg-white shadow">
        <h1 class="text-3xl font-bold">Menu</h1>
        <button onclick="toggleSidebar()" class="text-5xl focus:outline-none">☰</button>
    </div>

    <div class="flex">

        <!-- Sidebar -->
        <aside id="sidebar" class="h-screen w-96 bg-white text-gray-800 shadow-md p-4 md:block fixed md:relative z-50 transition-all duration-300">
            <div class="flex items-center p-4 space-x-4">
                <img src="https://source.unsplash.com/100x100/?portrait" alt="" class="w-16 h-16 rounded-full">
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</h2>
                    <span class="text-sm text-gray-600"><a href="#" class="hover:underline">View profile</a></span>
                </div>
            </div>

            <div class="mt-6 divide-y divide-gray-300">
               <ul class="space-y-2 text-lg font-medium">
    <li>
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               {{ Request::is('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 hover:text-white text-gray-800' }}">
            <span class="text-2xl">🏠</span>
            <span class="no-underline text-3xl">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/students') }}"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               {{ Request::is('students') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 hover:text-white text-gray-800' }}">
            <span class="text-2xl">📚</span>
            <span class="no-underline text-3xl">Students</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/grades/check') }}"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               {{ Request::is('/grades/check') ? 'bg-blue-600 text-white' : 'hover:bg-blue-600 hover:text-white text-gray-800' }}">
            <span class="text-2xl">📊</span>
            <span class="no-underline text-3xl">Grades Check</span>
        </a>
    </li>
    <li>
        <a href="#"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               hover:bg-blue-100 text-gray-800">
            <span class="text-2xl">💬</span>
            <span class="no-underline text-3xl">Chat</span>
        </a>
    </li>
    <li>
        <a href="#"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               hover:bg-blue-100 text-gray-800">
            <span class="text-2xl">📝</span>
            <span class="no-underline text-3xl">Orders</span>
        </a>
    </li>
    <li>
        <a href="#"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               hover:bg-blue-100 text-gray-800">
            <span class="text-2xl">❤️</span>
            <span class="no-underline text-3xl">Wishlist</span>
        </a>
    </li>
</ul>

<ul class="pt-5 space-y-2 text-lg font-medium">
    <li>
        <a href="#"
           class="flex items-center px-3 py-6 space-x-4 rounded-md transition
               hover:bg-blue-100 text-gray-800">
            <span class="text-2xl">⚙️</span>
            <span class="no-underline text-3xl">Settings</span>
        </a>
    </li>
    <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center px-3 py-8 space-x-4 rounded-md transition
               hover:bg-blue-100 text-gray-800">
            <span class="text-2xl">🚪</span>
            <span class="no-underline text-3xl">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
</ul>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-46">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>
