<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

var_dump($_POST);

// Gather info.
$action = (isset($_POST["action"])              ? $db->escape($_POST["action"]) : false);
$character_id = (isset($_POST["character_id"])  ? $db->escape($_POST["character_id"]) : false);
$field  = (isset($_POST["field"])               ? $db->escape($_POST["field"])  : false);
$value  = (isset($_POST["value"])               ? $db->escape($_POST["value"])  : '0');

if (!$action)       exit("No action selected.");
if (!$character_id) exit("No character selected.");
if (!$field)        exit("No field or value.");

// Set the value.
$db->setCharacterDetail($character_id, $field, $value);
$message="Character detail set.";

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_character.php?character_id=$character_id&message=".urlencode($message) . "#character_$character_id");
?>
<!--a href="<?=SITE_HOST?>/admin_character.php?character_id=<?=$character_id?>">Done. Back to character.</a-->



