<?php

use BlackoutLand\NotfallPunkt\Model\Utils;

require_once __DIR__ . '/../inc/bootstrap.php';

$settings = Utils::getSettings();

header("Content-Type: application/json");
echo json_encode(
    [
        'success'      => true,
        'isTestMode'   => (bool)$settings['is_test_mode'],
        'user'         => [
            'isLoggedIn' => false,
            'userName'   => null
        ],
        "updates"      => [
            // TODO: Whenever someone updates news etc. send message
        ],
        "updateCounts" => [
            "news"      => 0,
            "board"     => 0,
            "files"     => 0,
            "knowledge" => 0,
            "users"     => 0
        ]
    ]
);
