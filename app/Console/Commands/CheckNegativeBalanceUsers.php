<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Console\Command;

class CheckNegativeBalanceUsers extends Command
{
    protected $signature = 'check:negative-balance-users';
    protected $description = 'Check users with negative balance and update their status if necessary';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Получаем всех абонентов с отрицательным балансом
        $users = User::where('balance', '<', 0)->get();

        // Обновляем статус абонентов на отключенный
        foreach ($users as $user) {
            if($user->balance < $user->credit){
                $user->status = UserStatus::Disabled;
                $user->save();
            }
        }

        $this->info('User statuses updated successfully.');
    }
}
