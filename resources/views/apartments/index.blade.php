@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<!-- HERO СЕКЦИЯ (Ваш главный баннер) -->
<section class="text-center py-12 lg:py-16">
    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
        Найдите идеальное место для <span class="text-indigo-600">отдыха</span>
    </h1>
    <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto">
        Комфортабельные апартаменты в лучших районах города с полным набором удобств для вашего комфорта.
    </p>

    <!-- ФОРМА БЫСТРОГО ПОИСКА -->
    <form action="{{ route('apartments.index') }}" method="GET" class="bg-white p-3 rounded-2xl shadow-xl border border-gray-100 flex flex-col md:flex-row gap-3 max-w-4xl mx-auto">
        <div class="flex-1 px-4 py-2 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Заезд</label>
            <input type="text" name="check_in" id="check-in" value="{{ request('check_in') }}" placeholder="Выберите дату" class="w-full focus:outline-none text-gray-700 font-medium bg-transparent cursor-pointer">
        </div>

        <div class="flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-100 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Выезд</label>
            <input type="text" name="check_out" id="check-out" value="{{ request('check_out') }}" placeholder="Выберите дату" class="w-full focus:outline-none text-gray-700 font-medium bg-transparent cursor-pointer">
        </div>

        <div class="flex-1 px-4 py-2 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Гости</label>
            <select name="guests" class="w-full focus:outline-none text-gray-700 font-medium cursor-pointer bg-transparent">
                <option value="">Все</option>
                <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 гость</option>
                <option value="2" {{ request('guests') == '2' ? 'selected' : '' }}>2 гостя</option>
                <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3-4 гостя</option>
                <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ гостей</option>
            </select>
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all active:scale-95">
            Найти
        </button>
    </form>

</section>

<!-- МОДЕРНАЯ ПАНЕЛЬ ФИЛЬТРОВ (Появляется под Hero-секцией) -->
<div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 mb-12">
    <form action="{{ route('apartments.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-6">

        <div class="flex flex-wrap items-center gap-6">
            <!-- Фильтр по цене -->
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-500">Бюджет до:</span>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₽</span>
                    <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="5000"
                        class="pl-7 pr-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm w-28">
                </div>
            </div>

            <!-- Фильтр по комнатам -->
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-500">Комнаты:</span>
                <select name="rooms" class="py-2 pl-3 pr-8 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-white">
                    <option value="">Все</option>
                    <option value="1" {{ request('rooms') == '1' ? 'selected' : '' }}>1 комната</option>
                    <option value="2" {{ request('rooms') == '2' ? 'selected' : '' }}>2 комнаты</option>
                    <option value="3" {{ request('rooms') == '3' ? 'selected' : '' }}>3+ комнаты</option>
                </select>
            </div>

            <!-- Удобства (Чекбоксы) -->
            <div class="flex items-center gap-4 pl-6 border-l border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="wifi" value="1" {{ request('wifi') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-600 group-hover:text-indigo-600 transition">Wi-Fi</span>
                </label>
            </div>
        </div>

        <!-- Кнопки управления -->
        <div class="flex items-center gap-3 ml-auto">
            <a href="{{ route('apartments.index') }}" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition px-4 py-2">
                Сбросить
            </a>
            <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-gray-800 transition-all active:scale-95">
                Применить
            </button>
        </div>
    </form>
</div>

<!-- КАТАЛОГ КВАРТИР -->
<section class="py-12">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Доступные объекты</h2>
            <p class="text-gray-500">Найдено {{ $apartments->count() }} подходящих вариантов</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($apartments as $apt)
        <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="relative h-64 overflow-hidden">
                <img src="{{ $apt->images->first() ? Storage::url($apt->images->first()->path) : 'https://via.placeholder.com/800x600?text=Нет+фото' }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-gray-800 shadow-sm">
                    ⭐ {{ number_format($apt->rating ?? 5.0, 1) }}
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $apt->title }}</h3>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($apt->price_per_night, 0, '.', ' ') }} ₽</span>
                        <span class="text-gray-400 text-sm block">/ сутки</span>
                    </div>
                </div>
                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $apt->description }}</p>
                <div class="flex items-center gap-4 text-gray-400 text-xs mb-6">
                    <span class="flex items-center gap-1">🛏️ {{ $apt->rooms }} комн.</span>
                    <span class="flex items-center gap-1">📶 Wi-Fi</span>
                </div>
                <a href="{{ route('apartments.show', $apt->id) }}"
                    class="block text-center w-full py-3 rounded-xl border-2 border-indigo-600 text-indigo-600 font-semibold hover:bg-indigo-600 hover:text-white transition-all">
                    Подробнее
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-bold text-gray-900">Ничего не нашли</h3>
            <p class="text-gray-500">Попробуйте изменить фильтры или цену</p>
            <a href="{{ route('apartments.index') }}" class="mt-4 inline-block text-indigo-600 font-semibold hover:underline">Сбросить все фильтры</a>
        </div>
        @endforelse
    </div>
</section>

<!-- Инициализация Flatpickr -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#check-in", {
            dateFormat: "d.m.Y",
            allowInput: true
        });
        flatpickr("#check-out", {
            dateFormat: "d.m.Y",
            allowInput: true
        });
    });
</script> -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const config = {
            altInput: true, // Создает второй, "красивый" инпут для пользователя
            altFormat: "d.m.Y", // Формат, который видит пользователь (04.11.2026)
            dateFormat: "Y-m-d", // Формат, который реально отправляется на сервер (2026-11-04)
            allowInput: true,
        };

        flatpickr("#check-in", config);
        flatpickr("#check-out", config);
    });
</script>

@endsection