<?php

use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [ApartmentController::class, 'index']);
Route::get('/apartments/{id}', [ApartmentController::class, 'show'])->name('apartments.show');

// Все, что внутри этой группы, защищено Middleware 'is_admin'
Route::middleware(['is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index'); // Страница управления всеми квартирами
    Route::post('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update'); // Маршрут для сохранения обновленных данных квартиры
});
