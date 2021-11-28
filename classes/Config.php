<?php

namespace PPW;

class Config
{
    public static function getGoogleApiKey(): string
    {
        return self::getConfig()['googleApiKey'];
    }

    public static function getMinMaxApiUrl()
    {
        return self::getConfig()['minMaxApiUrl'];
    }

    private static function getConfig()
    {
        return require __DIR__ . '/../config.php';
    }

}