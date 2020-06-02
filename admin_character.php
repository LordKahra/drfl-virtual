<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\AdminCharacterPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Authentication.
// TODO: This will be part of every page eventually.
require_once SITE_ROOT . "/src/admin/auth.php";

// Open the database.
$db = new EventDatabase();

// Get the mods.
$filter_id = (isset($_GET["filter_id"])      ? $db->escape($_GET["filter_id"])     : false);
$filter = (isset($_GET["filter"])     ? $db->escape($_GET["filter"])     : false);

$characters = array();
switch($filter) {
    case "event":
        $characters = $db->getCharacters("id IN (SELECT character_id FROM r_mod_characters WHERE mod_id IN (SELECT id FROM mods WHERE event_id = $filter_id))");
        break;
    case "current":
        $characters = $db->getCharacters("id IN (SELECT character_id FROM r_mod_characters WHERE mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))");
        break;
    case "all":
    default:
        // By default, get all characters.
        $characters = $db->getCharacters("");
        break;
}

// If there are no mods, say so.
$page = new AdminCharacterPage($characters);

$page->render();
