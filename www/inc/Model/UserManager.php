<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Josantonius\Session\Facades\Session;
use Nette\Security\Passwords;

class UserManager
{
    /** @var Sqlite */
    private $db;

    private $loggedIn = null;

    private $userData = [];

    public function __construct()
    {
        $this->db = Utils::getDb();
    }

    public function updatePassword($userId, $newPassword)
    {
        $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]); // TODO: Make configurable
        $hash      = $passwords->hash($newPassword);

        return $this->db->preparedQuery("UPDATE users SET pass = ? WHERE id = ?", [$hash, $userId]);
    }

    public function initializeSession()
    {
        Session::start(
            [
                // 'cache_expire' => 180,
                // 'cache_limiter' => 'nocache',
                // 'cookie_domain' => '',
                'cookie_httponly'  => true,
                'cookie_lifetime'  => $GLOBALS['config']['sessionLifetimeSeconds'],
                // 'cookie_path' => '/',
                'cookie_samesite'  => 'Strict',
                'cookie_secure'    => false,
                // 'gc_divisor' => 100,
                // 'gc_maxlifetime' => 1440,
                // 'gc_probability' => true,
                // 'lazy_write' => true,
                'name'             => 'nfp_sid',
                // 'read_and_close' => false,
                // 'referer_check' => '',
                // 'save_handler' => 'files',
                // 'save_path' => '',
                // 'serialize_handler' => 'php',
                // 'sid_bits_per_character' => 4,
                // 'sid_length' => 32,
                // 'trans_sid_hosts' => $_SERVER['HTTP_HOST'],
                // 'trans_sid_tags' => 'a=href,area=href,frame=src,form=',
                'use_cookies'      => true,
                'use_only_cookies' => true,
                // 'use_strict_mode' => false,
                // 'use_trans_sid' => false,
            ]
        );

        Session::set('last_request_ts', time());

        $userData = Session::get('User');
        $userId   = null;
        if ($userData) {
            $userId = $userData['id'];
        }

        if ($userId) {
            $this->loggedIn = true;
            $this->userData = $userData;
        }
    }

    public function setLoginUserData(array $data)
    {
        Session::set('User', $data);
    }

    public function getLoggedInUserData()
    {
        if ($this->loggedIn) {
            return $this->userData;
        }
        return null;
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function login($login, $password, $persistent = false)
    {
        $userData = $this->db->getOne("SELECT * FROM users WHERE login = ?", [$login]);
        if (!$userData) {
            // Not found
            sleep(rand(1, 5)); // DoS prevention
            return [false, 'notfound', null];
        }

        if ($userData['status'] !== 'ACTIVE' && $userData['status'] !== 'ADMIN') {
            // Disabled
            sleep(rand(1, 5)); // DoS prevention
            return [false, 'inactive', null];
        }

        $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]); // TODO: Make configurable
        if ($passwords->verify($password, $userData['pass'])) {
            // Update last login date
            // Wrong pass
            $this->db->preparedQuery("UPDATE users SET ts_last_login = ? WHERE id = ?", [time(), $userData['id']]);

            // Persistent?
            /*
            if ($persistent) {
                $this->session->setCookieParams(['lifetime' => Utils::getSetting('ext_login_persistence_seconds')]);
            }
            */

            Session::regenerateId(true);
            $this->setLoginUserData($userData);
            return [true, "ok", $userData];
        }

        sleep(rand(1, 5)); // DoS prevention
        return [false, 'wrongpass', null];
    }

    public function isOnline($login)
    {
        return Utils::memcacheGet('usr.' . $login . '.online');
    }

    public function logout()
    {
        Session::destroy();
        Utils::redirectTo('/');
    }

    public function getAll($onlyActive = true)
    {
        $statuses = ["'ADMIN'"];
        if ($onlyActive) {
            $statuses[] = "'ACTIVE'";
        }
        $statusString = implode(', ', $statuses);
        return $this->db->getAll("SELECT * FROM users WHERE status IN ($statusString)", []);
    }

    /**
     * @param bool $onlyActive
     * @return int
     */
    public function getUserCount($onlyActive = true)
    {
        $statuses = ["'ADMIN'"];
        if ($onlyActive) {
            $statuses[] = "'ACTIVE'";
        }
        $statusString = implode(', ', $statuses);
        return (int)$this->db->getOneField("SELECT COUNT(*) As count FROM users WHERE status IN ($statusString)", 'count', []);
    }
}
