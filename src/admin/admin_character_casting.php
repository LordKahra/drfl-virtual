<?php

use drflvirtual\src\model\database\EventDatabase;

require_once '../config/app_config.php';
require_once '../config/global_config.php';

// Connect to database.
global /** @var EventDatabase $db */ $db;

$action = (isset($_POST["action"])     ? $db->escape($_POST["action"])     : false);
$character_id = (isset($_POST["character_id"])     ? $db->escape($_POST["character_id"])     : false);
$player_id = (isset($_POST["player_id"]) ? $db->escape($_POST["player_id"])   : false);

if (!$action) exit("No action selected.");
if (!$character_id || !$player_id) exit("No character or player id.");

// Act.
$message = "";
switch (strtolower($action)) {
    case "add":
        echo "<br/>Adding player.";
        $db->addCharacterCasting($character_id, $player_id);
        $message="Player added to character.";
        break;
    case "delete":
        echo "<br/>Removing player.";
        $db->deleteCharacterCasting($character_id, $player_id);
        $message="Player deleted from character.";
        break;
    default:
        $message = "Invalid action chosen: $action";
}

// Done. Redirect or link back to the admin page.
header("Location: " . SITE_HOST . "/admin_character.php?character_id=$character_id&message=".urlencode($message) . "#character_$character_id");
?>
<!--a href="<?=SITE_HOST?>/admin_character.php?character_id=<?=$character_id?>">Done. Back to character.</a-->
