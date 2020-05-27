<?php

namespace drflvirtual\src\model\database;

use Character;
use drflvirtual\src\model\Event;
use drflvirtual\src\model\Mod;
use drflvirtual\src\model\Player;
use EventNotFoundException;
use ModNotFoundException;
use mysqli;

class EventDatabase {
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
    type.id AS type_id,
    toon.core AS core
FROM characters toon
LEFT JOIN z_strains strain ON toon.strain_id = strain.id
LEFT JOIN z_lineages lineage ON strain.lineage_id = lineage.id
LEFT JOIN z_character_types type ON lineage.type_id = type.id";

    protected $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

    }

    ////////////////////////////////
    // GET - PUBLIC - SINGULAR
    ////////////////////////////////

    /**
     * @param int $id
     * @return Mod
     * @throws ModNotFoundException
     */
    public function getMod(int $id) : Mod {
        $mod_array = $this->getRawMod($id);
        if (!$mod_array) throw new ModNotFoundException($id);

        // Get details.
        $character_arrays = $this->getRawModCharacters($id);
        $guide_arrays = $this->getRawModGuides($id);

        $mod_array['characters'] = (is_array($character_arrays)) ? $character_arrays : array();
        $mod_array['guides'] = (is_array($guide_arrays)) ? $guide_arrays : array();

        $mod = Mod::constructFromArray($mod_array);

        return $mod;
    }

    /**
     * @param int $id
     * @return Event
     * @throws EventNotFoundException
     */
    public function getEvent(int $id) : Event {
        $event_array = $this->getRawEventWithDetails($id);
        if (!$event_array) throw new EventNotFoundException($id);

        $event = Event::constructFromArray($event_array);

        return $event;
    }

    ////////////////////////////////
    // GET - PUBLIC - PLURAL
    ////////////////////////////////

    public function getEvents(string $where="") : array {
        $arrays = $this->getRawEventsWithDetails($where);

        $events = array();
        foreach ($arrays as $array) {
            $event = Event::constructFromArray($array);
            $events[$event->getId()] = $event;
        }

        return $events;
    }

    public function getMods(string $where="", string $order="") : array {
        $mod_arrays = $this->getRawModsWithDetails($where, $order);

        $mods = array();
        foreach ($mod_arrays as $mod_array) {
            $mod = Mod::constructFromArray($mod_array);
            $mods[$mod->getId()] = $mod;
        }

        return $mods;
    }

    /**
     * @param string $where
     * @return Player[]
     */
    public function getPlayers(string $where="") : array {
        $player_arrays = $this->getRawPlayers($where);

        $players = array();
        foreach($player_arrays as $player_array) {
            $players[] = Player::constructFromArray($player_array);
        }

        return $players;
    }

    /**
     * @param string $where
     * @return Character[]
     */
    public function getCharacters(string $where="") : array {
        $character_arrays = $this->getRawCharactersWithSkills($where);

        $characters = array();
        foreach($character_arrays as $character_array) {
            $characters[] = Character::constructFromArray($character_array);
        }

        return $characters;
    }

    ////////////////////////////////
    // GET - PUBLIC, SPECIFIC
    ////////////////////////////////

    public function getGuides() {
        return $this->getPlayers("is_guide = true");
    }

    ////////////////////////////////
    // GET - PRIVATE, SPECIFIC
    ////////////////////////////////

    private function getRawEventMods($event_id) {
        return $this->getRawModsWithDetails("event_id = $event_id");
    }

    private function getRawModCharacters($mod_id) {
        return $this->getRawCharactersWithSkills("toon.id IN (SELECT character_id FROM r_mod_characters WHERE mod_id = $mod_id)");
    }

    private function getRawModGuides($mod_id) {
        return $this->getRawPlayers("id IN (SELECT guide_id FROM r_mod_guides WHERE mod_id = $mod_id)");
    }

    ////////////////////////////////
    // GET - PRIVATE - RAW WITH DETAILS
    ////////////////////////////////

    private function getRawEventWithDetails(int $id) {
        $events = $this->getRawEventsWithDetails("id = $id");
        if (!$events) return false;
        if (is_array($events['0'])) return $events['0'];
        return false;
    }

    private function getRawEventsWithDetails(string $where) {
        // Get the events.
        $event_arrays = $this->getRawEvents($where);

        if (!$event_arrays) return array();

        // Add details.
        foreach($event_arrays as &$event) {
            $event['mods'] = $this->getRawEventMods($event['id']);
        }

        return $event_arrays;
    }

    private function getRawModsWithDetails(string $where="", $order="") {
        // Get the mods.
        $mod_arrays = $this->getRawMods($where, $order);

        if (!$mod_arrays) return array();

        foreach($mod_arrays as &$mod_array) {
            $mod_array['characters'] = $this->getRawModCharacters($mod_array['id']);
            $mod_array['guides'] = $this->getRawModGuides($mod_array['id']);
        }

        return $mod_arrays;
    }

    private function getRawCharactersWithSkills(string $where="") {
        // Get the characters.
        $characters = $this->getRawCharacters($where);

        // Get their skills.
        if (!$characters) return array();
        foreach($characters as &$character) {
            $character_skills = $this->getCharacterSkills($character['id']);
            $lineage_skills = $this->getLineageSkills($character['lineage_id']);
            $type_skills = $this->getTypeSkills($character['type_id']);

            $skills = array();
            if ($character_skills)  foreach ($character_skills  as $skill) $skills[] = $skill;
            if ($lineage_skills)    foreach ($lineage_skills    as $skill) $skills[] = $skill;
            if ($type_skills)       foreach ($type_skills       as $skill) $skills[] = $skill;
            $character['skills'] = $skills;
        }

        // Done.
        return $characters;
    }

    ////////////////////////////////
    // GET - PRIVATE - RAW
    ////////////////////////////////
    ///
    private function getRawEvent(int $id) {
        $array = $this->runGetQuery("SELECT * FROM events WHERE id = $id");

        if (!$array) return false;
        if (is_array($array['0'])) return $array['0'];
        return false;
    }

    private function getRawEvents(string $where="") {
        $array = $this->runGetQuery("SELECT * FROM events " . ($where ? "WHERE $where" : ""));

        if (!$array) return false;
        return $array;
    }

    private function getRawMod(int $id) {
        $mod_array = $this->runGetQuery("SELECT * FROM mods WHERE id = $id");

        if (!$mod_array) return false;
        if (is_array($mod_array['0'])) return $mod_array['0'];
        return false;
    }

    private function getRawMods(string $where="", string $order="") {
        $mod_array = $this->runGetQuery(
            "SELECT * FROM mods "
            . ($where ? " WHERE $where " : "")
            . ($order ? " ORDER BY $order " : "")
        );

        if (!$mod_array) return false;
        return $mod_array;
    }

    private function getRawCharacters(string $where="", string $order_by="`name`") {
        $character_array = $this->runGetQuery(
            static::CHARACTER_SELECT .
            ($where ? " \nWHERE $where" : "") .
            ($order_by ? " \nORDER BY $order_by" : "")
        );
        if (!$character_array) return false;
        return $character_array;
    }

    private function getRawPlayers(string $where="", string $order_by="`name`") {
        $player_array = $this->runGetQuery(
            "SELECT * FROM players " .
            ($where ? " \nWHERE $where" : "") .
            ($order_by ? " \nORDER BY $order_by" : "")
        );
        if (!$player_array) return false;
        return $player_array;
    }

    private function getCharacterSkills(int $id) {
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

        return $this->runGetQuery($query);
    }

    private function getTypeSkills(int $id) {
        $query =
            "SELECT
  skills.id AS id,
  skills.name AS `name`,
  skills.text AS text,
  r_type_skills.uses AS uses
FROM skills
LEFT JOIN r_type_skills 
    ON r_type_skills.type_id = $id
    AND r_type_skills.skill_id = skills.id
WHERE skills.id IN (SELECT skill_id FROM r_type_skills WHERE type_id = $id)";

        return $this->runGetQuery($query);
    }

    private function getLineageSkills(int $id) {
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
WHERE skills.id IN (SELECT skill_id FROM r_lineage_skills WHERE lineage_id = $id)";

        return $this->runGetQuery($query);
    }

    ////////////////////////////////
    // WRITE
    ////////////////////////////////

    public function addGuideToMod(int $mod_id, int $guide_id) {
        $query = "INSERT INTO r_mod_guides (mod_id, guide_id) VALUES ($mod_id, $guide_id) ON DUPLICATE KEY UPDATE id=id";
        // id=id avoids increasing auto increment. :)

        $result = $this->runUpsertQuery($query);

        var_dump($result);

        return $result;
    }

    ////////////////////////////////
    // DELETE
    ////////////////////////////////

    public function deleteGuideFromMod(int $mod_id, int $guide_id) {
        $query = "DELETE FROM r_mod_guides WHERE mod_id = $mod_id AND guide_id = $guide_id";

        $result = $this->runDeleteQuery($query);

        return $result;
    }

    private function runGetQuery($query) {
        //echo("<br/><br/>" . $query);

        $result = $this->mysqli->query($query);
        if (!$result) return false;

        $record = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if (!$record) {
            //(new MissingPage("Character - Not Found", "That character was not found."))->render();
            return false;
        }

        return $record;
    }

    private function runUpsertQuery($query) {
        echo("<br/><br/>" . $query);

        $result = $this->mysqli->query($query);

        // Handle errors.
        if (!$result) {
            var_dump($this->mysqli);
        }

        return $result;
    }

    private function runDeleteQuery($query) {
        return $this->mysqli->query($query);
    }

    ////////////////////////////////
    // UTILITY
    ////////////////////////////////

    public function escape($string) {
        return mysqli_real_escape_string($this->mysqli, $string);
    }
}