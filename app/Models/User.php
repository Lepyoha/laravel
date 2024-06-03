<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\TelegramController;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tariffs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tariff::class, 'city_type', 'city');
    }

    public function tariffHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TariffUserHistory::class);
    }

    public function phoneNumbers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PhoneNumber::class);
    }

    public function userType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    public function sendCodeToTelegram()
    {
        $code = rand(100000, 999999);
        $this->confirmation_code = $code;
        $this->save();

        $telegramVerification = TelegramVerification::where('id_user', $this->id)->first();
        if ($telegramVerification && $telegramVerification->verification) {
            $telegramId = $telegramVerification->telegram_id;
            $message = "Код подтверждения: " . $code;

            Http::post('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
                'chat_id' => $telegramId,
                'text' => $message
            ]);
        }
    }

    public function updateCredit($amount): void
    {
        $this->credit = abs($amount);
        $this->save();
    }

    public function resetCredit(): void
    {
        $this->credit = 10;
        $this->save();
    }

    public function balanceChanges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BalanceChange::class, 'user_id', 'id');
    }

    public function hasPendingTariffChange(): bool
    {
        // Проверяем наличие будущих записей в истории смены тарифов
        return $this->tariffUserHistory()
            ->where('changed_at', '>', now())
            ->exists();
    }

    public function latestTariffRelation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TariffUserHistory::class)
           ->where('changed_at', '<=', now())
            ->latest('changed_at');
    }

    public function getLatestTariff()
    {
        return $this->latestTariffRelation()->first();
    }


    private function tariffUserHistory(): HasMany
    {
        return $this->hasMany(TariffUserHistory::class);
    }

}
