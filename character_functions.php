<?php

    const CHARACTER_SELECT =
"SELECT
    toon.id AS id,
    toon.name AS `name`,
    toon.attack AS attack,
    toon.defense AS defense,
    toon.successes AS successes,
    toon.description AS description,
    strain.name AS strain,
    lineage.name AS lineage,
    type.name AS type,
    strain.id AS strain_id,
    lineage.id AS lineage_id,
    type.id AS type_id
FROM characters toon
LEFT JOIN z_strains strain ON toon.strain_id = strain.id
LEFT JOIN z_lineages lineage ON strain.lineage_id = lineage.id
LEFT JOIN z_character_types type ON lineage.type_id = type.id";

function getMod(int $id) {
    $mod_array = getQueryResults("SELECT * FROM mods WHERE id = $id");

    if (!$mod_array) return false;
    if (is_array($mod_array['0'])) return $mod_array['0'];
    return false;
}

function getAllModsWithCharacters() {
    // Get all mods.
    $mods = getQueryResults("SELECT * FROM mods ORDER BY `name`");
    if (!$mods) return false;

    // Get their characters.
    foreach($mods as &$mod) {
        $mod_characters = getModCharacters($mod['id']);

        $mod['characters'] = (is_array($mod_characters)) ? $mod_characters : array();
    }

    return $mods;
}

function getModWithCharacters(int $id) {
    $mod = getMod($id);
    if (!$mod) return false;

    // Get their characters.
    $character_arrays = getModCharacters($id);

    $mod['characters'] = (is_array($character_arrays)) ? $character_arrays : array();

    return $mod;
}

function getModCharacters($mod_id) {
    return getAllCharactersWithSkills("toon.id IN (SELECT character_id FROM r_mod_characters WHERE mod_id = $mod_id)");
    //$query = CHARACTER_SELECT . " WHERE toon.id IN (SELECT character_id FROM r_mod_characters WHERE mod_id = $mod_id)";

    //return getQueryResults($query);
}


function getCharacter(int $id) {
    global /** @var mysqli $mysqli */ $mysqli;

    $query = CHARACTER_SELECT . " WHERE toon.id = $id";

    $character_array = getQueryResults($query);
    //var_dump($character_array);
    if (!$character_array) return false;
    if (is_array($character_array['0'])) return $character_array['0'];
    return false;
}

function getAllCharacters(string $where="", string $order_by="`name`") {

    $character_array = getQueryResults(
            CHARACTER_SELECT .
            ($where ? " \nWHERE $where" : "") .
            ($order_by ? " \nORDER BY $order_by" : "")
    );
    if (!$character_array) return false;
    return $character_array;
}



function getAllCharactersWithSkills(string $where="") {
    // Get the characters.
    $characters = getAllCharacters($where);
    //var_dump($characters);

    // Get their skills.
    if (!$characters) return array();
    foreach($characters as &$character) {
        $character_skills = getCharacterSkills($character['id']);
        $lineage_skills = getLineageSkills($character['lineage_id']);
        $type_skills = getTypeSkills($character['type_id']);

        $skills = array();
        if ($character_skills)  foreach ($character_skills  as $skill) $skills[] = $skill;
        if ($lineage_skills)    foreach ($lineage_skills    as $skill) $skills[] = $skill;
        if ($type_skills)       foreach ($type_skills       as $skill) $skills[] = $skill;
        $character['skills'] = $skills;
    }

    // Done.
    return $characters;
}

function getCharacterWithSkills(int $id) {
    // Get the character.
    $character = getCharacter($id);

    // Get the skills.
    $character_skills = getCharacterSkills($id);
    $lineage_skills = getLineageSkills($character['lineage_id']);
    $type_skills = getTypeSkills($character['type_id']);

    $skills = array();
    if ($character_skills)  foreach ($character_skills  as $skill) $skills[] = $skill;
    if ($lineage_skills)    foreach ($lineage_skills    as $skill) $skills[] = $skill;
    if ($type_skills)       foreach ($type_skills       as $skill) $skills[] = $skill;
    $character['skills'] = $skills;

    // Done. Return.
    return $character;
}

/**
 * @param int $id
 * @return array|bool
 */
function getCharacterSkills(int $id) {
    $query =
"SELECT
  skills.id AS id,
  skills.name AS name,
  skills.text AS text,
  r_character_skills.uses AS uses
FROM skills 
LEFT JOIN r_character_skills 
    ON r_character_skills.character_id = $id
    AND r_character_skills.skill_id = skills.id
WHERE skills.id IN (SELECT skill_id FROM r_character_skills WHERE character_id = $id)";

    return getQueryResults($query);
}

function getTypeSkills(int $id) {
    $query =
"SELECT
  skills.id AS id,
  skills.name AS name,
  skills.text AS text,
  r_type_skills.uses AS uses
FROM skills
LEFT JOIN r_type_skills 
    ON r_type_skills.type_id = $id
    AND r_type_skills.skill_id = skills.id
WHERE id IN (SELECT skill_id FROM r_type_skills WHERE type_id = $id)";

    return getQueryResults($query);
}

function getLineageSkills(int $id) {
    $query =
"SELECT
  skills.id AS id,
  skills.name AS name,
  skills.text AS text,
  r_lineage_skills.uses AS uses
FROM skills
LEFT JOIN r_lineage_skills 
    ON r_lineage_skills.lineage_id = $id
    AND r_lineage_skills.skill_id = skills.id
WHERE id IN (SELECT skill_id FROM r_lineage_skills WHERE lineage_id = $id)";

    return getQueryResults($query);
}

function getQueryResults($query) {
    global /** @var mysqli $mysqli */ $mysqli;

    echo "\n<!-- Running query: $query -->";

    $result = $mysqli->query($query);
    if (!$result) return false;

    $record = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (!$record) {
        //(new MissingPage("Character - Not Found", "That character was not found."))->render();
        return false;
    }

    return $record;
}

function renderHeader($title) {
?><!DOCTYPE html>
<html>
<head>
    <title><?=$title;?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/nav.css"/>
</head>
<body>
<header>
    <main><a href="index.php">DRFL Virtual Event</a></main>
    <nav>
        <ul>
            <li><a href="character.php">Characters</a></li>
            <li><a href="mod.php">Mods</a></li>
        </ul>
    </nav>
</header>
<?php
}

function renderCharacter($character) {
    ?>
    <div data-type="character">
        <header><a href="character.php?id=<?=$character['id']?>"><?=$character['name']?></a></header>

        <div data-type="types"><?=$character['type']?> - <?=$character['lineage']?> - <?=$character['strain']?></div>
        <div data-type="description">

            <main><?=nl2br($character['description'])?></main>
        </div>
        <?php
            if(array_key_exists('skills', $character) && is_array($character['skills']))
                foreach($character['skills'] as $skill) { ?>
        <div data-type="skill">
            <header><?=$skill['name'] . ($skill['uses'] == 1 ? "" : " x {$skill['uses']}")?></header>
            <div><?=$skill['text']?></div>
        </div>
        <?php } ?>
        <div data-type="attributes">
            <div>⚔️4d6<?=($character['attack']?$character['attack']:'')?></div>
            <div>🛡️ <?=$character['defense']?></div>
            <div>💓️ <?=($character['successes']?$character['successes']:'')?></div>
        </div>

    </div>
    <?php
}

function renderModSmall(array $mod) {
    ?>
    <a href="mod.php?id=<?=$mod['id']?>"><div data-type="mod" data-style="small">
        <header><div data-type="name"><b><?=$mod['name']?></b></div></header>
        <div><b>Location:</b> <?=$mod['location']?></div>
        <div><?=$mod['host']?> - <?=$mod['start']?></div>
        <div><b>Characters:</b> <?php
            if (array_key_exists('characters', $mod) && is_array($mod['characters'])) {
                $character_names = array();
                foreach ($mod['characters'] as $character) {
                    //$character_names[] = "<a href='character.php?id=" . $character['id'] . "'>" . $character['name'] . "</a>";
                    $character_names[] = $character['name'];
                }
                echo implode(", ", $character_names);
            } else {
                echo "None.";
            }
        ?> </div>

        <div><?=
            strlen($mod['description']) > 300 ?
                nl2br(substr($mod['description'], 0, 300)) . "..." :
                nl2br($mod['description'])
            ?></div>

    </div></a>
    <?php
}

function renderModList(array $mods) {
    ?>
<ul>
    <?php foreach ($mods as $mod) { ?>
        <li><a href="mod.php?id=<?=$mod['id']?>"><?=$mod['name']?></a></li>
    <?php } ?>
</ul>
    <?php
}

function renderMod(array $mod) {
    ?>
    <div data-type="mod">
        <header>
            <div data-type="name"><a href="mod.php?id=<?=$mod['id']?>"><?=$mod['name']?></a></div>
            <div><?=$mod['location']?></div>
            <div><?=$mod['host']?> - <?=$mod['start']?></div>
        </header>
        <div><?=nl2br($mod['description'])?></div>
        <?php
        if(array_key_exists('characters', $mod) && is_array($mod['characters'])) {
            ?><div data-type="characters">
                <header>Characters</header><?php
                foreach ($mod['characters'] as $character) renderCharacter($character);
        }
        ?></div>
    </div>
    <?php
}

function renderSingleCharacterPage($character) {
    renderHeader($character['name']);
    renderCharacter($character);
}

function renderMultiCharacterPage($characters) {
    renderHeader("Characters");
    foreach($characters as $character) {
        renderCharacter($character);
    }
}

function renderSingleModPage($mod) {
    renderHeader($mod['name']);
    //var_dump($mod);
    renderMod($mod);
}

function renderMultiModPage($mods) {
    renderHeader("Mods");
    //renderModList($mods);
    foreach($mods as $mod) {
        renderModSmall($mod);
    }
}