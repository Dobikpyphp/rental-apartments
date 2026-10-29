<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Аренда Квартир') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Flatpickr CSS (для нашего будущего календаря) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Добавляем несколько глобальных улучшений, которые сложно сделать через Tailwind */
        body {
            font-family: 'Figtree', sans-serif;
        }

        .custom-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 10px -2px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        <!-- Шапка сайта (Навигация Breeze) -->
        @include('layouts.navigation')

        <!-- Основной контейнер -->
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <!-- Блок уведомлений (Flash Messages) -->
            <div class="mb-6">
                @if(session('success'))
                <div class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-500 bg-green-50 rounded-lg shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM//9 11a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="flex items-center p-4 mb-4 text-red-800 border-t-4 border-red-500 bg-red-50 rounded-lg shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zM9 11a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
                </div>
                @endif
            </div>

            <!-- Контент страницы -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Футер -->
        <footer class="py-8 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} Сдача квартир посуточно. Все права защищены.
        </footer>
    </div>

    <!-- Скрипты -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>