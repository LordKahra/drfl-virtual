<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\MapListPage;
use drflvirtual\src\view\page\MapPage;
use drflvirtual\src\view\page\MissingPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Load the database.
$db = new EventDatabase();

// Load the ID.
$map_id =       (isset($_GET["id"])         ? $db->escape($_GET["id"])           : false);
$filter =       (isset($_GET["filter"])     ? $db->escape($_GET["filter"])       : false);
$filter_id =    (isset($_GET["filter_id"])  ? $db->escape($_GET["filter_id"])    : false);

// Create the page.
$page = false;

// If valid ID, render single map page.
if ($map_id) {
    try {
        // Get the map.
        $map = $db->getMap($map_id);

        // Create the page.
        $page = new MapPage($map->getName(), "map");
    } catch (MapNotFoundException $e) {
        // Rip.
        $page = new MissingPage("Map Not Found", "Map not found.");
    }
} else {
    // Create the query, if any.
    $filter_query = "";

    switch($filter) {
        //case "unfinished":
            //$filter_query = "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR tabletop_status IS NULL OR (tabletop_status NOT LIKE 'READY' AND tabletop_status NOT LIKE 'NONE') OR NOT is_ready) AND host NOT LIKE 'Skipped'";
            //break;
        case "all":
            break;
        case "event":
            $filter_query = "id IN (SELECT map_id FROM r_mod_maps WHERE mod_id IN (SELECT id FROM mods WHERE event_id = $filter_id))";
            break;
        case "player":
            $filter_query = "creator_id = $filter_id";
            break;
        case false:
        case "current":
        default:
            // Use the latest event by default.
            $filter_query = "id IN (SELECT map_id FROM r_mod_maps WHERE mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))";
            break;
    }

    // Get the maps.
    $maps = $db->getMaps($filter_query);

    // Create the page.
    $page = new MapListPage($maps);

}

$page->render();

