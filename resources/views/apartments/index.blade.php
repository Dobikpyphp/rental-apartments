@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<!-- HERO СЕКЦИЯ (внутри вашего контейнера) -->
<section class="text-center py-12 lg:py-16">
    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
        Найдите идеальное место для <span class="text-indigo-600">отдыха</span>
    </h1>
    <p class="text-lg text-gray-500 mb-10 max-w-2xl mx-auto">
        Комфортабельные апартаменты в лучших районах города с полным набором удобств для вашего комфорта.
    </p>

    <!-- ФОРМА ПОИСКА -->
    <div class="bg-white p-3 rounded-2xl shadow-xl border border-gray-100 flex flex-col md:flex-row gap-3 max-w-4xl mx-auto">
        <div class="flex-1 px-4 py-2 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Заезд</label>
            <input type="text" id="check-in" placeholder="Выберите дату" class="w-full focus:outline-none text-gray-700 font-medium bg-transparent cursor-pointer">
        </div>
        <div class="flex-1 px-4 py-2 border-b md:border-b-0 md:border-r border-gray-100 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Выезд</label>
            <input type="text" id="check-out" placeholder="Выберите дату" class="w-full focus:outline-none text-gray-700 font-medium bg-transparent cursor-pointer">
        </div>
        <div class="flex-1 px-4 py-2 text-left">
            <label class="block text-xs font-semibold text-gray-400 uppercase">Гости</label>
            <select class="w-full focus:outline-none text-gray-700 font-medium cursor-pointer bg-transparent">
                <option>1 гость</option>
                <option>2 гостя</option>
                <option>3-4 гостя</option>
            </select>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all active:scale-95">
            Найти
        </button>
    </div>
</section>

<!-- КАТАЛОГ КВАРТИР -->
<section class="py-12">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Популярные объекты</h2>
            <p class="text-gray-500">Лучшие предложения этой недели</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($apartments as $apt)
        <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="relative h-64 overflow-hidden">
                <img src="{{ $apt->images ?? 'https://via.placeholder.com/800x600?text=Нет+фото' }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-gray-800 shadow-sm">
                    ⭐ {{ number_format($apt->rating ?? 5.0, 1) }}
                </div>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $apt->title }}</h3>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($apt->price, 0, '.', ' ') }} ₽</span>
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
        <div class="col-span-full text-center py-10 text-gray-500">К сожалению, пока нет доступных квартир.</div>
        @endforelse
    </div>
</section>

<!-- Инициализация Flatpickr для поиска -->
<script>
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
</script>
@endsection