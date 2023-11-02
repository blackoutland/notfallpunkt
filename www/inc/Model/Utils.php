<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Utils
{
    public static function getSysData()
    {
        return [
            'phpVersion'     => PHP_VERSION,
            'serverSoftware' => $_SERVER['SERVER_SOFTWARE'],
            'remoteIp'       => $_SERVER['REMOTE_ADDR'],
            'debug'          => DEBUG,
            'system'         => json_decode(file_get_contents('/system.json'), true),
            'db'             => self::getDb()->getVersionNumber()
        ];
    }

    public static function getTemplateDefaultVars()
    {
        $ss = new Settings();
        return [
            'sys'      => self::getSysData(),
            'config'   => $GLOBALS['config'],
            'settings' => $ss->getAll()
        ];
    }

    public static function getRootDir()
    {
        return '/var/www';
    }

    public static function getDb()
    {
        return new Sqlite($GLOBALS['config']['db']);
    }
}
