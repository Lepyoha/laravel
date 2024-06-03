<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tariff;
use App\Models\User;

class TariffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tariff::create(['city_type' => 0,'name' => 'Классический', 'price' => 200.00, 'speed' => 20, 'channels'=> 300]);
        Tariff::create(['city_type' => 0,'name' => 'Стильный', 'price' => 350.00, 'speed' => 60, 'channels'=> 300]);
        Tariff::create(['city_type' => 0,'name' => 'Прорыв', 'price' => 500.00, 'speed' => 100, 'channels'=> 300]);
        Tariff::create(['city_type' => 1,'name' => 'Классический', 'price' => 250.00, 'speed' => 25, 'channels'=> 300]);
        Tariff::create(['city_type' => 2,'name' => 'Классический', 'price' => 300.00, 'speed' => 25, 'channels'=> 300]);
    }
}
