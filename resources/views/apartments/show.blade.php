<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>{{ $apartment->title }} - Аренда</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            background: white;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
        }

        h1 {
            color: #333;
        }

        .price {
            font-size: 1.5em;
            color: green;
            font-weight: bold;
            margin: 20px 0;
        }

        .details {
            line-height: 1.6;
            color: #444;
        }

        .btn-book {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn-book:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="/" class="back-link">← Вернуться в каталог</a>

        <h1>{{ $apartment->title }}</h1>
        <p><b>Адрес:</b> {{ $apartment->address }}</p>
        <p><b>Количество комнат:</b> {{ $apartment->rooms }}</p>

        <div class="price">{{ $apartment->price_per_night }} руб / сутки</div>

        <div class="details">
            <h3>Описание:</h3>
            <p>{{ $apartment->description }}</p>
        </div>

        <p class="status">
            {{ $apartment->is_available ? '✅ Квартира доступна для бронирования' : '❌ Извините, квартира сейчас занята' }}
        </p>

        @if($apartment->is_available)
        <a href="#" class="btn-book">Забронировать сейчас</a>
        @endif
    </div>
</body>

</html>