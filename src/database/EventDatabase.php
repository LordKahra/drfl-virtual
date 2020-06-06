<?php

namespace drflvirtual\src\model\database;

use drflvirtual\src\model\Character;
use drflvirtual\src\model\Event;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;
use drflvirtual\src\model\Player;
use drflvirtual\src\model\Plot;
use drflvirtual\src\model\Skill;
use drflvirtual\src\model\Strain;
use EventNotFoundException;
use MapNotFoundException;
use ModNotFoundException;
use mysqli;
use PlayerNotFoundException;
use PlotNotFoundException;

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

    // GLOBAL VALUES
    protected $strains;
    protected $players;

    public function __construct() {
        $this->mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

        // Load global data.
        $this->players = $this->getPlayers();
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
        $map_arrays = $this->getRawModMaps($id);

        $mod_array['characters'] = (is_array($character_arrays)) ? $character_arrays : array();
        $mod_array['guides'] = (is_array($guide_arrays)) ? $guide_arrays : array();
        $mod_array['maps'] = (is_array($map_arrays)) ? $map_arrays : array();

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

    /**
     * @param int $id
     * @return Map
     * @throws MapNotFoundException, PlayerNotFoundException
     */
    public function getMap(int $id) : Map {
        $array = $this->getRawMap($id);
        if (!$array) throw new MapNotFoundException($id);

        $map = Map::constructFromArray($array);

        return $map;
    }

    /**
     * @param int $id
     * @return Plot
     * @throws PlotNotFoundException
     */
    public function getPlot(int $id) : Plot {
        $array = $this->getRawPlot($id);
        if (!$array) throw new PlotNotFoundException($id);

        $plot = Plot::constructFromArray($array);

        return $plot;
    }

    /**
     * @param int $id
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function getPlayer(int $id) : Player {
        if (!array_key_exists($id, $this->players)) throw new PlayerNotFoundException($id);
        return $this->players[$id];
    }

    ////////////////////////////////
    // GET - PUBLIC - PLURAL
    ////////////////////////////////

    /**
     * @param string $where
     * @param bool $details
     * @return Event[]
     */
    public function getEvents(string $where="", bool $details=true) : array {
        $arrays = $details ? $this->getRawEventsWithDetails($where) : $this->getRawEvents($where);

        $events = array();
        foreach ($arrays as $array) {
            $event = Event::constructFromArray($array);
            $events[$event->getId()] = $event;
        }

        return $events;
    }

    public function getMods(string $where="", string $order="`name`") : array {
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
            $players[$player_array['id']] = Player::constructFromArray($player_array);
        }

        return $players;
    }

    /**
     * @param string $where
     * @return Character[]
     */
    public function getCharacters(string $where="") : array {
        $arrays = $this->getRawCharactersWithDetails($where);

        $characters = array();
        foreach($arrays as $array) {
            $characters[] = Character::constructFromArray($array);
        }

        return $characters;
    }

    /**
     * @param string $where
     * @return Plot[]
     */
    public function getPlots(string $where="", bool $details=true) : array {
        $arrays = $details ? $this->getRawPlotsWithDetails($where) : $this->getRawPlots($where);
        if (!$arrays) return array();

        $plots = array();
        foreach($arrays as $array) {
            $plots[$array['id']] = Plot::constructFromArray($array);
        }

        return $plots;
    }

    /**
     * @param string $where
     * @return Skill[]
     */
    public function getSkills(string $where="") : array {
        $arrays = $this->getRawSkills($where);

        $skills = array();
        foreach($arrays as $array) {
            // Set uses if necessary.
            if (!array_key_exists('uses', $array)) $array['uses'] = 1;

            // Create skill object.
            $skills[] = Skill::constructFromArray($array);
        }

        return $skills;
    }

    /**
     * @param string $where
     * @return Map[]
     */
    public function getMaps(string $where="", string $order="`name`") : array {
        $arrays = $this->getRawMaps($where, $order);

        if (!$arrays) return array();

        $maps = array();
        foreach($arrays as $array) {
            $maps[$array['id']] = Map::constructFromArray($array);
        }

        return $maps;
    }

    public function getStrains(string $where="") : array {
        $arrays = $this->getRawStrains($where);

        if (!$arrays) return array();

        $strains = array();
        foreach ($arrays as $array) {
            $strains[$array['id']] = Strain::constructFromArray($array);
        }

        return $strains;
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

    private function getRawPlotMods($plot_id) {
        return $this->getRawModsWithDetails("id IN (SELECT mod_id FROM r_plot_mods WHERE plot_id = $plot_id)", "`start`");
    }

    private function getRawModCharacters($mod_id) {
        return $this->getRawCharactersWithDetails("toon.id IN (SELECT character_id FROM r_mod_characters WHERE mod_id = $mod_id)");
    }

    private function getRawModGuides($mod_id) {
        return $this->getRawPlayers("id IN (SELECT guide_id FROM r_mod_guides WHERE mod_id = $mod_id)");
    }

    private function getRawModMaps($mod_id) {
        return $this->getRawMaps("id IN (SELECT map_id FROM r_mod_maps WHERE mod_id = $mod_id)");
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

    private function getRawPlot(int $id) {
        $plots = $this->getRawPlotsWithDetails("id = $id");
        if (!$plots) return false;
        if (is_array($plots['0'])) return $plots['0'];
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

    private function getRawPlotsWithDetails(string $where) {
        // Get the events.
        $plot_arrays = $this->getRawPlots($where);

        if (!$plot_arrays) return array();

        // Add details.
        foreach($plot_arrays as &$plot) {
            $plot['mods'] = $this->getRawPlotMods($plot['id']);
        }

        return $plot_arrays;
    }

    private function getRawModsWithDetails(string $where="", $order="`name`") {
        // Get the mods.
        $mod_arrays = $this->getRawMods($where, $order);

        if (!$mod_arrays) return array();

        foreach($mod_arrays as &$mod_array) {
            $mod_array['characters'] = $this->getRawModCharacters($mod_array['id']);
            $mod_array['guides'] = $this->getRawModGuides($mod_array['id']);
            $mod_array['maps'] = $this->getRawModMaps($mod_array['id']);
        }
        //var_dump($mod_arrays);

        return $mod_arrays;
    }

    private function getRawCharactersWithDetails(string $where="") {
        // Get the characters.
        $characters = $this->getRawCharacters($where);

        // Get their skills.
        if (!$characters) return array();
        foreach($characters as &$character) {
            $character_skills = $this->getCharacterSkills($character['id']);
            $lineage_skills = $this->getLineageSkills($character['lineage_id']);
            $type_skills = $this->getTypeSkills($character['type_id']);
            $casting = $this->getCharacterCasting($character['id']);

            $skills = array();
            if ($character_skills)  foreach ($character_skills  as $skill) $skills[] = $skill;
            if ($lineage_skills)    foreach ($lineage_skills    as $skill) $skills[] = $skill;
            if ($type_skills)       foreach ($type_skills       as $skill) $skills[] = $skill;
            $character['skills'] = $skills;

            $character['casting'] = $casting;
        }

        // Done.
        return $characters;
    }

    ////////////////////////////////
    // GET - PRIVATE - RELATIONS
    ////////////////////////////////
    ///
    public function getModMapRelations(string $where="") {
        return $this->runGetQuery("SELECT * FROM r_mod_maps " . ($where ? "WHERE $where" : ""));
    }

    ////////////////////////////////
    // GET - PRIVATE - RAW
    ////////////////////////////////

    private function getRawMaps(string $where="", string $order="`name`") {
        return $this->runGetQuery("SELECT * FROM maps " . ($where ? "WHERE $where" : "") . ($order ? " ORDER BY $order " : ""));
    }

    private function getRawEvent(int $id) {
        return $this->runGetQuery("SELECT * FROM events WHERE id = $id");
    }

    private function getRawEvents(string $where="") {
        return $this->runGetQuery("SELECT * FROM events " . ($where ? "WHERE $where" : ""));
    }

    private function getRawMods(string $where="", string $order="`name`") {
        return $this->runGetQuery("SELECT * FROM mods " . ($where ? " WHERE $where " : "") . ($order ? " ORDER BY $order " : ""));
    }

    private function getRawCharacters(string $where="", string $order_by="`name`") {
        return $this->runGetQuery(static::CHARACTER_SELECT . ($where ? " \nWHERE $where" : "") . ($order_by ? " \nORDER BY $order_by" : ""));
    }

    private function getRawStrains(string $where="", string $order="`name`") {
        return $this->runGetQuery("SELECT * FROM z_strains " . ($where ? " WHERE $where " : "") . ($order ? " ORDER BY $order " : ""));
    }

    private function getRawPlayers(string $where="", string $order_by="`name`") {
        return $this->runGetQuery("SELECT * FROM players " . ($where ? " \nWHERE $where" : "") . ($order_by ? " \nORDER BY $order_by" : ""));
    }

    private function getRawSkills(string $where="", string $order_by="`name`") {
        return $this->runGetQuery("SELECT * FROM skills " . ($where ? " \nWHERE $where" : "") . ($order_by ? " \nORDER BY $order_by" : ""));
    }

    private function getRawPlots(string $where="", string $order_by="`name`") {
        return $this->runGetQuery("SELECT * FROM plots " . ($where ? " \nWHERE $where" : "") . ($order_by ? " \nORDER BY $order_by" : ""));
    }

    private function getRawMod(int $id) {
        $mod_array = $this->runGetQuery("SELECT * FROM mods WHERE id = $id");

        if (!$mod_array) return false;
        if (is_array($mod_array['0'])) return $mod_array['0'];
        return false;
    }

    private function getRawMap(int $id) {
        $array = $this->getRawMaps("id = $id");

        if (!$array) return false;
        if (is_array($array['0'])) return $array['0'];
        return false;
    }

    ////////////////////////////////////
    // GET - PRIVATE - RAW - RELATION
    ////////////////////////////////////

    private function getCharacterCasting(int $id) {
        $query = "SELECT * FROM players WHERE id IN (SELECT player_id FROM r_character_casting WHERE character_id = $id)";

        return $this->runGetQuery($query);
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

    private function addRelation(int $left_id, int $right_id, string $table, string $left_column, string $right_column) {

        $query = "INSERT INTO $table (`$left_column`, `$right_column`) VALUES ($left_id, $right_id) ON DUPLICATE KEY UPDATE id=id";
        // id=id avoids increasing auto increment. :)

        $result = $this->runUpsertQuery($query);

        var_dump($result);

        return $result;
    }

    private function addSkillRelation(int $left_id, int $right_id, string $table, string $left_column, string $right_column, int $uses) {

        $query = "INSERT INTO $table (`$left_column`, `$right_column`, `uses`) VALUES ($left_id, $right_id, $uses) ON DUPLICATE KEY UPDATE id=id";
        // id=id avoids increasing auto increment. :)

        $result = $this->runUpsertQuery($query);

        var_dump($result);

        return $result;
    }

    private function setValue(string $table, int $id, string $field, string $value) {
        // Escape values.
        $table = $this->escape($table);
        $id = $this->escape($id);
        $field = $this->escape($field);
        $value = $this->escape($value);

        // Run query.
        $query = "UPDATE $table SET `$field` = '$value' WHERE id = $id";

        $result = $this->runUpdateQuery($query);

        var_dump($result);

        return $result;
    }

    public function addGuideToMod(int $mod_id, int $guide_id) {
        return $this->addRelation($mod_id, $guide_id, "r_mod_guides", "mod_id", "guide_id");
    }

    public function addCharacterToMod(int $mod_id, int $character_id) {
        return $this->addRelation($mod_id, $character_id, "r_mod_characters", "mod_id", "character_id");
    }

    public function addMapToMod(int $mod_id, int $map_id) {
        return $this->addRelation($mod_id, $map_id, "r_mod_maps", "mod_id", "map_id");
    }

    public function addCharacterCasting(int $character_id, int $player_id) {
        return $this->addRelation($character_id, $player_id, "r_character_casting", "character_id", "player_id");
    }

    public function addCharacterSkill(int $character_id, int $skill_id, int $uses=1) {
        return $this->addSkillRelation($character_id, $skill_id, "r_character_skills", "character_id", "skill_id", $uses);
    }

    public function setCharacterDetail(int $id, string $field, string $value) {
        return $this->setValue("characters", $id, $field, $value);
    }

    public function setModDetail(int $id, string $field, string $value) {
        return $this->setValue("mods", $id, $field, $value);
    }

    public function insertCharacter(string $name, int $strain_id, int $attack , int $defense, int $successes, string $description, bool $core) {
        $query =
            "INSERT INTO characters (`name`, `strain_id`, `attack`, `defense`, `successes`, `description`, `core`) " .
                "VALUES ('$name','$strain_id','$attack','$defense','$successes','$description'," . ($core ? '1' : '0') . ")";

        return $this->runInsertQuery($query);
    }

    public function insertSkill($name, $text) {
        $query =
            "INSERT INTO skills (`name`, `text`) " .
            "VALUES ('$name', '$text')";

        return $this->runInsertQuery($query);
    }

    ////////////////////////////////
    // DELETE
    ////////////////////////////////

    public function deleteGuideFromMod(int $mod_id, int $guide_id) {
        $query = "DELETE FROM r_mod_guides WHERE mod_id = $mod_id AND guide_id = $guide_id";

        $result = $this->runDeleteQuery($query);

        return $result;
    }

    public function deleteCharacterFromMod(int $mod_id, int $character_id) {
        $query = "DELETE FROM r_mod_characters WHERE mod_id = $mod_id AND character_id = $character_id";

        $result = $this->runDeleteQuery($query);

        return $result;
    }

    public function deleteCharacterCasting(int $character_id, int $player_id) {
        $query = "DELETE FROM r_character_casting WHERE character_id = $character_id AND player_id = $player_id";

        $result = $this->runDeleteQuery($query);

        return $result;
    }

    public function deleteCharacterSkill(int $character_id, int $skill_id) {
        $query = "DELETE FROM r_character_skills WHERE character_id = $character_id AND skill_id = $skill_id";

        $result = $this->runDeleteQuery($query);

        return $result;
    }

    public function deleteMapFromMod(int $mod_id, int $map_id) {
        $query = "DELETE FROM r_mod_maps WHERE mod_id = $mod_id AND map_id = $map_id";

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

    private function runInsertQuery($query) {
        echo("<br/><br/>" . $query);

        $result = $this->mysqli->query($query);

        // Handle errors.
        if (!$result) {
            var_dump($this->mysqli);
        }

        return $result;
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

    private function runUpdateQuery($query) {
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