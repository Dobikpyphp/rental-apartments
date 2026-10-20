<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds. Наполнение данными 
     */
    public function run(): void
    {
        Apartment::create([
            'title' => 'Уютная студия в центре',
            'description' => 'Светлая студия с новым ремонтом, есть всё для комфортного проживания. 5 минут до метро.',
            'address' => 'ул. Ленина, д. 10, кв. 45',
            'price_per_night' => 3000,
            'rooms' => 1,
            'is_available' => true,
        ]);

        Apartment::create([
            'title' => 'Просторная двушка для семьи',
            'description' => 'Две раздельные комнаты, большая кухня, детский уголок. Рядом парк.',
            'address' => 'пр. Мира, д. 5, кв. 12',
            'price_per_night' => 5000,
            'rooms' => 2,
            'is_available' => true,
        ]);

        Apartment::create([
            'title' => 'Люкс с видом на город',
            'description' => 'Премиальная квартира на 15 этаже, панорамные окна, джакузи.',
            'address' => 'ул. Советская, д. 22, кв. 101',
            'price_per_night' => 8000,
            'rooms' => 2,
            'is_available' => true,
        ]);

        Apartment::create([
            'title' => 'Компактная однушка у вокзала',
            'description' => 'Идеальный вариант для коротких поездок. Чисто, тихо, удобно.',
            'address' => 'ул. Железнодорожная, д. 3, кв. 8',
            'price_per_night' => 2000,
            'rooms' => 1,
            'is_available' => true,
        ]);

        Apartment::create([
            'title' => 'Семейный трехкомнатный апартамент',
            'description' => 'Огромная квартира для большой компании. Три спальни, большая гостиная.',
            'address' => 'ул. Цветочная, д. 14, кв. 2',
            'price_per_night' => 7000,
            'rooms' => 3,
            'is_available' => true,
        ]);
    }
}
