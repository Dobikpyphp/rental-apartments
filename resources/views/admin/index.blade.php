@extends('layouts.app')

@section('title', 'Панель управления')

@section('content')
<h1>Управление квартирами</h1>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
    {{ session('success') }}
</div>
@endif

<table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background: #333; color: white; text-align: left;">
            <th style="padding: 15px;">Квартира</th>
            <th style="padding: 15px;">Цена за сутки</th>
            <th style="padding: 15px;">Статус</th>
            <th style="padding: 15px;">Действие</th>
            <th style="padding: 15px;">Удалить</th>
        </tr>
    </thead>
    <tbody>
        @foreach($apartments as $apartment)
        <tr style="border-bottom: 1px solid #ddd;">
            <td style="padding: 15px;">{{ $apartment->title }}</td>
            <td style="padding: 15px;">
                {{-- Создаем маленькую форму для каждой квартиры --}}
                <form action="{{ route('admin.update', $apartment->id) }}" method="POST" style="display: inline-flex; gap: 10px;">
                    @csrf {{-- Обязательный токен безопасности Laravel для всех форм --}}
                    <input type="number" name="price_per_night" value="{{ $apartment->price_per_night }}" style="width: 80px;">

                    <label style="font-size: 0.9em;">
                        <input type="checkbox" name="is_available" {{ $apartment->is_available ? 'checked' : '' }}> Свободна
                    </label>

                    <button type="submit" style="background: #27ae60; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">💾</button>
                </form>
            </td>
            <td style="padding: 15px;">
                {{ $apartment->is_available ? '✅' : '❌' }}
            </td>
            <td style="padding: 15px;">
                <a href="{{ route('apartments.show', $apartment->id) }}" target="_blank">👁️ Посмотреть</a>
            </td>
            {{-- ДОБАВЛЯЕМ кнопку удаления --}}
            <td class="p-4">
                <form action=" {{ route('admin.destroy', $apartment->id) }}" method="POST" onsubmit="return confirm('Вы точно хотите удалить эту квартиру?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection