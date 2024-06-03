<?php

namespace App\Helpers;

class TokenHelper
{
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Проверка токена.
     *
     * @param string $token Токен для проверки
     * @param string $expectedToken Ожидаемый токен
     * @return bool Результат проверки
     */
    public static function verifyToken($token, $expectedToken)
    {
        return hash_equals($expectedToken, $token);
    }
}
