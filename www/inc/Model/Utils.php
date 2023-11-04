<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Utils
{
    /** @var array */
    private static $settings;

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public static function getSettings()
    {
        $ss = new Settings();
        if (!self::$settings) {
            self::$settings = $ss->getAll();
        }
        return self::$settings;
    }

    /**
     * @return array
     */
    public static function getTemplateDefaultVars()
    {
        return [
            'sys'      => self::getSysData(),
            'config'   => $GLOBALS['config'],
            'settings' => self::getSettings()
        ];
    }

    /**
     * @return string
     */
    public static function getRootDir()
    {
        return '/var/www';
    }

    public static function getHtdocsDir() {
        return self::getRootDir().'/htdocs';
    }

    /**
     * @return Sqlite
     */
    public static function getDb()
    {
        return new Sqlite($GLOBALS['config']['db']);
    }
}
