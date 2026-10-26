@extends('layouts.app')

@section('title', 'Список гостей')

@section('content')

<x-nav-link :href="route('admin.guests.create')" :active="request()->routeIs('admin.create')">
    {{ __('Добавить гостя') }}
</x-nav-link>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Все гости</h1>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="p-4 font-semibold text-gray-600">Фамилия</th>
                <th class="p-4 font-semibold text-gray-600">Телефон</th>
                <th class="p-4 font-semibold text-gray-600">Рейтинг</th>
                <th class="p-4 font-semibold text-gray-600">Статус</th>
                <th class="p-4 font-semibold text-gray-600">Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $guest)
            <tr class="border-b border-gray-100 {{ $guest->is_blacklisted ? 'bg-red-50' : '' }}">
                <td class="p-4">
                    <div class="font-medium">{{ $guest->full_name }}</div>
                </td>
                <td class="p-4 text-gray-700">{{ $guest->phone }}</td>
                <td class="p-4"> {{ $guest->rating }}</td>
                <td>
                    @if($guest->is_blacklisted)
                    <span class=" text-red-600 font-bold">🚫 В черном списке</span>
                    @else
                    <span class="text-green-600">✅ Надежный</span>
                    @endif
                </td>
                <td class="p-4">
                    <a href="{{ route('admin.guests.show', $guest->id) }}" class="text-blue-600 hover:underline">Открыть досье</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection