<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::orderBy('full_name', 'asc')->get();
        return view('admin.guests.index', compact('guests'));
    }

    public function create()
    {
        return view('admin.guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|unique:guests,phone', // Проверяем, чтобы телефон не повторялся
            'email'     => 'nullable|email',
            'notes'   => 'nullable|string', // Напоминаю: проверь, чтобы тут не было слэшей!
            'rating'    => 'nullable|integer|min:1|max:5',
            'is_blacklisted' => 'boolean',
        ]);

        Guest::create($validated);

        return redirect()->route('admin.guests.index')->with('success', 'Гость добавлен в базу!');
    }

    public function show(Guest $guest) //Метод show: Здесь мы используем крутую фишку Laravel — Route Model Binding. Если в маршруте написано {guest}, а в методе контроллера мы укажем тип Guest $guest, Laravel сам найдет гостя в базе по ID.
    {
        // Загружаем гостя вместе с его бронированиями, чтобы не делать лишних запросов к БД
        $guest->load('bookings.apartment');

        return view('admin.guests.show', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_blacklisted' => 'boolean',
        ]);

        $guest->update($validated);

        return redirect()->back()->with('success', 'Досье гостя обновлено!');
    }
}
