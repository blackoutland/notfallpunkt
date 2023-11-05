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

    public static function isLoggedIn()
    {
        if (empty($GLOBALS['UserManager'])) {
            return false;
        }
        return $GLOBALS['UserManager']->isLoggedIn();
    }

    public static function getLoggedInUser()
    {
        if (!self::isLoggedIn()) {
            return null;
        }
        return $GLOBALS['UserManager']->getLoggedInUserData();
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

    public static function getSetting($name)
    {
        $settings = self::getSettings();
        if (isset($settings[$name])) {
            return $settings[$name];
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getTemplateDefaultVars()
    {
        return [
            'sys'      => self::getSysData(),
            'config'   => $GLOBALS['config'],
            'settings' => self::getSettings(),
            'user'     => self::getLoggedInUser()
        ];
    }

    /**
     * @return string
     */
    public static function getRootDir()
    {
        return '/var/www';
    }

    public static function getHtdocsDir()
    {
        return self::getRootDir() . '/htdocs';
    }

    /**
     * @return Sqlite
     */
    public static function getDb()
    {
        return new Sqlite($GLOBALS['config']['db']);
    }

    public static function redirectTo($path, $code = 302)
    {
        header("HTTP/1.1 Moved");
        header("Location: $path");
        exit;
    }
}
