<?php

namespace App\Http\Controllers;

use App\Models\BalanceChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BalanceController extends Controller
{
    /**
     * Обновление баланса пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBalance(Request $request)
    {
        $user = Auth::user();
        $amount = $request->input('amount');
        $reason = $request->input('reason');

        // Обновление баланса пользователя
        $user->balance += $amount;
        $user->save();

        // Запись изменения баланса
        BalanceChange::create([
            'user_id' => $user->id,
            'change_balance' => $amount,
            'change_credit' => $user->credit,
            'reason' => $reason,
        ]);

        return response()->json(['success' => true]);
    }
}
