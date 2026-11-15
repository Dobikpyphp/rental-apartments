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
            // ID объекта в системе RealtyCalendar (уникальный для каждой квартиры)
            $table->string('realty_calendar_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropUnique(['realty_calendar_id']); // сначала удаляем индекс
            $table->dropColumn('realty_calendar_id');   // потом колонку
        });
    }
};
