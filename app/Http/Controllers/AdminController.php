<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;

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