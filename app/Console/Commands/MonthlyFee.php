<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Tariff;
use App\Models\BalanceChange;
use App\Enums\UserStatus;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyFee extends Command
{
    protected $signature = 'monthly:fee';
    protected $description = 'Снятие ежемесячной платы у Абонентов';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Получаем тип пользователя "Абонент"
        $userType = UserType::where('name', 'Абонент')->first();

        if (!$userType) {
            $this->error('User type "Абонент" not found.');
            return;
        }

        // Получаем всех пользователей с типом "Абонент"
        $users = User::where('user_type_id', $userType->id)->get();

        foreach ($users as $user) {
            // Получаем последний тариф пользователя
            $latestTariffHistory = $user->latestTariffRelation();

            if ($latestTariffHistory) {
                $tariff = $latestTariffHistory->tariff;

                // Снимаем ежемесячную плату
                $user->balance -= $tariff->price;
                $user->save();

                // Записываем изменение баланса в таблицу balance_changes
                BalanceChange::create([
                    'user_id' => $user->id,
                    'change_balance' => $user->balance-$tariff->price,
                    'change_credit' => $user->credit,
                    'reason' => 'Ежемесячный съём средств',
                    'created_at' => now()
                ]);

                // Проверяем баланс
                if ($user->balance < -$user->credit) {
                    $user->status = UserStatus::Disabled;
                    $user->save();
                }

                $this->info("Абонент {$user->username} с тарифом {$tariff->price}. Новый баланс: {$user->balance}");
            } else {
                $this->warn("Абонент {$user->username} не имеет тарифа.");
            }
        }

        $this->info('Команда выполнена.');
    }
}
