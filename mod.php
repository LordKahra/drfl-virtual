<?php

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'character_functions.php';

global /** @var mysqli $mysqli */ $mysqli;

// Load the ID.
$mod_id =           (isset($_GET["id"])            ? $_GET["id"] : false);

// If valid ID, render single mod page.
if ($mod_id) {

// Get the character.
    $mod = getModWithCharacters($mod_id);

// Render.

    renderSingleModPage($mod);
} else {
    // Get all mods.
    $mods = getAllModsWithCharacters();

    // Render.
    renderMultiModPage($mods);
}
