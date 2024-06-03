<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'speed', 'price', 'chanel', 'city_type'];
    public function userHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TariffUserHistory::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'city', 'city_type');
    }

}
