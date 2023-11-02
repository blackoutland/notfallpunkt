<?php

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
