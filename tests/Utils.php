<?php

namespace ToDoApp\Tests;

class Utils
{
    public static function generateRandomString(
        int $length = 12,
        bool $containSpecialCharacters = true,
        bool $containsLowercase = true,
        bool $containsUppercase = true,
        bool $containNumeric = true
    ) {
        $divider = 0;
        if ($containNumeric) {
            ++$divider;
        }
        if ($containSpecialCharacters) {
            ++$divider;
        }
        if ($containsLowercase) {
            ++$divider;
        }
        if ($containsUppercase) {
            ++$divider;
        }
        if (0 == $divider) {
            return '';
        }

        $sublength = ceil($length / $divider);

        $random = '';
        if ($containNumeric) {
            $random .= substr(str_shuffle(str_repeat($x = '0123456789', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containsLowercase) {
            $random .= substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containsUppercase) {
            $random .= substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containSpecialCharacters) {
            $random .= substr(str_shuffle(str_repeat($x = '!@#$%^&*()?/\\[]{}|;:,.<>`~|\'"-_+=', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        return $random;
    }
}
