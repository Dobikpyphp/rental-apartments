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
    <!-- <a href="#" class="btn-book">Забронировать сейчас</a> -->
    <div class="mt-8 p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Забронировать квартиру</h3>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Скрытое поле: передаем ID квартиры, чтобы контроллер знал, ЧТО бронируем --}}
            <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Дата заезда --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Дата заезда</label>
                    <input type="date" name="check_in"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                        required>
                </div>

                {{-- Дата выезда --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Дата выезда</label>
                    <input type="date" name="check_out"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                        required>
                </div>
            </div>

            <button type="submit" class="w-full btn-book text-center py-3">
                Подтвердить бронирование
            </button>
        </form>
    </div>

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