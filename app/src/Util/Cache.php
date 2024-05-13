<?php

namespace App\Util;

class Cache
{
    private static array $cache = [];

    private static ?Cache $instance = null;

    private function __construct() {}

    private function __clone() {}

    /**
     * @return Cache
     */
    public static function getInstance(): Cache
    {
        if (is_null(Cache::$instance)) {
            self::$instance = new Cache;
        }

        return Cache::$instance;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset(self::$cache[$key]);
    }

    /**
     * @param string $key
     * @param        $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return self::$cache[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return $this
     */
    public function set(string $key, $value): Cache
    {
        self::$cache[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return Cache
     */
    public function unset(string $key): Cache
    {
        if ($this->has($key)) {
            unset(self::$cache[$key]);
        }

        return $this;
    }
}
