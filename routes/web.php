<?php

use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApartmentController::class, 'index']);
