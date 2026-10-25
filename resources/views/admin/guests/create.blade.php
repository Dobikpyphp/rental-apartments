@extends('layouts.app')

@section('title', 'Добавить гостя')

@section('content')
<h1 class="mb-4">Добавить гостя</h1>

<form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">ФИО</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="title"
            name="title"
            placeholder="Введите ФИО"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="address" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="address"
            name="address"
            placeholder="Введите email"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Заметки</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="title"
            name="title"
            placeholder="Запиши заметки"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4">
        <label for="rooms" class="block text-sm font-medium text-gray-700">Рейтинг</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="rooms"
            name="rooms"
            placeholder="Оцени по рейтингу"
            min="1"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

</form>
@endsection