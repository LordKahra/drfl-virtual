<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\MissingPage;
use drflvirtual\src\view\page\PlotListPage;
use drflvirtual\src\view\page\PlotPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Load the database.
global /** @var EventDatabase $db */ $db;

// Load the ID.
$plot_id =       (isset($_GET["id"])         ? $db->escape($_GET["id"])           : false);
$filter =       (isset($_GET["filter"])     ? $db->escape($_GET["filter"])       : false);
$filter_id =    (isset($_GET["filter_id"])  ? $db->escape($_GET["filter_id"])    : false);

// Create the page.
$page = false;

// If valid ID, render single mod page.
if ($plot_id) {
    try {
        // Get the mod.
        $plot = $db->getPlot($plot_id);

        // Create the page.
        $page = new PlotPage($plot);
    } catch (PlotNotFoundException $e) {
        // Rip.
        $page = new MissingPage("Plot Not Found", "Plot not found.");
    }
} else {
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
    $plots = $db->getPlots($filter_query);

    // Create the page.
    $page = new PlotListPage($plots);

}

$page->render();