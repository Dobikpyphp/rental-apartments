@extends('layouts.app')

@section('title', 'Досье гостя')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Досье: {{ $guest->full_name }}</h1>
    <a href="{{ route('admin.guests.index') }}" class="text-blue-600 hover:underline">← К списку гостей</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- ЛЕВАЯ КОЛОНКА: Информация и Редактирование -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Личные данные</h3>
            <p class="text-gray-600">Телефон: {{ $guest->phone }}</p>
            <p class="text-gray-600">Email: {{ $guest->email ?? 'Не указан' }}</p>
        </div>

        <div class="bg-white p-6 rounded-y-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Редактировать профиль</h3>

            <form action="{{ route('admin.guests.update', $guest->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Заметки о госте</label>
                    <textarea name="notes" class="mt-1 block w-full rounded-md border-gray-300 p-2 border">{{ $guest->notes }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Рейтинг (1-5)</label>
                    <input type="number" name="rating" value="{{ $guest->rating }}" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 p-2 border">
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <input type="checkbox" name="is_blacklisted" value="1" {{ $guest->is_blacklisted ? 'checked' : '' }}>
                    <label class="text-sm font-medium text-red-600">Внести в черный список</label>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Обновить досье</button>
            </form>

        </div>
    </div>

    <!-- ПРАВАЯ КОЛОНКА: История бронирований -->
    <div class="md:col-span-2">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold mb-4">История проживаний</h3>
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">+

                    <tr>
                        <th class="p-2 border-b">Квартира</th>
                        <th class="p-2 border-b">Даты</th>
                        <th class="p-2 border-b">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guest->bookings as $booking)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $booking->apartment->title }}</td>
                        <td class="p-2 text-sm">{{ $booking->check_in }} -> {{ $booking->check_out }}</td>
                        <td class="p-2">{{ $booking->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection