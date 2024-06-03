<?php

namespace App\Http\Controllers;

use App\Models\TelegramVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function addTelegramID(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            if ($request->has('telegram_id')) {
                $telegramId = $request->input('telegram_id');

                // Создание или обновление записи в таблице telegram_verifications
                $verification = TelegramVerification::updateOrCreate(
                    ['id_user' => $user->id],
                    ['telegram_id' => $telegramId, 'verification' => false]
                );

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function unlinkTelegramID(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Удаление записи из таблицы telegram_verifications
            TelegramVerification::where('id_user', $user->id)->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    // Метод для отправки сообщения в Telegram
    public function sendTelegramMessage(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $telegramVerification = TelegramVerification::where('id_user', $user->id)->first();

            if ($telegramVerification && $telegramVerification->verification) {
                $telegramId = $telegramVerification->telegram_id;
                $message = $request->input('message');

                $response = Http::withOptions(['verify' => false])
                    ->post('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
                        'chat_id' => $telegramId,
                        'text' => $message
                    ]);

                if ($response->successful()) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'error' => 'Failed to send message']);
                }
            }
        }

        return response()->json(['success' => false, 'error' => 'User not authenticated or telegram not verified']);
    }
}
