<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\FactionListPage;
use drflvirtual\src\view\page\FactionPage;
use drflvirtual\src\view\page\MissingPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';

// Load the database.
global /** @var EventDatabase $db */ $db;

// Load the ID.
$faction_id = (isset($_GET["id"])        ? $_GET["id"]                     : false);
$filter =       (isset($_GET["filter"])    ? $db->escape($_GET["filter"])    : false);
$filter_id =    (isset($_GET["filter_id"]) ? $db->escape($_GET["filter_id"]) : false);

// Create the page.
$page = false;

// If valid ID, render single faction page.
if ($faction_id) {
    try {
        // Get the mod.
        $faction = $db->getFaction($faction_id);

        // Create the page.
        $page = new FactionPage($faction->getName(), $faction);
    } catch (FactionNotFoundException $e) {
        // Rip.
        $page = new MissingPage("Faction Not Found", "Faction not found.");
    }
} else {
    // Create the query, if any.
    $filter_query = "";
    $filter_sort = "`name`";

    switch($filter) {
        /*case "unfinished":
            $filter_query = "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR tabletop_status IS NULL OR (tabletop_status NOT LIKE 'READY' AND tabletop_status NOT LIKE 'NONE') OR NOT is_ready) AND host NOT LIKE 'Skipped'";
            break;*/
        /*case "event":
            $filter_query = "event_id = $filter_id";
            break;*/
        case "character":
            $filter_query = "id IN (SELECT faction_id FROM r_faction_characters WHERE character_id = $filter_id)";
            //$filter_sort = 'start';
            break;
        /*case "guide":
            $filter_query = "id IN (SELECT mod_id FROM r_mod_guides WHERE guide_id = $filter_id)";
            break;*/
        /*case "current_guide":
            $filter_query = "id IN (SELECT mod_id FROM r_mod_guides WHERE guide_id = $filter_id AND mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))";
            // Sort this one by time.
            $filter_sort = 'start';
            break;*/
        case "all":
        default:
            $filter_query = "";
            break;
        //case "current":
        //case false:
        /*default:
            // Default filter is by current event.
            $filter_query = "event_id = " . CURRENT_EVENT;
            break;*/
    }

    // Get the mods.
    $factions = $db->getFactions($filter_query, $filter_sort);

    // Create the page.
    $page = new FactionListPage($factions);

}

$page->render();


