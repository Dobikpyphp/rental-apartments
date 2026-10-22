<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    // Указываем, какие поля можно заполнять массово
    protected $fillable = [
        'apartment_id',
        'path',
    ];
    public function apartment()
    {
        // belongsTo означает «принадлежит к»
        return $this->belongsTo(Apartment::class);
    }
}
