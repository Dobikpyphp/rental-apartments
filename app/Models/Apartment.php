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
}
