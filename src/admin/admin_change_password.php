<?php

use drflvirtual\src\admin\Authentication;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

var_dump($_POST);

// Get the player_id and password.
$player_id = (isset($_POST["player_id"]) ? $db->escape($_POST["player_id"]) : false);
$old_password = (isset($_POST["old_password"])   ? $db->escape($_POST["old_password"])  : false);
$new_password = (isset($_POST["new_password"])   ? $db->escape($_POST["new_password"])  : false);

// Validate the old password.
if (!Authentication::isValidPassword($player_id, $old_password)) {
    // Invalid old password. Exit.

    exit("INVALID OLD PASSWORD");
}

// Validated old password. Update.
$updated = Authentication::setPassword($player_id, $new_password);

// Test new password.
if (Authentication::isValidPassword($player_id, $new_password)) {
    echo "/r/n<br/>Password set succeeded.";
} else {
    echo "/r/n<br/>Password set failed.";
}
