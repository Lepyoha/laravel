<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhoneNumber;

class PhoneNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Генерация случайных номеров телефонов
        $phoneNumbers = [
            '+7949888888',
            '+7949777777',
            '+7949999999',
        ];

        foreach ($phoneNumbers as $phoneNumber) {
            // Создание записи номера телефона в таблице
            PhoneNumber::create([
                'number' => $phoneNumber,
            ]);
        }
    }
}
