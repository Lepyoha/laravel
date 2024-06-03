<?php

namespace Database\Seeders;

use App\Enums\UserCity;
use App\Enums\UserStatus;
use Cassandra\Type\UserType;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tariff;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $tariff = Tariff::where('name', 'Классический')->first();

        User::create([
            'user_type_id' => 1,
            'username' => 'lepy0ha',
            'email' => 'lepy0ha@yandex.ru',
            'password' => bcrypt('1234qwer'),
            'status' => UserStatus::Active,
            'balance' => 1000,
            'full_name' => 'Колесников Алексей Евгеньевич',
            'birthday' => '2002-05-20',
            'INN' => '00000000',
            'passport_series' => '4444',
            'passport_number' => '666666',
            'notes' => '',
            'user_type' => '0',
            'city' => UserCity::Donetsk,
            'street' => 'Петровского',
            'house_number' => '109',
            'apartment_number' => '56',
            'floor' => '5',
        ]);

        User::create([
            'user_type_id' => 3,
            'username' => 'admin',
            'email' => 'admin@yandex.ru',
            'password' => bcrypt('admin'),
            'status' => UserStatus::Active,
            'balance' => 0,
            'full_name' => 'Колесников Алексей Евгеньевич',
            'birthday' => '2002-05-20',
            'INN' => '00000000',
            'passport_series' => '4444',
            'passport_number' => '666666',
            'notes' => '',
            'user_type' => '0',
            'city' => UserCity::Donetsk,
            'street' => 'Петровского',
            'house_number' => '109',
            'apartment_number' => '56',
            'floor' => '5',
        ]);

        User::create([
            'user_type_id' => 1,
            'username' => 'chel',
            'email' => 'chel@yandex.ru',
            'password' => bcrypt('chel'),
            'status' => UserStatus::Active,
            'balance' => -1000,
            'full_name' => 'Петров Пётр Петрович',
            'birthday' => '1999-10-01',
            'INN' => '111111111',
            'passport_series' => '1111',
            'passport_number' => '111111',
            'notes' => '',
            'user_type' => '0',
            'city' => UserCity::Mandrykino,
            'street' => 'Бринько',
            'house_number' => '11',
        ]);

    }
}
