@extends('layouts.app')

@section('title', 'Добавить квартиру')

@section('content')
<h1 class="mb-4">Добавить квартиру</h1>

<form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="title"
            name="title"
            placeholder="Введите название объекта"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
        <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="description"
            name="description"
            rows="4"
            placeholder="Введите описание объекта"
            required></textarea>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="address" class="block text-sm font-medium text-gray-700">Адрес</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="address"
            name="address"
            placeholder="Введите адрес"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="price_per_night" class="block text-sm font-medium text-gray-700">Цена за ночь</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="price_per_night"
            name="price_per_night"
            placeholder="Введите цену"
            min="0"
            step="0.01"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="rooms" class="block text-sm font-medium text-gray-700">Количество комнат</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="rooms"
            name="rooms"
            placeholder="Введите количество комнат"
            min="1"
            required>
        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <input type="file"
        class="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border 
           file:mr-4 file:py-2 file:px-4 
           file:rounded-full file:border-0 
           file:text-sm file:font-semibold 
           file:bg-blue-50 file:text-blue-700 
           hover:file:bg-blue-100"
        name="images[]"
        id="image"
        multiple>

    <!-- file:mr-4 - отступ справа от кнопки до текста «No file chosen».
    file:py-2 file:px-4 - внутренние отступы самой кнопки.
    file:rounded-full - делает кнопку овальной (очень современный вид).
    file:bg-blue-50 file:text-blue-700 - делает кнопку нежно-голубой с темно-синим текстом.
    hover:file:bg-blue-100 - кнопка станет чуть темнее при наведении. -->


    <div class="mb-4 flex items-center gap-2">
        <input class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
            type="checkbox"
            id="is_available"
            name="is_available"
            value="1">
        <label class="flex items-center gap-2" for="is_available">
            Доступно для бронирования
        </label>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Сохранить</button>
</form>


@endsection