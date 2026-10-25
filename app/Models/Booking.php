<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // РАЗРЕШАЕМ заполнение всех полей, которые мы используем в методе store
    protected $fillable = [
        'apartment_id',
        'user_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Бронирование принадлежит одному конкретному гостю
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
