<?php

use BlackoutLand\NotfallPunkt\Model\Utils;

require_once __DIR__ . '/../inc/bootstrap.php';

$settings = Utils::getSettings();

header("Content-Type: application/json");
echo json_encode(
    [
        'success'    => true,
        'isTestMode' => (bool)$settings['is_test_mode']
    ]
);