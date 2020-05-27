<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\AdminModPage;

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

$mods = array();
switch($filter) {
    case "event":
        $mods = $db->getMods("event_id = $filter_id", '`name`');
        break;
    default:
        $mods = $db->getMods("", '`name`');
        break;
}

// If there are no mods, say so.
$page = new AdminModPage($mods);

$page->render();