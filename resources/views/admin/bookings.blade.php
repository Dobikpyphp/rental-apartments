@extends('layouts.app')

@section('title', 'Список бронирований')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Все бронирования</h1>
    <a href="{{ route('admin.index') }}" class="text-blue-600 hover:underline">← К квартирам</a>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="p-4 font-semibold text-gray-600">Гость</th>
                <th class="p-4 font-semibold text-gray-600">Квартира</th>
                <th class="p-4 font-semibold text-gray-600">Даты</th>
                <th class="p-4 font-semibold text-gray-600">Сумма</th>
                <th class="p-4 font-semibold text-gray-600">Статус</th>
                <th class="p-4 font-semibold text-//gray-600">Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="p-4">
                    <div class="font-medium">{{ $booking->user->name ?? 'Не зарегистрирован' }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->user->email ?? 'Нет email' }}</div>
                </td>
                <td class="p-4 text-gray-700">{{ $booking->apartment->title }}</td>
                <td class="p-4 text-sm text-gray-600">
                    {{ $booking->check_in }} -> {{ $booking->check_out }}
                </td>
                <td class="p-4 font-bold text-gray-800">{{ $booking->total_price }} руб.</td>
                <td class="p-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 
                                   ($booking->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $booking->status }}
                    </span>
                </td>
                <td class="p-4">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="text-xs border rounded p-1">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Подтвердить</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Отменить</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">OK</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection