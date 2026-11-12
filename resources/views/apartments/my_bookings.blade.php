@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900">Мои поездки</h1>
        <p class="text-gray-500">История ваших бронирований и их текущий статус</p>
    </div>
    <a href="{{ route('apartments.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
        Забронировать еще
    </a>
</div>

@if($bookings->isEmpty())
<div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
    <div class="text-6xl mb-4">🧳</div>
    <h3 class="text-xl font-bold text-gray-900">У вас пока нет бронирований</h3>
    <p class="text-gray-500 mb-6">Пора отправиться в путешествие!</p>
    <a href="{{ route('apartments.index') }}" class="text-indigo-600 font-semibold hover:underline">Перейти в каталог</a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($bookings as $booking)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ $booking->apartment->title }}</h3>
                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 
                                   ($booking->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ $booking->status == 'confirmed' ? 'Подтверждено' : ($booking->status == 'cancelled' ? 'Отменено' : 'Ожидает') }}
                </span>
            </div>

            <div class="space-y-2 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <span class="w-20 font-medium">Заезд:</span>
                    <span>{{ \Carbon\Carbon::parse($booking->check_in)->format('d.m.Y') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="w-20 font-medium">Выезд:</span>
                    <span>{{ \Carbon\Carbon::parse($booking->check_out)->format('d.m.Y') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-900 font-bold pt-2 border-t border-gray-50">
                    <span class="w-20 font-medium text-gray-500">Итого:</span>
                    <span>{{ number_format($booking->total_price, 0, '.', ' ') }} ₽</span>
                </div>
            </div>

            @if($booking->status == 'pending')
            <div class="flex gap-2 mt-2">
                <!-- Кнопка оплаты -->
                <form action="{{ route('bookings.pay', $booking->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2 text-sm bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        Оплатить
                    </button>
                </form>
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-2 text-sm text-red-500 font-medium hover:bg-red-50 rounded-lg transition" onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                        Отменить бронирование
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endsection