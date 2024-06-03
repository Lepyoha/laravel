<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Models\BalanceChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetCredit extends Command
{
    protected $signature = 'credit:reset';
    protected $description = 'Сброс кредита пользователей через 5 дней';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();
//        $users = User::whereNotNull('last_delayed_payment')
//            ->where('last_delayed_payment', '<', Carbon::now()->subDays(5))
//            ->get();

        foreach ($users as $user) {
            $user->resetCredit();
            if ($user->balance < -$user->credit) {
                $user->status = UserStatus::Disabled;
                $user->save();
            }

            // Записываем изменение баланса в таблицу balance_changes
            BalanceChange::create([
                'user_id' => $user->id,
                'change_balance' => $user->balance,
                'change_credit' => $user->credit,
                'reason' => 'Анулирование кредита',
                'created_at' => now()
            ]);
        }

        $this->info('Кредиты сброшены.');
    }
}
