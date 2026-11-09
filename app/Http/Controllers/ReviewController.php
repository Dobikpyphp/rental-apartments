<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, int $apartmentId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        $userId = Auth::id();

        // !!! ГЛАВНАЯ ПРОВЕРКА: Был ли пользователь в этой квартире?
        // Ищем бронирование этого пользователя для этой квартиры со статусом 'completed' (завершено)
        // Если у вас пока нет статуса 'completed', можно использовать 'confirmed'
        $hasStayed = Booking::where('user_id', $userId)
            ->where('apartment_id', $apartmentId)
            ->whereIn('status', ['confirmed', 'completed']) // Разрешаем оставить отзыв и тем, кто сейчас живет, и тем, кто уже выехал
            ->exists();

        if (!$hasStayed) {
            return back()->with('error', 'Вы можете оставить отзыв только после того, как проживете в этой квартире!');
        }


        // 2. НОВАЯ ПРОВЕРКА: Не оставлял ли он отзыв ранее?
        $alreadyReviewed = Review::where('user_id', $userId)
            ->where('apartment_id', $apartmentId)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Вы уже оставили отзыв об этой квартире. Повторный отзыв невозможен!');
        }

        Review::create([
            'user_id' => $userId,
            'apartment_id' => $apartmentId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Спасибо за ваш отзыв!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Отзыв был удален.');
    }
}
