<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

$action = (isset($_POST["action"]) ? $db->escape($_POST["action"])          : null);
$name = (isset($_POST["name"])     ? $db->escape($_POST["name"])    : null);
$strain_id = (isset($_POST["strain_id"])     ? $db->escape($_POST["strain_id"])    : null);
$attack = (isset($_POST["attack"])     ? $db->escape($_POST["attack"])    : null);
$defense = (isset($_POST["defense"])     ? $db->escape($_POST["defense"])    : null);
$successes = (isset($_POST["successes"])     ? $db->escape($_POST["successes"])    : null);
$description = (isset($_POST["description"])           ? $db->escape($_POST["description"])       : null);
$core = (isset($_POST["core"])           ? '1'       : '0');

//var_dump($_POST);

if (!$action) exit("No action selected.");
if (!$name) exit("No name.");
if (!$strain_id) exit("No strain_id.");
if ($attack === null) exit("No attack.");
if ($defense === null) exit("No defense.");
if (!$successes) exit("No successes.");
if (!$description) exit("No description.");
if ($core === null) exit("No core.");

// Act.
$message = "";

switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding character.";
        $db->insertCharacter($name, $strain_id, $attack, $defense, $successes, $description, $core);
        $message="Character created!";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_character.php?message=".urlencode($message)."#add_character");
?>
<!--a href="<?=SITE_HOST?>/admin_character.php?message=<?=urlencode($message)?>#add_character">Done. Back to characters.</a-->

