<?php

use drflvirtual\src\model\Mod;
use drflvirtual\src\view\page\ModPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';

global /** @var mysqli $mysqli */ $mysqli;

// Load the ID.
$mod_id = (isset($_GET["id"])     ? mysqli_real_escape_string($mysqli, $_GET["id"])     : false);
$filter = (isset($_GET["filter"]) ? mysqli_real_escape_string($mysqli, $_GET["filter"]) : false);

// If valid ID, render single mod page.
if ($mod_id) {

// Get the mod.
    $mod_array = getModWithCharacters($mod_id);
    $mod = Mod::constructFromArray($mod_array);

// Render.

    $page = new ModPage($mod->getName(), $mod);

    $page->render();

} else {
    $mods = false;

    switch($filter) {
        case "unfinished":
            $mods = getAllModsWithCharacters(
                "(location LIKE '???' OR description LIKE '???' OR map_status IS NULL OR roll20_status IS NULL OR (roll20_status NOT LIKE 'READY' AND roll20_status NOT LIKE 'NONE') OR NOT is_ready) AND host NOT LIKE 'Skipped'"
            );
            break;
        case false:
        default:
            $mods = getAllModsWithCharacters();
            break;
    }

    renderMultiModPage($mods);
}
