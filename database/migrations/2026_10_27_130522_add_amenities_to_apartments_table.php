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
        Schema::table('apartments', function (Blueprint $table) {
            // Добавляем удобства с дефолтным значением false (выключено)
            $table->boolean('has_wifi')->default(false);
            $table->boolean('has_microwave')->default(false);
            $table->boolean('has_tv')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn(['has_wifi', 'has_microwave', 'has_tv']);
        });
    }
};
