<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\view\page\CharacterPage;
use drflvirtual\src\view\page\CharacterListPage;
use drflvirtual\src\view\page\MissingPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';

// Load the database.
global /** @var EventDatabase $db */ $db;

// Load the ID.
$character_id = (isset($_GET["id"])        ? $_GET["id"]                     : false);
$filter =       (isset($_GET["filter"])    ? $db->escape($_GET["filter"])    : false);
$filter_id =    (isset($_GET["filter_id"]) ? $db->escape($_GET["filter_id"]) : false);

// Create the page.
$page = false;

// If valid ID, render single character page.
if ($character_id) {
    try {
        // Get the character.
        $character = $db->getCharacter($character_id);
        // Create the page.
        $page = new CharacterPage($character);
    } catch (CharacterNotFoundException $e) {
        $page = new MissingPage("Character not Found", "Character Not Found");
    }
} else {
    // Create the query, if any.
    $filter_query = "";

    switch($filter) {
        //case "unfinished":
        //$filter_query = "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR tabletop_status IS NULL OR (tabletop_status NOT LIKE 'READY' AND tabletop_status NOT LIKE 'NONE') OR NOT is_ready) AND host NOT LIKE 'Skipped'";
        //break;
        case "event":
            $filter_query = "id IN (SELECT character_id FROM r_mod_characters WHERE mod_id IN (SELECT id FROM mods WHERE event_id = $filter_id))";
            break;
        case "casting":
            $filter_query = "id IN (SELECT character_id FROM r_character_casting WHERE player_id = $filter_id)";
            break;
        case "player_current":
            $filter_query = "id IN (SELECT character_id FROM r_character_casting WHERE player_id = $filter_id) AND id IN (SELECT character_id FROM r_mod_characters WHERE mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))";
            break;
        case "current":
            $filter_query = "id IN (SELECT character_id FROM r_mod_characters WHERE mod_id IN (SELECT id FROM mods WHERE event_id = " . CURRENT_EVENT . "))";
            break;
        case "all":
        case false:
        default:
            // Use all events by default.
            break;
    }


    // Get all characters.
    $characters = $db->getCharacters($filter_query);

    // Create the page.
    $page = new CharacterListPage($characters);
}

$page->render();