<?php

use drflvirtual\src\admin\Authentication;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

global /** @var Authentication $auth */ $auth;

//var_dump($_POST);

// TES
//Authentication::setPassword(20779, "insecure");

// Get the player_id and password.
$player_id = (isset($_POST["player_id"]) ? $db->escape($_POST["player_id"]) : false);
$password = (isset($_POST["password"])   ? $db->escape($_POST["password"])  : false);

if (!$player_id || !$password) {
    exit("Invalid player id or password.");
}

echo "<br/>Logging in...";

$logged_in = $auth->login($player_id, $password);

if ($logged_in) {
    echo "<br/>Logged in!";
    $auth->redirectHome("Logged in.");
} else {
    echo "<br/>Failed to log in.";
    $auth->redirectToLogin("Failed to log in.");
}

echo "<br/>" . ($logged_in ? "Logged in!" : "Failed to log in.");

//echo "<br/>";

//var_dump($_SESSION);
