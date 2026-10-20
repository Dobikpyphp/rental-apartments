<?php

use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [ApartmentController::class, 'index']);
Route::get('/apartments/{id}', [ApartmentController::class, 'show'])->name('apartments.show');

// Страница управления всеми квартирами
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Маршрут для сохранения обновленных данных квартиры
Route::post('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
