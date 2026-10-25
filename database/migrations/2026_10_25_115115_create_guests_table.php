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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            // Делаем nullable, чтобы можно было добавить гостя без заметок и рейтинга
            $table->text('notes')->nullable();
            $table->integer('rating')->nullable();
            $table->boolean('is_blacklisted')->default(false);
            // Правильный порядок: nullable -> constrained -> set null
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); //при удалении пользователя профиль гостя останется, а в поле user_id просто запишется NULL.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
