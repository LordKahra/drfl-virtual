<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
$db = new EventDatabase();

$action = (isset($_POST["action"])     ? $db->escape($_POST["action"])     : false);
$mod_id = (isset($_POST["mod_id"])     ? $db->escape($_POST["mod_id"])     : false);
$guide_id = (isset($_POST["guide_id"]) ? $db->escape($_POST["guide_id"])   : false);

if (!$action) exit("No action selected.");
if (!$mod_id || !$guide_id) exit("No mod or guide id.");

// Act.
$message = "";
switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding guide.";
        $db->addGuideToMod($mod_id, $guide_id);
        $message="Guide added to mod.";
        break;
    case "delete":
        echo "<br/>Removing guide.";
        $db->deleteGuideFromMod($mod_id, $guide_id);
        $message="Guide deleted from mod.";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_mod.php?mod_id=$mod_id&message=".urlencode($message));
?>
<!--a href="<?=SITE_HOST?>/admin_mod.php?mod_id=<?=$mod_id?>">Done. Back to mod.</a-->
