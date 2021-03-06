<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Mod;
use drflvirtual\src\view\page\MissingPage;
use drflvirtual\src\view\page\ModListPage;
use drflvirtual\src\view\page\ModPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';

// Load the database.
global /** @var EventDatabase $db */ $db;

// Load the ID.
$mod_id =       (isset($_GET["id"])         ? $db->escape($_GET["id"])           : false);
$filter =       (isset($_GET["filter"])     ? $db->escape($_GET["filter"])       : false);
$filter_id =    (isset($_GET["filter_id"])  ? $db->escape($_GET["filter_id"])    : false);

// DEBUGGING.

// Create the page.
$page = false;

// If valid ID, render single mod page.
if ($mod_id) {
    try {
        echo "<script>console.log('mod.php: Getting individual mod.')</script>";

        // Get the mod.
        $mod = $db->getMod($mod_id);

        // Create the page.
        $page = new ModPage($mod->getName(), $mod);
    } catch (ModNotFoundException $e) {
        echo "<script>console.log('mod.php: Mod not found.')</script>";
        // Rip.
        $page = new MissingPage("Mod Not Found", "Mod not found.");
    }
} else {
    echo "<script>console.log('mod.php: Getting list of mods.')</script>";
    // Create the query, if any.
    $filter_query = "";
    $filter_sort = "`name`";

    switch($filter) {
        case "unfinished":
            $filter_query = "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR tabletop_status IS NULL OR (tabletop_status NOT LIKE 'READY' AND tabletop_status NOT LIKE 'NONE') OR NOT is_ready) AND host NOT LIKE 'Skipped'";
            break;
        case "event":
            $filter_query = "event_id = $filter_id";
            break;
        case "character":
            $filter_query = "id IN (SELECT mod_id FROM r_mod_characters WHERE character_id = $filter_id)";
            $filter_sort = 'start';
            break;
        case "guide":
            $filter_query = "id IN (SELECT mod_id FROM r_mod_guides WHERE guide_id = $filter_id)";
            break;
        case "current_guide":
            $filter_query = "id IN (SELECT mod_id FROM r_mod_guides WHERE guide_id = $filter_id AND mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))";
            // Sort this one by time.
            $filter_sort = 'start';
            break;
        case "all":
            $filter_query = "";
            break;
        case "current":
        case false:
        default:
            // Default filter is by current event.
            $filter_query = "event_id = " . CURRENT_EVENT;
            break;
    }

    // Get the mods.
    $mods = $db->getMods($filter_query, $filter_sort);

    // Create the page.
    $page = new ModListPage($mods);

}

echo "<script>console.log('mod.php: About to render page.')</script>";
$page->render();
echo "<script>console.log('mod.php: Done rendering page.')</script>";
