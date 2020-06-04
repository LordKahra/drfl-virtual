<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

$action = (isset($_POST["action"])     ? $db->escape($_POST["action"])     : false);
$character_id = (isset($_POST["character_id"])     ? $db->escape($_POST["character_id"])     : false);
$skill_id = (isset($_POST["skill_id"]) ? $db->escape($_POST["skill_id"])   : false);
$uses = (isset($_POST["uses"]) ? $db->escape($_POST["uses"])   : 1);

if (!$action) exit("No action selected.");
if (!$character_id || !$skill_id) exit("No character or skill id.");
if (!$uses) exit("No uses.");

// Act.
$message = "";
switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding skill.";
        $db->addCharacterSkill($character_id, $skill_id, $uses);
        $message="Skill added to character.";
        break;
    case "delete":
        echo "<br/>Removing skill.";
        $db->deleteCharacterSkill($character_id, $skill_id);
        $message="Skill deleted from character.";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_character.php?character_id=$character_id&message=".urlencode($message) . "#character_$character_id");
?>
<!--a href="<?=SITE_HOST?>/admin_character.php?character_id=<?=$character_id?>">Done. Back to character.</a-->
