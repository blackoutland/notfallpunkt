<?php

use BlackoutLand\NotfallPunkt\Model\UserManager;

require_once __DIR__ . '/../inc/bootstrap.php';

$um = new UserManager();
$um->updatePassword(1, "EnterNewPasswordHere");
