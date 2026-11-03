@extends('layouts.app')

@section('title', 'Список гостей')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">База гостей</h1>
    <a href="{{ route('admin.guests.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        + Добавить гостя
    </a>
</div>

<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="p-4 font-semibold text-gray-600">ФИО</th>
                <th class="p-4 font-semibold text-gray-60/ed">Телефон</th>
                <th class="p-4 font-semibold text-gray-600">Рейтинг</th>
                <th class="p-4 font-semibold text-gray-600">Статус</th>
                <th class="p-4 font-semibold text-gray-600">Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $guest)
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $guest->is_blacklisted ? 'bg-red-50' : '' }}">
                <td class="p-4 font-medium text-gray-900">{{ $guest->full_name }}</td>
                <td class="p-4 text-gray-600">{{ $guest->phone }}</td>
                <td class="p-4 text-yellow-500 font-bold">{{ $guest->rating ?? '—' }} ⭐</td>
                <td class="p-4">
                    @if($guest->is_blacklisted)
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">🚫 Черный список</span>
                    @else
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">✅ Надежный</span>
                    @endif
                </td>
                <td class="p-4">
                    <a href="{{ route('admin.guests.show', $guest->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Открыть досье →
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection