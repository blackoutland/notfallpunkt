<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Exception;
use Memcached;

class MemcacheConnection
{

    private $memcache;

    public function __construct()
    {
        $this->memcache = new Memcached();
        $this->memcache->addServer('blackoutland-notfallpunkt-memcached', 11211);
        $this->memcache->setOption(Memcached::OPT_BINARY_PROTOCOL, false);
    }

    public function getVersion()
    {
        // We're only using one server anyway!
        $versions = $this->memcache->getVersion();

        foreach ($versions as $server => $version) {
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

    public function getAllLegacy()
    {
        return $this->memcache->getAllKeys();
    }

    // @see https://www.php.net/manual/de/memcached.getallkeys.php#125238
    public function getAll(): array
    {
        $allKeys = [];
        try {
            $sock = fsockopen('blackoutland-notfallpunkt-memcached', 11211, $errno, $errstr);
            if ($sock === false) {
                throw new Exception("Error connection to server 'blackoutland-notfallpunkt-memcached' on port 11211: ({$errno}) {$errstr}");
            }

            if (fwrite($sock, "stats items\n") === false) {
                throw new Exception("Error writing to socket");
            }

            $slabCounts = [];
            while (($line = fgets($sock)) !== false) {
                $line = trim($line);
                if ($line === 'END') {
                    break;
                }

                // STAT items:8:number 3
                if (preg_match('!^STAT items:(\d+):number (\d+)$!', $line, $matches)) {
                    $slabCounts[$matches[1]] = (int)$matches[2];
                }
            }

            foreach ($slabCounts as $slabNr => $slabCount) {
                if (fwrite($sock, "lru_crawler metadump {$slabNr}\n") === false) {
                    throw new Exception('Error writing to socket');
                }

                $count = 0;
                while (($line = fgets($sock)) !== false) {
                    $line = trim($line);
                    if ($line === 'END') {
                        break;
                    }

                    // key=foobar exp=1596440293 la=1596439293 cas=8492 fetch=no cls=24 size=14908
                    if (preg_match('!^key=(\S+)!', $line, $matches)) {
                        $allKeys[] = urldecode($matches[1]);
                        $count++;
                    }
                }
            }
            if (fclose($sock) === false) {
                throw new Exception('Error closing socket');
            }

            return $allKeys;
        } catch (Exception $e) {
            return [];
        }
    }
}