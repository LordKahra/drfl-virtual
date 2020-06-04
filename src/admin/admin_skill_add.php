<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

$action = (isset($_POST["action"]) ? $db->escape($_POST["action"])          : null);
$name = (isset($_POST["name"])     ? $db->escape($_POST["name"])    : null);
$text = (isset($_POST["text"])           ? $db->escape($_POST["text"])       : null);

if (!$action) exit("No action selected.");
if (!$name) exit("No name.");
if (!$text) exit("No description.");

// Act.
$message = "";

switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding skill.";
        $db->insertSkill($name, $text);
        $message="Skill created!";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_character.php?message=".urlencode($message)."#add_character");
?>
<!--a href="<?=SITE_HOST?>/admin_character.php?message=<?=urlencode($message)?>#add_character">Done. Back to characters.</a-->

