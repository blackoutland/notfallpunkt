<?php

use BlackoutLand\NotfallPunkt\Model\ChatManager;
use BlackoutLand\NotfallPunkt\Model\Utils;

require_once __DIR__ . '/../inc/bootstrap.php';

// TODO: Check if user is logged in!

$loginUserData = $GLOBALS['UserManager']->getLoggedInUserData();


if (!Utils::isLoggedIn()) {
    header("HTTP/1.1 403 Forbidden");
    header("Content-Type: application/json");
    echo json_encode(
        [
            "success" => false,
            "id"      => $_GET['id']
        ],
        JSON_PRETTY_PRINT);
    exit;
}

$expiration       = 3600; // TODO: make configurable!
$cm               = new ChatManager();
$maxMessageLength = 256; // TODO: Make configurable and use for inputs as well!
$cm->addMessage(Utils::getLoggedInUser()['login'], $_GET['id'], $_GET['msg'], $expiration);

die("OK");

