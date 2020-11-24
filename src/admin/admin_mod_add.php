<?php

use drflvirtual\src\api\GameAPIConnection;
use drflvirtual\src\view\Route;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Load your variables.
$name = (isset($_POST["name"])                  ? $_POST["name"] : null);
$description = (isset($_POST["description"])    ? $_POST["description"] : null);
$event_id = (isset($_POST["event_id"])          ? intval($_POST["event_id"]) : null);
$author_id = (isset($_POST["author_id"])        ? intval($_POST["author_id"]) : null);

$redirect = isset($_POST["redirect"]) ? $_POST["redirect"] : "admin_mod.php";

// Check your variables.

if (!$name || !$description || !$event_id || !$author_id) {
    // TODO: Should we redirect back with an error?
    //header("Location: " . SITE_HOST . "/$redirect?message=".urlencode("Missing data."));
    echo "MISSING DATA";
    exit();
}

// Act.

$response = GameAPIConnection::sendModCreateRequest($event_id, $name, $description, $author_id);

var_dump($response);

if ($response->isSuccess()) {
    // Send them back to the mod list.
    header("Location: " . SITE_HOST . "/$redirect?message=".urlencode($message));
    exit();
} else {
    $message = $response->getMessage();

    switch ($response->getCode()) {
        case 401:
            // They're not authorized. Send them back to where they were.
            header("Location: " . SITE_HOST . "/$redirect?message=".urlencode($message));
        default:
            $message .= " Unknown error?";
            break;
    }

    // TODO: Special error handling?
    header("Location: " . SITE_HOST . "/$redirect?message=".urlencode($message));
    exit();
}




