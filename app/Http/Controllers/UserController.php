<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): \Illuminate\Contracts\Support\Renderable
    {
        $user = Auth::user();
        $latestTariff = $user->latestTariffRelation();
        $currentDate = Carbon::now();
        $canRequestDelayedPayment = false;


        // Проверка условий для отображения кнопки "Отложенный платёж"
        if ($user->balance < -10 && $currentDate->day <= 10) {
            // Проверка, был ли уже взят отложенный платёж в текущем месяце
            $lastDelayedPayment = $user->balanceChanges()
                ->where('reason', 'Отложенный платёж')
                ->whereMonth('created_at', $currentDate->month)
                ->first();

            if (!$lastDelayedPayment) {
                $canRequestDelayedPayment = true;
            }
        }

        // Преобразуем last_delayed_payment в объект Carbon
        $lastDelayedPayment = $user->last_delayed_payment ? Carbon::parse($user->last_delayed_payment) : null;

        // Вычисление даты истечения кредита
        $creditExpiryDate = $lastDelayedPayment ? $lastDelayedPayment->copy()->addDays(5) : null;

        return view('user.home', compact('user', 'latestTariff', 'canRequestDelayedPayment', 'creditExpiryDate'));
    }

    public function showTariffs(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();
        $tariffs = $user->tariffs;

        return view('user.tariffs', compact('tariffs'));
    }

    public function showChangeTariffPage()
    {
        $user = Auth::user();
        $tariffs = Tariff::where('city_type', $user->city)->whereNotIn('id', [$user->getLatestTariff()->tariff->id])->get();

        \Log::info($tariffs);

        return view('user.change-tariff-page', compact('tariffs'));
    }

    public function requestFreezeAccount(Request $request)
    {
        $user = Auth::user();
        $endDate = Carbon::parse($request->input('end_date'));
        $now = Carbon::now();

        if ($endDate <= $now) {
            return response()->json(['success' => false, 'message' => 'Дата окончания должна быть в будущем.']);
        }

        // Отправка кода подтверждения в Telegram
        $telegramVerification = $user->telegramVerification;
        if ($telegramVerification && $telegramVerification->verification) {
            $telegramId = $telegramVerification->telegram_id;
            $code = mt_rand(100000, 999999);
            $user->freeze_confirmation_code = $code;
            $user->save();

            $response = Http::withOptions(['verify' => false])
                ->post('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
                    'chat_id' => $telegramId,
                    'text' => 'Ваш код подтверждения для заморозки аккаунта: ' . $code
                ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Код подтверждения отправлен в Telegram.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Не удалось отправить код подтверждения.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Telegram не подтверждён.']);
    }

    public function confirmFreezeAccount(Request $request)
    {
        $user = Auth::user();
        $code = $request->input('code');

        if ($user->freeze_confirmation_code == $code) {
            $endDate = Carbon::parse($request->input('end_date'));
            $user->frozen_until = $endDate;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Аккаунт успешно заморожен.']);
        }

        return response()->json(['success' => false, 'message' => 'Неверный код подтверждения.']);
    }

}
