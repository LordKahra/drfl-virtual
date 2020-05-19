<?php



require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';

global /** @var mysqli $mysqli */ $mysqli;

// Load the ID.
$character_id =           (isset($_GET["id"])            ? $_GET["id"] : false);

// If valid ID, render single character page.
if ($character_id) {

// Get the character.
    $character = getCharacterWithSkills($character_id);

// Render.

    renderSingleCharacterPage($character);
} else {
    // Get all characters.
    $characters = getAllCharactersWithSkills();

    // Render.
    renderMultiCharacterPage($characters);
}