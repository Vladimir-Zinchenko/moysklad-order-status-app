<?php

namespace App\Util;

/**
 * Class Log
 */
class Log
{
    protected const LEVEL_DEBUG = 'debug';
    protected const LEVEL_ERROR = 'error';

    /**
     * @param string $message
     *
     * @return void
     */
    public static function debug(string $message): void
    {
        if (!Config::get('debug', false)) {
            return;
        }

        self::write(self::LEVEL_DEBUG, $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public static function error(string $message): void
    {
        self::write(self::LEVEL_ERROR, $message);
    }

    /**
     * @param string $level
     * @param string $message
     *
     * @return void
     */
    protected static function write(string $level, string $message): void
    {
        $filename = sprintf('%s-%s.log', date('Ymd'), $level);
        $logfile = AppHelper::path('logs/' . $filename);
        $message = date('H:i:s.u') . "\n" . $message;

        if (file_exists($logfile)) {
            $message = "\n\n" . str_repeat('=', 40) . "\n\n" . $message;
        }

        file_put_contents($logfile, $message, FILE_APPEND);
    }
}
