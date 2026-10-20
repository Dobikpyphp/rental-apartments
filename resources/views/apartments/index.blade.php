<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Аренда квартир - Каталог</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            font-size: 1.2em;
            margin-top: 0;
        }

        .price {
            color: green;
            font-weight: bold;
            font-size: 1.1em;
        }

        .status {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Наши квартиры</h1>

    <div class="catalog">
        {{-- Это цикл Blade. Он проходит по каждой квартире из массива $apartments --}}
        @foreach($apartments as $apartment)
        <div class="card">
            <h2>{{ $apartment->title }}</h2>
            <p>{{ Str::limit($apartment->description, 100) }}</p>
            <p><b>Адрес:</b> {{ $apartment->address }}</p>
            <p class="price">{{ $apartment->price_per_night }} руб / сутки</p>
            <p class="status">
                {{ $apartment->is_available ? '✅ Свободна' : '❌ Занята' }}
            </p>
            <a href="#">Посмотреть детали</a>
        </div>
        @endforeach
    </div>
</body>

</html>