<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Доступны всем)
|--------------------------------------------------------------------------
*/

// Главная страница - наш каталог квартир
Route::get('/', [ApartmentController::class, 'index'])->name('apartments.index');

// Страница детального просмотра одной квартиры
Route::get('/apartments/{id}', [ApartmentController::class, 'show'])->name('apartments.show');


/*
|--------------------------------------------------------------------------
| Protected Routes (Только для авторизованных пользователей)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Личный кабинет Breeze
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Управление профилем пользователя
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* 
       ПАНЕЛЬ АДМИНИСТРАТОРА 
       Здесь мы управляем квартирами
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::post('/update/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('destroy');

        // Список всех бронирований
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');

        // Обновление статуса бронирования (подтвердить/отменить)
        Route::patch('/bookings/{id}', [AdminController::class, 'updateBookingStatus'])->name('bookings.update');
    });

    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');

    // Раздел управления гостями
    Route::prefix('admin/guests')->name('admin.guests.')->group(function () {
        Route::get('/', [GuestController::class, 'index'])->name('index');
        Route::get('/create', [GuestController::class, 'create'])->name('create');
        Route::post('/store', [GuestController::class, 'store'])->name('store');
        Route::get('/{guest}', [GuestController::class, 'show'])->name('show');
        Route::patch('/{guest}', [GuestController::class, 'update'])->name('update');
    });
});

// Подключаем маршруты авторизации (login, register, logout)
require __DIR__ . '/auth.php';
