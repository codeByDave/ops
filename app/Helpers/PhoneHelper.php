<?php

namespace App\Helpers;

class PhoneHelper
{
    public static function normalize(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        return $digits ? substr($digits, 0, 10) : null;
    }

    public static function format(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) !== 10) {
            return $phone;
        }

        return '(' . substr($digits, 0, 3) . ') '
            . substr($digits, 3, 3)
            . '-'
            . substr($digits, 6, 4);
    }
}