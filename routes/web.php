<?php

use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApartmentController::class, 'index']);
Route::get('/apartments/{id}', [ApartmentController::class, 'show'])->name('apartments.show');
