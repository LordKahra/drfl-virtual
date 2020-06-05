<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\AdminModPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Authentication.
// TODO: This will be part of every page eventually.
require_once SITE_ROOT . "/src/admin/auth.php";

// Open the database.
global /** @var EventDatabase $db */ $db;

// Get the mods.
$filter_id = (isset($_GET["filter_id"])      ? $db->escape($_GET["filter_id"])     : false);
$filter = (isset($_GET["filter"])     ? $db->escape($_GET["filter"])     : false);

$mods = array();
switch($filter) {
    case "character":
        $mods = $db->getMods("id IN (SELECT mod_id FROM r_mod_characters WHERE character_id = $filter_id)", '`start`');
        break;
    case "event":
        $mods = $db->getMods("event_id = $filter_id", '`name`');
        break;
    case "all":
        $mods = $db->getMods("", '`name`');
    case "current":
    default:
        $mods = $db->getMods("event_id = " . CURRENT_EVENT, '`name`');
        break;
}

// If there are no mods, say so.
$page = new AdminModPage($mods);

$page->render();