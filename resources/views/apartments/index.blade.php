@extends('layouts.app')

@section('title', 'Каталог квартир')

@section('content')
<div class="mb-10 text-center">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-3">Наши квартиры</h1>
    <p class="text-gray-600 max-w-2xl mx-auto">Выберите идеальный вариант для вашего отдыха. У нас только проверенные объекты с гарантией чистоты и комфорта.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($apartments as $apartment)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">

        <!-- Блок с фото -->
        <div class="relative h-56 overflow-hidden">
            @if($apartment->images->first())
            <img src="{{ Storage::url($apartment->images->first()->path) }}"
                alt="{{ $apartment->title }}"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
            @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                <span>Нет фото</strong>
            </div>
            @endif

            <!-- Статус (Бейдж) -->
            <div class="absolute top-4 right-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm 
                            {{ $apartment->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $apartment->is_available ? 'Свободна' : 'Занята' }}
                </span>
            </div>
        </div>

        <!-- Контент карточки -->
        <div class="p-6">
            <div class="flex justify-between items-start mb-2">
                <h2 class="text-xl font-bold text-gray-800 truncate">{{ $apartment->title }}</h2>
                <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-2 py-1 rounded">
                    {{ $apartment->rooms }} комн.
                </span>
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2 h-10">
                {{ $apartment->description }}
            </p>

            <div class="flex items-center text-gray-500 text-sm mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414M13.414 12.414L12 11m1.414 0L10.586 11M12 11V1l11 11H12z"></path>
                </svg>
                {{ $apartment->address }}
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="text-left">
                    <span class="text-sm text-gray-500 block">Цена за сутки</span>
                    <span class="text-2xl font-extrabold text-green-600">{{ number_format($apartment->price_night, 0, '.', ' ') }} ₽</span>
                </div>
                <a href="{{ route('apartments.show', $apartment->id) }}"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                    Детали
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection