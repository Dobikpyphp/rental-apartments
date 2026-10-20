<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;

class AdminController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all();
        return view('admin.index', compact('apartments'));
    }

    public function update(Request $request, $id) //Request $request — это объект, который содержит все данные, пришедшие из формы.
    {
        $apartment = Apartment::findOrFail($id);

        // Обновляем данные из формы
        $apartment->update([
            'price_per_night' => $request->price_per_night,
            'is_available' => $request->has('is_available'), // Проверяем, отмечен ли чекбокс
        ]);

        return redirect()->route('admin.index')->with('success', 'Данные обновлены!');
    }
}
