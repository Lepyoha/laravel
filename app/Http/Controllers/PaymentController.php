<?php

namespace App\Http\Controllers;

use App\Enums\ServicePrice;
use App\Enums\UserStatus;
use App\Models\Tariff;
use App\Models\TariffUserHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BalanceChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function showDelayedPaymentButton()
    {
        $user = Auth::user();
        $canRequestDelayedPayment = $user->balance < 0 && $user->balance < -$user->credit && now()->day <= 10 && (!$user->last_delayed_payment || $user->last_delayed_payment->lt(now()->startOfMonth()));

        return view('payment.delayed_payment', compact('canRequestDelayedPayment'));
    }

    public function requestDelayedPayment(Request $request)
    {
        $user = Auth::user();

        if ($user->balance < 0 && $user->balance < -$user->credit && now()->day <= 10 && (!$user->last_delayed_payment || $user->last_delayed_payment->lt(now()->startOfMonth()))) {
            $user->sendCodeToTelegram();
            return response()->json(['message' => 'Код подтверждения отправлен в Telegram.']);
        }

        return response()->json(['message' => 'Отложенный платёж недоступен.'], 403);
    }

    public function requestChangeTariff(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        // Проверка на привязку аккаунта Telegram
        $telegramVerification = \App\Models\TelegramVerification::where('id_user', $user->id)->first();

        if (!$telegramVerification || $telegramVerification->telegram_id == null || $telegramVerification->verification == 0) {
            Log::info('Telegram account not linked', ['user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Привяжите свой Telegram аккаунт для смены тарифа.']);
        }else{
            $user->sendCodeToTelegram();
            return response()->json(['message' => 'Код подтверждения отправлен в Telegram.']);
        }
    }

    public function confirmDelayedPayment(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $user = Auth::user();

        if ($user->confirmation_code === $request->code) {
            $user->credit = -$user->balance;
            $user->last_delayed_payment = now();
            $user->confirmation_code = null;
            $user->status = UserStatus::Active;
            $user->save();


            BalanceChange::create([
                'user_id' => $user->id,
                'change_balance' => $user->balance,
                'change_credit' => $user->credit,
                'reason' => 'Отложенный платёж',
                'created_at' => now()
            ]);

            return response()->json(['message' => 'Отложенный платёж подтвержден.']);

        }

        return response()->json(['message' => 'Неверный код подтверждения.'], 400);
    }

    public function changeTariff(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'tariff_id' => 'required|exists:tariffs,id',
            'is_urgent' => 'required|boolean',
        ]);

        $user = Auth::user();
        $newTariff = Tariff::find($request->tariff_id);
        $currentTariff = $user->getLatestTariff()->tariff;


        if ($user->confirmation_code === $request->code) {

            $user->confirmation_code = null;
            $user->save();

            // Проверка на запланированную смену тарифа
            if ($user->hasPendingTariffChange()) {
                Log::warning('User has pending tariff change', ['user_id' => $user->id]);
                return response()->json(['success' => false, 'message' => 'У вас уже запланирована смена тарифа.']);
            }

            $servicePriceEnum = $request->is_urgent ? ServicePrice::URGENT_TARIFF_CHANGE : ServicePrice::CHANGE_TARIFF_NEXT_MONTH;
            $servicePrice = $servicePriceEnum->value;

            if ($request->is_urgent) {
                // Срочная смена тарифа
                $currentDate = now();
                $daysInMonth = $currentDate->daysInMonth;
                $remainingDays = $daysInMonth - $currentDate->day;

                $currentTariffDailyPrice = $currentTariff->price / $daysInMonth;
                $newTariffDailyPrice = $newTariff->price / $daysInMonth;

                $currentTariffRemainingCost = $currentTariffDailyPrice * $remainingDays;
                $newTariffRemainingCost = $newTariffDailyPrice * $remainingDays;

                $balanceDifference = $currentTariffRemainingCost - $newTariffRemainingCost;
                $totalCost = $servicePrice - $balanceDifference;

                if ($user->balance >= $totalCost) {
                    // Обновляем тариф и баланс пользователя
                    DB::transaction(function () use ($user, $newTariff, $currentTariff, $totalCost, $servicePrice) {
                        $user->balance -= $totalCost;
                        $user->save();

                        // Добавляем запись в тарифную историю
                        TariffUserHistory::create([
                            'user_id' => $user->id,
                            'tariff_id' => $newTariff->id,
                            'changed_at' => now()->addDay()->startOfDay(), // Смена тарифа со следующего дня
                        ]);

                        // Записываем изменение баланса
                        DB::table('balance_changes')->insert([
                            'user_id' => $user->id,
                            'change_balance' => $user->balance,
                            'change_credit' => $user->credit,
                            'reason' => 'Срочная смена тарифа с ' . $currentTariff->name . ' на ' . $newTariff->name,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    });

                    return response()->json(['success' => true, 'message' => 'Тариф успешно изменён.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Недостаточно средств на счету.']);
                }
            } else {
                // Смена тарифа с первого числа следующего месяца
                $user->balance -= $servicePrice;
                $user->save();

                // Добавляем запись в тарифную историю
                TariffUserHistory::create([
                    'user_id' => $user->id,
                    'tariff_id' => $newTariff->id,
                    'changed_at' => now()->startOfMonth()->addMonth()->startOfDay(),
                ]);

                // Записываем изменение баланса
                DB::table('balance_changes')->insert([
                    'user_id' => $user->id,
                    'change_balance' => $user->balance,
                    'change_credit' => $user->credit,
                    'reason' => 'Запрос на смену тарифа с ' . $currentTariff->name . ' на ' . $newTariff->name . ' с первого числа следующего месяца',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return response()->json(['success' => true, 'message' => 'Тариф успешно изменён.']);
            }
        }
        return response()->json(['message' => 'Неверный код подтверждения.'], 400);
    }

    public function requestFreezeAccount(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // Проверка на привязку аккаунта Telegram
        $telegramVerification = \App\Models\TelegramVerification::where('id_user', $user->id)->first();

        if (!$telegramVerification || $telegramVerification->telegram_id == null || $telegramVerification->verification == 0) {
            Log::info('Telegram account not linked', ['user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Привяжите свой Telegram аккаунт для смены тарифа.']);
        } else {
            $user->sendCodeToTelegram();
            return response()->json(['success' => true, 'message' => 'Код подтверждения отправлен в Telegram.']);
        }
    }
    public function confirmFreezeAccount(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'end_date' => 'required|date|after:today',
                'freeze_type' => 'required|string|in:urgent,next_month'
            ]);

            $user = Auth::user();
            $endDate = $request->end_date;
            $freezeType = $request->freeze_type;
            $freezeStart = now()->addDay()->startOfDay();

            if ($freezeType === 'next_month') {
                $freezeStart = (new \DateTime('first day of next month'))->setTime(0, 0);
            }

            if ($user->confirmation_code === $request->code) {
                $totalCost = 0;
                $startDateTime = new \DateTime($freezeStart);
                $endDateTime = new \DateTime($endDate);
                $interval = $startDateTime->diff($endDateTime);
                $months = $interval->m + ($interval->y * 12);

                // В любом случае, округляем количество месяцев до ближайших кратных 3
                $billingPeriods = ceil($months / 3);

                if ($freezeType === 'urgent') {
                    // Если тип заморозки срочный, учитываем стоимость срочной заморозки для первых трех месяцев и обычную стоимость для последующих
                    if ($billingPeriods <= 1) {
                        $totalCost = ServicePrice::URGENT_FREEZE->value;
                    } else {
                        $totalCost = ServicePrice::URGENT_FREEZE->value + ($billingPeriods - 1) * ServicePrice::FREEZE_NEXT_MONTH->value;
                    }
                } elseif ($freezeType === 'next_month') {
                    $totalCost = $billingPeriods * ServicePrice::FREEZE_NEXT_MONTH->value;
                }

                if ($user->balance >= $totalCost) {
                    DB::transaction(function () use ($user, $freezeStart, $endDate, $totalCost) {
                        $user->balance -= $totalCost;
                        $user->freeze_start = $freezeStart;
                        $user->freeze_end = $endDate;
                        $user->confirmation_code = null;
                        $user->save();

                        BalanceChange::create([
                            'user_id' => $user->id,
                            'change_balance' => $user->balance,
                            'change_credit' => $user->credit,
                            'reason' => 'Заморозка аккаунта с ' . $freezeStart . ' до ' . $endDate,
                            'created_at' => now()
                        ]);
                    });

                    return response()->json(['success' => true, 'message' => 'Аккаунт успешно заморожен.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Недостаточно средств на счету.']);
                }
            }

            return response()->json(['success' => false, 'message' => 'Неверный код подтверждения.'], 400);
        } catch (\Exception $e) {
            Log::error('Ошибка при подтверждении заморозки аккаунта: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Внутренняя ошибка сервера.'], 500);
        }
    }




    public function showFreezeAccountForm()
    {
        return view('freeze.account');
    }
}
