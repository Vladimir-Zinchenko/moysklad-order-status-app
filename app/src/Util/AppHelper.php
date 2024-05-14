<?php

namespace App\Util;

/**
 * Class AppHelper
 */
class AppHelper
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function path(string $path): string
    {
        return Config::get('root_path') . '/' . trim($path, '/');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function templatePath(string $path): string
    {
        return self::path('templates/' . trim($path, '/'));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function url(string $path): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . trim($path, '/');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function assetsUrl(string $path): string
    {
        return self::url('assets/' . trim($path, '/'));
    }

    /**
     * @return bool
     */
    public static function isDebug(): bool
    {
        return (bool) Config::get('debug', false);
    }
}
