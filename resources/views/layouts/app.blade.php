<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Аренда квартир - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Tailwind (от Breeze) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ВОЗВРАЩАЕМ ТВОИ СТИЛИ -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-custom {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            width: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card h2 {
            font-size: 1.2em;
            margin-top: 0;
            color: #333;
        }

        .price {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2em;
        }

        .detail-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-book {
            display: inline-block;
            background: #007bff;
            color: white !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-book:hover {
            background: #0056b3;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Шапка от Breeze (там теперь красивое меню и профиль) -->
        @include('layouts.navigation')

        <div class="container-custom">
            {{-- Уведомление об успехе --}}
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Успех! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Уведомление об ошибке --}}
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Внимание! </strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif
        </div>

        <!-- Основной контент -->
        <div class="container-custom">
            @yield('content')
        </div>
    </div>
</body>

</html>