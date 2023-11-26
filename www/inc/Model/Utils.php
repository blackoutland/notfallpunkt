<?php

namespace BlackoutLand\NotfallPunkt\Model;

use CodeInc\HumanReadableFileSize\HumanReadableFileSize;

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
     * @var array|false
     */
    private static $allMemcacheKeys;

    /**
     * @var array
     */
    private static $fileInfo = [];

    /**
     * @return array
     */
    public static function getSysData()
    {
        if (!self::$sysData) {
            $memcacheVersion = null;
            try {
                if (!self::$memcache) {
                    self::$memcache = new MemcacheConnection();
                }
                $memcacheVersion = self::$memcache->getVersion();
            } catch (\Exception $e) {
                // Ignore silently
            }

            $envVars       = getenv();
            self::$sysData = [
                'phpVersion'      => PHP_VERSION,
                'serverSoftware'  => $_SERVER['SERVER_SOFTWARE'],
                'memcacheVersion' => $memcacheVersion,
                'remoteIp'        => $_SERVER['REMOTE_ADDR'],
                'debug'           => DEBUG,
                'system'          => json_decode(file_get_contents('/system.json'), true),
                'dbVersion'       => self::getDb()->getVersionNumber(),
                'accessPoint'     => [
                    'interface'       => empty($envVars['INTERFACE']) ? null : $envVars['INTERFACE'],
                    'hostname'        => empty($envVars['AP_HOSTNAME']) ? null : $envVars['AP_HOSTNAME'],
                    'subnet'          => empty($envVars['SUBNET']) ? null : $envVars['SUBNET'],
                    'subnetMask'      => empty($envVars['SUBNET_MASK']) ? null : $envVars['SUBNET_MASK'],
                    'apAddress'       => empty($envVars['AP_ADDR']) ? null : $envVars['AP_ADDR'],
                    'primaryDns'      => empty($envVars['PRI_DNS']) ? null : $envVars['PRI_DNS'],
                    'dhcpIpStart'     => empty($envVars['DHCP_IP_START']) ? null : $envVars['DHCP_IP_START'],
                    'dhcpIpEnd'       => empty($envVars['DHCP_IP_END']) ? null : $envVars['DHCP_IP_END'],
                    'dhcpIpNetmask'   => empty($envVars['DHCP_IP_NETMASK']) ? null : $envVars['DHCP_IP_NETMASK'],
                    'wifiSsid'        => empty($envVars['SSID']) ? null : $envVars['SSID'],
                    'wifiChannel'     => empty($envVars['CHANNEL']) ? null : $envVars['CHANNEL'],
                    'wifiCountryCode' => empty($envVars['COUNTRY_CODE']) ? null : $envVars['COUNTRY_CODE'],
                    'wifiHwMode'      => empty($envVars['HW_MODE']) ? null : $envVars['HW_MODE'],
                ]
            ];
        }

        return self::$sysData;
    }

    public static function getApIp()
    {
        return empty($envVars['AP_ADDR']) ? null : $envVars['AP_ADDR'];
    }

    // TODO: move to new factory
    public static function getMemcacheConnection()
    {
        if (!self::$memcache) {
            self::$memcache = new MemcacheConnection();
        }
        return self::$memcache;
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

    public static function getAllMemcacheKeys()
    {
        if (!self::$memcache) {
            self::$memcache = new MemcacheConnection();
        }
        if (!self::$allMemcacheKeys) {
            self::$allMemcacheKeys = self::$memcache->getAll();
        }
        return self::$allMemcacheKeys;
    }

    public static function getLoggedInUsers()
    {
        $onlineUsersNames = [];
        foreach (self::getAllMemcacheKeys() as $key) {
            if (preg_match("/^usr\.(.*)\.online$/", $key, $m)) {
                $onlineUsersNames[] = $m[1];
            }
        }
        return $onlineUsersNames;
    }

    public static function isLoggedIn()
    {
        return $GLOBALS['UserManager']->isLoggedIn();
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

    public static function getFileInfo()
    {
        // TODO: Read from memcache to ease CPU (filesize reading, JSON parsing etc.)

        if (!self::$fileInfo) {
            self::$fileInfo = [
                'community' => self::getFileInfos("/fileshare/community"),
                'kiwix'     => self::getFileInfos("/fileshare/kiwix"),
                'apk'       => self::getFileInfos("/fileshare/public/files/apk"),
                'osm'       => self::getFileInfos("/fileshare/public/files/osm"),
                'knowledge' => self::getFileInfos("/fileshare/public/knowledge"),
            ];
        }

        return self::$fileInfo;
    }

    private static function getFileInfos($path)
    {
        $files    = glob("$path/**");
        $fileInfo = file_exists("$path/.files.json") ? json_decode(file_get_contents("$path/.files.json"), JSON_PRETTY_PRINT) : [];

        $localizations = self::getLocalizations();
        $readableSize  = new HumanReadableFileSize();
        $readableSize->useNumberFormatter($localizations['locale']);
        $readableSize->setSpaceBeforeUnit();
        $readableSize->setByteSymbol('B');
        // echo $readableSize->compute(filesize('a-file.pdf'), 1);

        $data = [];
        foreach ($files as $file) {
            foreach ($fileInfo as $item) {
                if (basename($item['file']) === basename($file)) {

                    $title = null;
                    if (!empty($item['data'])) {
                        if (!empty($item['data']['title'])) {
                            $title = $item['data']['title'];
                        }
                    } else {
                        $title = $item['title'];
                    }

                    $date = null;
                    if (!empty($item['data'])) {
                        $date = $item['data']['date'];
                    } else {
                        $date = isset($item['date']) ?: null;
                    }

                    $category = null;
                    if (!empty($item['category'])) {
                        $category = $item['category'];
                    }

                    $ts = null;
                    if ($date) {
                        try {
                            $dt = new \DateTime($date);
                            $ts = (int)$dt->format('U');
                        } catch (\Exception $e) {
                            // ignore
                        }
                    }

                    $size   = filesize($file);
                    $data[] = [
                        'path'      => $file,
                        'file'      => basename($file),
                        'size'      => $size,
                        'sizeHuman' => $size ? $readableSize->compute($size, 1) : null,
                        'title'     => $title,
                        'ts'        => $ts,
                        'data'      => isset($item['data']) ? $item['data'] : null,
                        'category'  => $category
                    ];
                }
            }
        }
        return $data;
    }
}
