<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Models\BalanceChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleUserFreezingStatus extends Command
{
    protected $signature = 'user:handle-freezing-status';
    protected $description = 'Handle user freezing status and adjust balances accordingly';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            $today = Carbon::today();
            $users = User::where('status', UserStatus::Frozen)
                ->orWhere('freeze_start', $today)
                ->orWhere('freeze_end', $today)
                ->get();

            foreach ($users as $user) {
                // Обработка начала заморозки
                if ($user->freeze_start->isSameDay($today)) {
                    $user->status = UserStatus::Frozen;
                    $this->adjustBalanceForFreezeStart($user);
                }

                // Обработка окончания заморозки
                if ($user->freeze_end->isSameDay($today)) {
                    $user->status = UserStatus::Active;
                    $this->adjustBalanceForFreezeEnd($user);
                }

                $user->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при обработке статуса заморозки пользователей: ' . $e->getMessage());
        }
    }

    protected function adjustBalanceForFreezeStart(User $user)
    {
        $monthlyRate = 200; // Пример месячной ставки
        $daysInMonth = $user->freeze_start->daysInMonth;
        $daysToRefund = $daysInMonth - $user->freeze_start->day + 1;
        $refundAmount = ($monthlyRate / $daysInMonth) * $daysToRefund;

        $user->balance += $refundAmount;

        BalanceChange::create([
            'user_id' => $user->id,
            'change_balance' => $user->balance,
            'change_credit' => $user->credit,
            'reason' => 'Возврат средств за оставшиеся дни текущего месяца при заморозке',
            'created_at' => now()
        ]);
    }

    protected function adjustBalanceForFreezeEnd(User $user)
    {
        $monthlyRate = 200; // Пример месячной ставки
        $daysInMonth = $user->freeze_end->daysInMonth;
        $daysToCharge = $daysInMonth - $user->freeze_end->day + 1;
        $chargeAmount = ($monthlyRate / $daysInMonth) * $daysToCharge;

        $user->balance -= $chargeAmount;

        BalanceChange::create([
            'user_id' => $user->id,
            'change_balance' => $user->balance,
            'change_credit' => $user->credit,
            'reason' => 'Снятие средств за оставшиеся дни текущего месяца при разморозке',
            'created_at' => now()
        ]);
    }
}
