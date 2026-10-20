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
        'is_available'
    ];
}
