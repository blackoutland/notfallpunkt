<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Memcached;

class MemcacheConnection
{

    private $memcache;

    public function __construct()
    {
        $this->memcache = new Memcached();
        $this->memcache->addServer('blackoutland-notfallpunkt-memcached', 11211);
    }

    public function getVersion()
    {
        // We're only using one server anyway!
        $versions =  $this->memcache->getVersion();

        foreach($versions as $server => $version) {
            return $version;
        }
    }

    public function set($key, $value, $timeout = 0)
    {
        return $this->memcache->set($key, $value, $timeout);
    }

    public function get($key)
    {
        return $this->memcache->get($key);
    }

    public function getAll()
    {
        return $this->memcache->getAllKeys();
    }
}