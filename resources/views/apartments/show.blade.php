@extends('layouts.app')

@section('title', $apartment->title)

@section('content')
<!-- Кнопка назад -->
<div class="mb-6">
    <a href="{{ route('apartments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Назад в каталог
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- ЛЕВАЯ КОЛОНКА: Контент (2/3 ширины) -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Галерея фотографий -->
        <div class="grid grid-cols-4 grid-rows-2 gap-2 h-[400px] rounded-2xl overflow-hidden shadow-sm">
            {{-- Главное фото --}}
            <div class="col-span-2 row-span-2 relative">
                @if($apartment->images->first())
                <img src="{{ Storage::url($apartment->images->first()->path) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer">
                @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">Нет фото</div>
                @endif
            </div>

            {{-- Дополнительные фото --}}
            @foreach($apartment->images->slice(1) as $image)
            <div class="relative overflow-hidden">
                <img src="{{ Storage::url($image->path) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500 cursor-pointer">
            </div>
            @endforeach
        </div>

        <!-- Инфо-блок -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-2">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $apartment->title }}</h1>
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-lg text-sm font-bold">
                        ⭐ {{ $apartment->averageRating() ?? 'Нет оценок' }}
                    </span>
                </div>

                <span class="bg-blue-50 text-blue-600 text-sm font-semibold px-3 py-1 rounded-full">
                    {{ $apartment->rooms }} комн.
                </span>
            </div>

            <div class="flex items-center text-gray-500 mb-6 pb-6 border-b border-gray-100">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414M13.414 12.414L12 11m1.414 0L10.586 11M12 11V1l11 11H12z"></path>
                </svg>
                <span class="text-lg">{{ $apartment->address }}</span>
            </div>

            <!-- Удобства (Теги) -->
            <div class="mb-8">
                <h3 class="text-lg font-bold mb-3 text-gray-800">Что есть в квартире:</h3>
                <div class="flex flex-wrap gap-2">
                    @if($apartment->has_wifi) <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm border border-gray-200">📶 Wi-Fi</span> @endif
                    @if($apartment->has_tv) <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm border border-gray-200">📺 Телевизор</span> @endif
                    @if($apartment->has_microwave) <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm border border-//gray-200">🍲 Микроволновка</span> @endif
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-lg font-bold mb-3 text-gray-800">Описание</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $apartment->description }}
                </p>
            </div>
        </div>
        <!-- СЕКЦИЯ ОТЗЫВОВ -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mt-8">
            <h3 class="text-2xl font-bold mb-6 text-gray-900">Отзывы гостей</h3>

            <!-- Список отзывов -->
            @foreach($apartment->reviews as $review)
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 relative group">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-bold text-gray-800">{{ $review->user->name }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-yellow-500 font-bold">⭐ {{ $review->rating }}</span>

                        <!-- КНОПКА УДАЛЕНИЯ (только для админа) -->
                        @if(Auth::user() && Auth::user()->isAdmin())
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-xs opacity-0 group-hover:opacity-100 transition" onclick="return confirm('Удалить этот отзыв?')">
                                Удалить
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
            </div>
            @endforeach
            <!-- Форма добавления отзыва -->
            <div class="pt-6 border-t border-gray-100">
                <h4 class="font-bold mb-4 text-gray-800">Оставить отзыв</h4>
                <form action="{{ route('reviews.store', $apartment->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ваша оценка:</label>
                        <div class="flex items-center gap-2" id="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button"
                                data-value="{{ $i }}"
                                class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition-colors focus:outline-none"
                                onclick="setRating({{ $i }})">
                                ★
                                </button>
                                @endfor
                        </div>
                        <!-- Скрытое поле, которое реально отправится в контроллер -->
                        <input type="hidden" name="rating" id="rating-value" required>
                    </div>

                    <div>
                        <textarea name="comment" rows="3" class="w-full border rounded-xl p-3 text-sm focus:ring-indigo-500" placeholder="Расскажите о вашем пребывании..."></textarea>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Отправить отзыв
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ПРАВАЯ КОЛОНКА: Форма бронирования (STICKY) -->
    <div class="lg:col-span-1">
        <div class="sticky top-8 bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
            <div class="text-center mb-6">
                <span class="text-gray-500 text-sm">Цена за сутки</span>
                <div class="text-3xl font-black text-green-600">{{ number_format($apartment->price_per_night, 0, '.', ' ') }} ₽</div>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата заезда</label>
                    <input type="text" id="check_in" name="check_in" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата выезда</label>
                    <input type="text" id="check_out" name="check_out" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border" required>
                </div>

                <div id="price-display" data-price="{{ $apartment->price_per_night }}" data-blocked="{{ json_encode($blockedDates) }}" class="p-4 bg-blue-50 rounded-xl text-center hidden">
                    <span class="text-sm text-blue-600 block">Итого к оплате:</span>
                    <span id="total-amount" class="text-2xl font-bold text-blue-800">0</span> <span class="text-blue-800 font-bold">₽</span>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg active:scale-95">
                    Забронировать сейчас
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-4">
                Мгновенное подтверждение заявки
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Получаем данные из PHP (переводим массив PHP в массив JS)
        const blockedDates = JSON.parse(document.getElementById('price-display').dataset.blocked);
        const pricePerNight = document.getElementById('price-display').dataset.price;


        // 2. Настройка календаря заезда
        const checkInPicker = flatpickr("#check_in", {
            minDate: "today",
            dateFormat: "Y-m-d",
            // Блокируем даты
            // Здесь мы создаем массив всех дат из интервалов
            disable: blockedDates.flatMap(range => {
                let start = new Date(range.start);
                let end = new Date(range.end);
                let dates = [];
                for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                    dates.push(new Date(d));
                }
                return dates;
            }),
            onChange: function() {
                calculatePrice();
            }
        });

        // 3. Настройка календаря выезда
        const checkOutPicker = flatpickr("#check_out", {
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: blockedDates.flatMap(range => {
                let start = new Date(range.start);
                let end = new Date(range.end);
                let dates = [];
                for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                    dates.push(new Date(d));
                }
                return dates;
            }),
            onChange: function() {
                calculatePrice();
            }
        });

        // 4. Функция расчета цены
        function calculatePrice() {
            const start = checkInPicker.selectedDates[0];
            const end = checkOutPicker.selectedDates[0];

            if (start && end) {
                if (end <= start) {
                    alert("Дата выезда должна быть позже даты заезда!");
                    checkOutPicker.clear();
                    document.getElementById('price-display').classList.add('hidden');
                    return;
                }

                // Считаем разницу в миллисекундах, переводим в дни
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                const total = diffDays * pricePerNight;

                document.getElementById('total-amount').innerText = total.toLocaleString();
                document.getElementById('price-display').classList.remove('hidden');
            }
        }
    });

    function setRating(value) {
        // 1. Записываем значение в скрытое поле
        document.getElementById('rating-value').value = value;

        // 2. Подсвечиваем звезды
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
</script>
@endpush