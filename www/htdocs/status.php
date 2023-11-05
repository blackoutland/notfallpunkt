<?php

use BlackoutLand\NotfallPunkt\Model\Utils;

require_once __DIR__ . '/../inc/bootstrap.php';

$settings = Utils::getSettings();

header("Content-Type: application/json");

$loginUserData = $GLOBALS['UserManager']->getLoggedInUserData();

echo json_encode(
    [
        'success'      => true,
        'isTestMode'   => (bool)$settings['is_test_mode'],
        'user'         => [
            'isLoggedIn' => !!$loginUserData,
            'userName'   => $loginUserData ? $loginUserData['login'] : null
        ],
        "updates"      => [
            // TODO: Whenever someone updates news etc. send message
        ],
        "updateCounts" => [
            "home"      => 0,
            "news"      => 0,
            "board"     => 0,
            "files"     => 0,
            "knowledge" => 0,
            "users"     => 0
        ]
    ]
);
