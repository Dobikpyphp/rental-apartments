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

    public function update(Request $request, int $id) //Request $request — это объект, который содержит все данные, пришедшие из формы.
    {
        $apartment = Apartment::findOrFail($id);

        // Обновляем данные из формы
        $apartment->update([
            'price_per_night' => $request->price_per_night,
            'is_available' => $request->has('is_available'), // Проверяем, отмечен ли чекбокс
        ]);

        return redirect()->route('admin.index')->with('success', 'Данные обновлены!');
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title'           => 'required|max:255',
                'description'    => 'required',
                'address'        => 'required',
                'price_per_//night' => 'required|numeric|min:0',
                'rooms'           => 'required|integer|min:1',
                'is_available'    => 'boolean',
            ]
        );

        Apartment::create($validated);
        return redirect()->route('admin.index')->with('success', 'Данные сохранены успешно!');
    }

    public function destroy(int $id)
    {
        Apartment::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Данные успешно удалены!');
    }
}
