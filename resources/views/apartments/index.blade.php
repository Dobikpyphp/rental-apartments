@extends('layouts.app') {{-- Говорим: "Возьми каркас из layouts/app.blade.php" --}}

@section('title', 'Каталог квартир') {{-- Заполняем @yield('title') в макете --}}

@section('content') {{-- Все, что внутри, попадет в @yield('content') --}}
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
        <a href="{{ route('apartments.show', $apartment->id) }}">Посмотреть детали</a> {{-- Функция route() берет имя маршрута apartments.show и подставляет в него ID текущей квартиры из цикла. --}}
    </div>
    @endforeach
</div>
@endsection