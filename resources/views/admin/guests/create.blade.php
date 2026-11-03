@extends('layouts.app')

@section('title', 'Добавить нового гостя')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.guests.index') }}" class="text-gray-400 hover:text-gray-600">← Назад</a>
        <h1 class="text-2xl font-bold text-gray-800">Добавление гостя</h1>
    </div>

    <form action="{{ route('admin.guests.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Полное имя (ФИО)</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
            @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Телефон</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-//sm font-medium text-gray-700">Email (необязательно)</label>
            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Рейтинг (1-5)</label>
            <input type="number" name="rating" value="{{ old('rating', 5) }}" min="1" max="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
            @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Заметки</label>
            <textarea name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">{{ old('notes') }}</textarea>
            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_blacklisted" value="1" id="blacklist">
            <label for="blacklist" class="text-sm font-medium text-red-600">Добавить сразу в черный список</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md">
            Сохранить гостя в базу
        </button>
    </form>
</div>
@endsection