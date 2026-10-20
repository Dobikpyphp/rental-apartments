@extends('layouts.app')

@section('title', $apartment->title)

@section('content')
<div class="detail-card">
    <a href="/" style="text-decoration: none; color: #666;">← Вернуться в каталог</a>

    <h1>{{ $apartment->title }}</h1>
    <p><b>Адрес:</b> {{ $apartment->address }}</p>
    <p><b>Количество комнат:</b> {{ $apartment->rooms }}</p>

    <div class="price">{{ $apartment->price_per_night }} руб / сутки</div>

    <div style="margin: 20px 0;">
        <h3>Описание:</h3>
        <p>{{ $apartment->description }}</p>
    </div>

    <p>
        {{ $apartment->is_available ? '✅ Квартира доступна для бронирования' : '❌ Извините, квартира сейчас занята' }}
    </p>

    @if($apartment->is_available)
    <a href="#" class="btn-book">Забронировать сейчас</a>
    @endif
</div>
@endsection