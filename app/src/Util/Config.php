<?php

namespace App\Util;

use Exception;

class Config
{
    private static ?array $config = null;

    private function __construct() {}

    private function __clone() {}

    /**
     * @param array $config
     *
     * @return void
     *
     * @throws Exception
     */
    public static function init(array $config): void
    {
        if (!is_null(self::$config)) {
            throw new Exception('Config object already initialized');
        }

        self::$config = $config;
    }

    /**
     * @param string $key
     * @param        $default
     *
     * @return mixed|null
     */
    public static function get(string $key, $default = null)
    {
        return self::has($key) ? self::$config[$key] : $default;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset(self::$config[$key]);
    }
}
