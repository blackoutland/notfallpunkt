<?php

use BlackoutLand\NotfallPunkt\Model\GeneralInfos;
use BlackoutLand\NotfallPunkt\Model\OwnerNews;
use BlackoutLand\NotfallPunkt\Model\Renderer;
use BlackoutLand\NotfallPunkt\Pages\News;

require_once __DIR__ . '/../inc/bootstrap.php';

// All sub-urls that are not found directly will be redirected here
$path = empty($_GET['_page']) ? null : $_GET['_page'];
if (!$path) {
    $path = '/';
} else {
    // Strip trailing slash
    $path = preg_replace("/\/$/", "", $path);
}

// Route request
$action = null;
$function = 'render';
switch ($path) {
    case '/news':
        $page = 'News';
        break;
    case '/board':
        $page = 'Board';
        break;
    case '/files':
        $page = 'Files';
        break;
    case '/knowledge':
        $page = 'Knowledge';
        break;
    case '/login':
        $page   = 'User';
        $action = 'login';
        break;
    case '/signup':
        $page   = 'User';
        $action = 'signup';
        break;
    case '/logout':
        $page = 'User';
        $function = 'logout';
        break;
    case '/users':
        $page = 'User';
        $action = 'list';
        break;
    case '/':
    case '/home':
        $page = 'Home';
        break;
    default:
        $page = 'Home';
        $action = 'notfound';
        break;
}

$className = "BlackoutLand\NotfallPunkt\Pages\\" . $page;
$obj       = new $className;
$obj->$function($action);

$GLOBALS['currentPage'] = $obj->getPageIndicator();


