<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    /**
     * Атрибуты, которые НЕЛЬЗЯ массово присваивать.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    // protected $quarted = false; //не надо защищать
    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'title',
    //     'content',
    //     'image',
    //     'likes',
    //     // добавьте сюда все поля, которые вы хотите заполнять через create/update
    // ];
}
