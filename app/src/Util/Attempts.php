<?php

namespace App\Util;

/**
 * Class Attempts
 */
class Attempts
{
    /**
     * @param callable $callback
     * @param int      $attempts
     * @param int      $timeout
     * @param int      $timeoutIncrement
     *
     * @return bool
     */
    public static function retry(callable $callback, int $attempts = 3, int $timeout = 30, int $timeoutIncrement = 0): bool
    {
        return true;
    }

    /**
     * @param string $key
     * @param int    $limit
     * @param int    $period
     *
     * @return bool
     */
    public static function limit(string $key, int $limit = 45, int $period = 3): bool
    {
        return false;
    }
}
