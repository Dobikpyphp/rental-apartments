<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $apartments = Apartment::all();
        return view('admin.index', compact('apartments'));
    }

    public function edit(int $id)
    {
        $apartment = Apartment::findOrFail($id);
        return view('admin.apartments.edit', compact('apartment'));
    }

    public function update(Request $request, int $id)
    {
        $apartment = Apartment::findOrFail($id);

        $validated = $request->validate([
            'title'           => 'required|max:255',
            'description'    => 'required',
            'address'        => 'required',
            'price_per_night' => 'required|numeric|min:0',
            'rooms'           => 'required|integer|min:1',
            'is_available'    => 'boolean',
            'has_wifi'        => 'boolean',
            'has_microwave'   => 'boolean',
            'has_tv'          => 'boolean',
            'ical_url'        => 'nullable|url',
        ]);

        // Важный момент с чекбоксами: если чекбокс не отмечен, браузер его НЕ присылает.
        // Поэтому мы принудительно устанавливаем false для всех удобств, если их нет в запросе.
        $validated['is_available'] = $request->has('is_available');
        $validated['has_wifi'] = $request->has('has_wifi');
        $validated['has_microwave'] = $request->has('has_microwave');
        $validated['has_tv'] = $request->has('has_tv');

        $apartment->update($validated);

        // --- ЛОГИКА ДОБАВЛЕНИЯ НОВЫХ ФОТО ---
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('apartments', 'public');
                Image::create([
                    'path' => $path,
                    'apartment_id' => $apartment->id,
                ]);
            }
        }

        return redirect()->route('admin.index')->with('success', 'Данные квартиры и фото обновлены!');
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate(
            [
                'title'           => 'required|max:255',
                'description'    => 'required',
                'address'        => 'required',
                'price_per_night' => 'required|numeric|min:0',
                'rooms'           => 'required|integer|min:1',
                'is_available'    => 'boolean',
            ]
        );

        $apartment = Apartment::create($validated);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Сохраняем файл на диск
                $path = $image->store('apartments', 'public');
                // Сохраняем путь в базу
                Image::create([
                    'path' => $path,
                    'apartment_id' => $apartment->id,
                ]);
            }
        }
        return redirect()->route('admin.index')->with('success', 'Данные сохранены успешно!');
    }

    public function destroy(int $id)
    {
        Apartment::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Данные успешно удалены!');
    }

    public function bookings()
    {
        // Используем with(), чтобы Laravel одним запросом достал и бронь, и данные квартиры, и данные пользователя.
        // Если так не сделать, будет проблема N+1 (запрос в базу на каждую строку таблицы), и сайт будет тормозить.
        $bookings = Booking::with(['apartment', 'user'])->orderBy('created_at', 'desc')->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);

        // Обновляем статус из того, что пришло из формы
        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Статус бронирования обновлен!');
    }

    public function destroyBooking(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'Бронирование успешно удалено!');
    }

    public function deleteImage(int $id)
    {
        $image = Image::findOrFail($id);

        // 1. Удаляем физический файл с диска
        Storage::disk('public')->delete($image->path);

        // 2/ Удаляем запись из базы данных
        $image->delete();

        return back()->with('success', 'Фотография удалена!');
    }
}

// $path = $image->store('apartments', 'public');

// Произошло следующее:

// Laravel взял файл из временного хранилища.
// Придумал ему уникальное имя (например, aB123...jpg), чтобы два разных пользователя не перезаписали фото друг друга, если они назовут их одинаково photo.jpg.
// Сохранил этот файл в папку storage/app/public/apartments.
// Вернул тебе строку с путем к этому файлу (например, "apartments/aB123...jpg").
// Именно эту строку ты затем сохраняешь в базу данных через Image::create(['path' => $path, ...]).

// 'apartments' (Первый аргумент): Это название папки. Laravel создаст её внутри твоего хранилища. В итоге файл попадет в storage/app/public/apartments/.
// 'public' (Второй аргумент): Это название «диска». В Laravel есть разные диски: local (приватный), public (доступный из интернета), s3 (облако Amazon). Мы указали public, чтобы картинки были доступны клиентам через браузер.