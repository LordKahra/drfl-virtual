<?php

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'character_functions.php';

global /** @var mysqli $mysqli */ $mysqli;

// Load the ID.
$mod_id = (isset($_GET["id"])     ? mysqli_real_escape_string($mysqli, $_GET["id"])     : false);
$filter = (isset($_GET["filter"]) ? mysqli_real_escape_string($mysqli, $_GET["filter"]) : false);

// If valid ID, render single mod page.
if ($mod_id) {

// Get the character.
    $mod = getModWithCharacters($mod_id);

// Render.

    renderSingleModPage($mod);

} else {
    $mods = false;

    switch($filter) {
        case "unfinished":
            $mods = getAllModsWithCharacters(
                "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR roll20_status IS NULL OR (roll20_status NOT LIKE 'READY' AND roll20_status NOT LIKE 'NONE')) AND host NOT LIKE 'Skipped'"
            );
            break;
        case false:
        default:
            $mods = getAllModsWithCharacters();
            break;
    }

    renderMultiModPage($mods);
}
