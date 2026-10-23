@extends('layouts.app')

@section('title', $apartment->title)

@section('content')
<div class="detail-card">
    <a href="/" style="text-decoration: none; color: #666;">← Вернуться в каталог</a>

    <h1>{{ $apartment->title }}</h1>
    <p><b>Адрес:</b> {{ $apartment->address }}</p>
    <p><b>Количество комнат:</b> {{ $apartment->rooms }}</p>

    <div class="price">{{ $apartment->price_per_night }} руб / сутки</div>

    <div style="margin: 20px 0;">
        <h3>Описание:</h3>
        <p>{{ $apartment->description }}</p>
    </div>

    <p>
        {{ $apartment->is_available ? '✅ Квартира доступна для бронирования' : '❌ Извините, квартира сейчас занята' }}
    </p>

    @if($apartment->is_available)
    <a href="#" class="btn-book">Забронировать сейчас</a>
    @endif
</div>
<div class="gallery grid grid-cols-2 md:grid-cols-3 gap-4 mt-6">
    @forelse($apartment->images as $image)
    <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200">
        <img src="{{ Storage::url($image->path) }}"
            alt="Фото квартиры"
            class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
    </div>
    @empty
    <p class="text-gray-500 italic col-span-full text-center">Фотографии пока не добавлены</p>
    @endforelse
</div>

@endsection

{{-- grid grid-cols-2 md:grid-cols-3 - делает сетку: 2 колонки на телефонах, 3 на компьютерах.
gap-4 - делает аккуратные отступы между фото.
h-48 object-cover - самое важное! Все фото будут одной высоты, а лишние части обрежутся (без искажения пропорций), чтобы галерея была ровной.
hover:scale-105 transition-transform - добавил легкий эффект увеличения при наведении курсора (выглядит очень современно). --}}

{{-- @forelse работает так: «Попробуй перебрать элементы, а если их нет — выполни блок @empty». --}}