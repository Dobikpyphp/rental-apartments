<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Кто оставил отзыв
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // О какой квартире отзыв
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');

            $table->integer('rating')->unsigned(); // Оценка от 1 до 5, unsigned() запрещает хранить в этой колонке отрицательные числа
            $table->text('comment');             // Текст отзыва
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
