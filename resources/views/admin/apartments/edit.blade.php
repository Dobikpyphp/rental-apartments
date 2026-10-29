@extends('layouts.app')

@section('title', 'Редактирование данных квартиры')

@section('content')
<h1 class="mb-4">Редактирование данных квартиры</h1>

<div class="mb-8">
    <h3 class="text-lg font-bold mb-3">Галерея квартиры</h3>
    <div class="grid grid-cols-4 gap-4">
        @foreach($apartment->images as $image)
        <div class="relative group">
            <img src="{{ Storage::url($image->path) }}" class="w-full h-32 object-cover rounded-lg border">

            <!-- Кнопка удаления (появляется при наведении) -->
            <form action="{{ route('admin.apartments.image.destroy', $image->id) }}" method="POST"
                class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white rounded-full p-1 shadow-lg hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>


<form action="{{ route('admin.update', $apartment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="title"
            name="title"
            value="{{ $apartment->title }}"
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
            required>{{ $apartment->description }}</textarea>
        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="address" class="block text-sm font-medium text-gray-700">Адрес</label>
        <input type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="address"
            name="address"
            value="{{ $apartment->address }}"
            placeholder="Введите адрес"
            required>
        @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="price_per_night" class="block text-sm font-medium text-gray-700">Цена за ночь</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="price_per_night"
            name="price_per_night"
            value="{{ $apartment->price_per_night }}"
            placeholder="Введите цену"
            min="0"
            step="0.01"
            required>
        @error('price_per_night') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="rooms" class="block text-sm font-medium text-gray-700">Количество комнат</label>
        <input type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            id="rooms"
            name="rooms"
            value="{{ $apartment->rooms }}"
            placeholder="Введите количество комнат"
            min="1"
            required>
        @error('rooms') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex flex-wrap gap-4 mb-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="has_wifi" value="1" {{ $apartment->has_wifi ? 'checked' : '' }}> Wi-Fi
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="has_microwave" value="1" {{ $apartment->has_microwave ? 'checked' : '' }}> Микроволновка
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="has_tv" value="1" {{ $apartment->has_tv ? 'checked' : '' }}> Телевизор
        </label>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Добавить новые фото</label>
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
    </div>


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