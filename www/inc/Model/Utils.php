<?php

namespace BlackoutLand\NotfallPunkt\Model;

class Utils
{
    /** @var array */
    private static $localizations;

    /** @var array */
    private static $settings;

    /**
     * @var MemcacheConnection
     */
    private static $memcache;

    /** @var UserManager */
    private static $userManager;

    /** @var array */
    private static $sysData;

    /**
     * @return array
     */
    public static function getSysData()
    {
        if (!self::$sysData) {
            $memcacheVersion = null;
            try {
                $mc              = new MemcacheConnection();
                $memcacheVersion = $mc->getVersion();
            } catch (\Exception $e) {
                // Ignore silently
            }

            self::$sysData = [
                'phpVersion'      => PHP_VERSION,
                'serverSoftware'  => $_SERVER['SERVER_SOFTWARE'],
                'memcacheVersion' => $memcacheVersion,
                'remoteIp'        => $_SERVER['REMOTE_ADDR'],
                'debug'           => DEBUG,
                'system'          => json_decode(file_get_contents('/system.json'), true),
                'dbVersion'       => self::getDb()->getVersionNumber()
            ];
        }

        return self::$sysData;
    }

    public static function memcacheSet($key, $value, $expiration = 0)
    {
        if (!self::$memcache) {
            self::$memcache = new MemcacheConnection();
        }
        self::$memcache->set($key, $value, $expiration);
    }

    public static function memcacheGet($key)
    {
        if (!self::$memcache) {
            self::$memcache = new MemcacheConnection();
        }
        return self::$memcache->get($key);
    }

    public static function isLoggedIn()
    {
        if (!self::$userManager) {
            self::$userManager = new UserManager();
        }

        return self::$userManager->isLoggedIn();
    }

    public static function getLoggedInUser()
    {

        if (!self::isLoggedIn()) {
            return null;
        }
        return $GLOBALS['UserManager']->getLoggedInUserData();
    }

    public static function isOnline($login)
    {
        if (!self::$userManager) {
            self::$userManager = new UserManager();
        }
        return self::$userManager->isOnline($login);
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

    public static function getLocalizations()
    {
        $lm = new LocalizationManager();
        if (!self::$localizations) {
            self::$localizations = $lm->getAll();
        }
        return self::$localizations;
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
            'sys'           => self::getSysData(),
            'config'        => $GLOBALS['config'],
            'settings'      => self::getSettings(),
            'localizations' => self::getLocalizations(),
            'user'          => self::getLoggedInUser()
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
