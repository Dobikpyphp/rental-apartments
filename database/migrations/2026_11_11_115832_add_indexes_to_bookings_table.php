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
        Schema::table('bookings', function (Blueprint $table) {
            // Составной индекс для быстрой проверки пересечения дат
            $table->index(['apartment_id', 'check_in', 'check_out'], 'bookings_apartment_dates_index');

            // Отдельный индекс на статус (для фильтрации)
            $table->index('status', 'bookings_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_apartment_dates_index');
            $table->dropIndex('bookings_status_index');
        });
    }
};
