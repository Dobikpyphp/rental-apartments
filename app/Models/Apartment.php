<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    // Указываем, какие поля можно заполнять массово
    protected $fillable = [
        'title',
        'description',
        'address',
        'price_per_night',
        'rooms',
        'is_available',
        'path',
        'has_wifi',
        'has_microwave',
        'has_tv',
        'ical_url',
    ];

    public function images()
    {
        // hasMany означает «имеет много»
        return $this->hasMany(Image::class);
    }

    public function bookings()
    {
        // hasMany означает «имеет много»
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Метод для получения среднего рейтинга
    public function averageRating()
    {
        return round($this->reviews()->avg('rating'), 1); //$this->reviews() (со скобками) $\rightarrow$ возвращает конструктор запроса (Query Builder). Это позволяет нам добавлять к запросу дополнительные команды (например, avg), прежде чем идти в базу.
    }
}

// avg('rating') (Агрегатная функция)
// Это метод Laravel, который переводит команду на язык SQL. Вместо того чтобы выкачивать тысячи отзывов на сервер, считать их сумму в PHP и делить на количество, Laravel говорит базе данных: «Считай всё сама!».

// В базу улетает SQL-запрос: SELECT AVG(rating) FROM reviews WHERE apartment_id = ...

// База данных (MySQL) очень быстро считает среднее арифметическое всех оценок и возвращает один единственный результат. Например: 4.3333333333333.
