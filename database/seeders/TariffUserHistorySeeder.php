<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tariff;
use App\Models\TariffUserHistory;
use Carbon\Carbon;

class TariffUserHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $tariffs = Tariff::all();

        foreach ($users as $user) {
            // Добавляем 2-3 записи в историю для каждого пользователя
            $numberOfRecords = rand(2, 3);

            for ($i = 0; $i < $numberOfRecords; $i++) {
                $tariff = $tariffs->where('city_type', $user->city)->random();
                TariffUserHistory::create([
                    'user_id' => $user->id,
                    'tariff_id' => $tariff->id,
                    'created_at' => Carbon::now()->subDays(rand(0, 100))->format('Y-m-d H:i:s'), // случайная дата в прошлом
                ]);
            }
        }
    }
}
