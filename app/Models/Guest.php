<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;
    // Гость может иметь много бронирований
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Гость может быть привязан к одному пользователю (аккаунту)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
