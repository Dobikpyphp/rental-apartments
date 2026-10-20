<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment; // Не забываем про use!

class ApartmentController extends Controller
{
    public function index()
    {
        // 1. Получаем все квартиры из базы данных
        $apartments = Apartment::all();

        // 2. Возвращаем страницу 'apartments.index' и передаем туда данные
        return view('apartments.index', compact('apartments'));
    }
}
