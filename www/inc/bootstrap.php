<?php

use Aura\Session\SessionFactory;
use BlackoutLand\NotfallPunkt\Model\UserManager;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once __DIR__ . '/../vendor/autoload.php';

// DEBUG
$config = json_decode(file_get_contents('/config.json'), true);
define("DEBUG", empty($config['debug']) ? false : ((bool)$config['debug']));
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set("display_errors", "On");
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}

// Redirect if not correct IP/hostname (captive portal)
$apHostName = getenv('AP_HOSTNAME');
if ($_SERVER["HTTP_HOST"] !== $apHostName) {
    header("HTTP/1.1 302 Found");
    if (!empty($_SERVER['HTTPS'])) {
        header("https://$apHostName/");
    } else {
        header("http://$apHostName/");
    }
    exit;
}

// Session
// TODO: Only do this is a logged in cookie exists
$GLOBALS['UserManager'] = new UserManager();
$GLOBALS['UserManager']->initializeSession();
