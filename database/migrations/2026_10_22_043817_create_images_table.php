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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // Путь к файлу
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade'); // Связь с квартирой
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};

// foreignId('apartment_id')  создает колонку unsignedBigInteger с названием apartment_id.
// constrained()  говорит Laravel: «Посмотри на название колонки (apartment_id), найди таблицу с похожим названием (apartments) и свяжи эту колонку с её ID».
// onDelete('cascade')  устанавливает правило: удаляем квартиру $\rightarrow$ удаляются все её фото.
