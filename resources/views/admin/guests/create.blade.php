@extends('layouts.app')

@section('title', 'Добавить гостя')

@section('content')
<h1 class="mb-4">Добавить гостя</h1>

<form action="{{ route('admin.guests.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="full_name" class="block text-sm font-medium text-gray-700">ФИО</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="full_name"
            name="full_name"
            placeholder="Введите ФИО"
            required>
        @error('full_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="phone"
            name="phone"
            placeholder="+7 (999) 000-00-00"
            required>
        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="email"
            name="email"
            placeholder="Введите email">
        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="notes" class="block text-sm font-medium text-gray-7/00">Заметки</label>
        <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="notes"
            name="notes"
            rows="4"
            placeholder="Запиши заметки (например: тихий алкаш)"></textarea>
        @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="rating" class="block text-sm font-medium text-gray-700">Рейтинг (1-5)</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="rating"
            name="rating"
            placeholder="Оцени по рейтингу"
            min="1"
            max="5">
        @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Сохранить гостя</button>
</form>
@endsection