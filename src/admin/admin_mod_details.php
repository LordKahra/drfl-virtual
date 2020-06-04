<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

var_dump($_POST);

// Gather info.
$action = (isset($_POST["action"]) ? $db->escape($_POST["action"]) : false);
$mod_id = (isset($_POST["mod_id"]) ? $db->escape($_POST["mod_id"]) : false);
$field  = (isset($_POST["field"])  ? $db->escape($_POST["field"])  : false);
$value  = (isset($_POST["value"])  ? $db->escape($_POST["value"])  : '0');

if (!$action) exit("No action selected.");
if (!$mod_id) exit("No mod selected.");
if (!$field) exit("No field or value.");

// Set the value.
$db->setModDetail($mod_id, $field, $value);
$message="Mod detail set.";

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_mod.php?mod_id=$mod_id&message=".urlencode($message) . "#mod_$mod_id");
?>
<!--a href="<?=SITE_HOST?>/admin_mod.php?mod_id=<?=$mod_id?>">Done. Back to mod.</a-->



