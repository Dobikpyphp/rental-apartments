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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

// nullable() - Разрешаем пустое значение (для тех, кто бронирует без регистрации).
// constrained() - Создаем жесткую связь с таблицей users. Теперь в эту колонку попадет только существующий ID пользователя.
// onDelete('set null') - Это очень важный момент! Если пользователь решит удалить свой аккаунт из системы, мы не хотим удалять данные о бронировании (нам же нужно знать, что квартира была занята и сколько денег пришло!). Поэтому мы говорим: «Если пользователь удален, просто поставь в поле user_id значение NULL, но саму запись о бронировании оставь».
