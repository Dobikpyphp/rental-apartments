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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id(); // Уникальный ID квартиры
            $table->string('title'); // Название
            $table->text('description'); // Описание
            $table->string('address'); // Адрес
            $table->decimal('price_per_night', 10, 2); // Цена (10 цифр всего, 2 после запятой)
            $table->integer('rooms'); // Кол-во комнат
            $table->boolean('is_available')->default(true); // Доступна или нет
            $table->timestamps(); // Дата создания и обновления записи
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
