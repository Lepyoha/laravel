<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TariffsTableSeeder::class,
            UsersTableSeeder::class,
            TariffUserHistorySeeder::class,
            PhoneNumbersSeeder::class,
            PhoneUserSeeder::class,
            UserTypeSeeder::class,
        ]);
    }
}
