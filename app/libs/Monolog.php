<?php

abstract class Monolog
{
    public static function log($type, $message)
    {
        error_log($type . ': ' . $message."\n", 3, APP_LOGS);
    }

    public static function info($message)
    {
        self::log('Info', $message);
    }

    public static function err($message)
    {
        self::log('Err', $message);
    }
}
