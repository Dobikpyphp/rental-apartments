<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'apartment_id', 'rating', 'comment'];

    // Отзыв может быть привязан к одному пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отзыв может быть привязан к одной квартире
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
