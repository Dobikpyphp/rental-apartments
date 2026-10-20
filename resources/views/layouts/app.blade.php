<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аренда квартир - @yield('title')</title>
    <style>
        /* Все общие стили теперь живут здесь! */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        header {
            background: #333;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        header a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 0.9rem;
            margin-top: 40px;
        }

        /* Стили для карточек (переносим из index) */
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

        /* Стили для страницы деталей (переносим из show) */
        .detail-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-book {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <a href="/">🏠 Мои Квартиры</a>
    </header>

    <div class="container">
        {{-- Сюда будет вставляться содержимое каждой страницы --}}
        @yield('content')
    </div>

    <footer>
        &copy; {{ date('Y') }} Бизнес по аренде квартир. Все права защищены.
    </footer>
</body>

</html>