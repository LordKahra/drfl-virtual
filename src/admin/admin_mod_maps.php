<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

$action = (isset($_POST["action"])     ? $db->escape($_POST["action"])     : false);
$mod_id = (isset($_POST["mod_id"])     ? $db->escape($_POST["mod_id"])     : false);
$map_id = (isset($_POST["map_id"]) ? $db->escape($_POST["map_id"])   : false);

if (!$action) exit("No action selected.");
if (!$mod_id || !$map_id) exit("No mod or map id.");

// Act.
$message = "";
switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding map.";
        $db->addMapToMod($mod_id, $map_id);
        $message="Map added to mod.";
        break;
    case "delete":
        echo "<br/>Removing map.";
        $db->deleteMapFromMod($mod_id, $map_id);
        $message="Map deleted from mod.";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_mod.php?mod_id=$mod_id&message=".urlencode($message) . "#mod_$mod_id");
?>
<!--a href="<?=SITE_HOST?>/admin_mod.php?mod_id=<?=$mod_id?>">Done. Back to mod.</a-->
