<?php

namespace App\Util\Moysklad;

class MoyskladHelper
{
    public const ERR_AUTH = 1056;

    public static function codesFromErrors(array $errors): array
    {
        return array_map(function (array $error) {
            return $error['code'];
        }, $errors);
    }
}
