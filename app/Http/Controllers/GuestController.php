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
}
