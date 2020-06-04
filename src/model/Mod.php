<?php

namespace drflvirtual\src\model;

use DateTime;

class Mod implements NamedObject {
    protected $id;
    protected $name;
    protected $host;
    protected $start;
    protected $location;
    protected $description;
    protected $map_status;
    protected $tabletop_status;
    protected $is_ready;
    protected $is_statted;
    protected $event_id;

    protected $characters = array();
    protected $guides = array();
    protected $maps = array();

    protected $calculatedStatus;
    protected $errors = false;

    /**
     * Mod constructor.
     * @param int $id
     * @param string $name
     * @param string $host
     * @param string $start
     * @param string $location
     * @param string $description
     * @param Character[] $characters
     * @param Player[] $guides
     */
    public function __construct(
        int $id,
        string $name,
        string $host,
        string $space,
        string $start,
        string $location,
        string $description,
        string $map_status,
        string $tabletop_status,
        bool $is_ready,
        bool $is_statted,
        int $event_id,
        array $characters,
        array $guides,
        array $maps
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->space = $space;
        $this->start = new DateTime($start);
        $this->location = $location;
        $this->description = $description;
        $this->map_status = $map_status;
        $this->tabletop_status = $tabletop_status;
        $this->is_ready = $is_ready;
        $this->is_statted = $is_statted;
        $this->event_id = $event_id;

        foreach ($characters as $character) {
            $this->characters[$character->getId()] = $character;
        }

        foreach ($guides as $guide) {
            $this->guides[$guide->getId()] = $guide;
        }

        foreach ($maps as $map) {
            $this->maps[$map->getId()] = $map;
        }

        $this->calculatedStatus = false;
    }

    public static function constructFromArray(array $mod) {
        // Create a holding array for characters.
        $characters = array();
        $guides = array();
        $maps = array();

        // Get the details.
        if ($mod['characters']) foreach($mod['characters'] as $char_array)  $characters[] = Character::constructFromArray($char_array);
        if ($mod['guides'])     foreach($mod['guides'] as $guide_array)     $guides[] = Player::constructFromArray($guide_array);
        if ($mod['maps'])       foreach($mod['maps'] as $map_array)         $maps[] = Map::constructFromArray($map_array);

        return new Mod(
            $mod['id'],
            $mod['name'],
            $mod['host'],
            $mod['space'],
            $mod['start'],
            $mod['location'],
            $mod['description'],
            $mod['map_status'] ? $mod['map_status'] : "",
            $mod['tabletop_status'] ? $mod['tabletop_status'] : "",
            $mod['is_ready'],
            $mod['is_statted'],
            $mod['event_id'],
            $characters,
            $guides,
            $maps
        );
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getSpace(): string {
        return strval($this->space);
    }

    /**
     * @return DateTime
     */
    public function getStart() {
        return $this->start;
    }

    public function getStartString() {
        return $this->start->format('Y-m-d Hi');
    }

    public function getStartTimestamp() {
        return $this->start->getTimestamp();
    }

    /**
     * @return mixed
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getMapStatus(): string {
        return $this->map_status;
    }

    /**
     * @return string
     */
    public function getTabletopStatus(): string {
        return $this->tabletop_status;
    }

    /**
     * @return bool
     */
    public function isReady(): bool {
        return $this->is_ready;
    }

    /**
     * @return bool
     */
    public function isStatted(): bool {
        return $this->is_statted;
    }

    public function isTabletop(): bool {
        switch(strtolower($this->getHost())) {
            case "roll20":
            case "astral":
                return true;
            case "discord":
            default:
                return false;
        }
    }

    public function hasGuides() : bool {
        return $this->guides ? true : false;
    }

    /**
     * @return int
     */
    public function getEventId(): int {
        return $this->event_id;
    }

    /**
     * @return Character[]
     */
    public function getCharacters() : array {
        return ($this->characters ? $this->characters : array());
    }

    /**
     * @return Player[]
     */
    public function getGuides(): array {
        return $this->guides;
    }

    public function getGuideString(string $delimiter=", ") : string {
        if (!$this->getGuides()) return "";

        foreach($this->getGuides() as $guide) {
            $guide_names[] = $guide->getName();
        }
        return implode($delimiter, $guide_names);
    }

    /**
     * @return Map[]
     */
    public function getMaps(): array {
        return $this->maps;
    }

    public function getCalculatedStatus(): int {
        if (!$this->calculatedStatus) $this->calculatedStatus = $this->calculateStatus();

        return $this->calculatedStatus;
    }

    public function getErrors() : array {
        if($this->errors === false) $this->errors = $this->calculateErrors();

        return $this->errors;
    }

    private function validateDescription() : bool {
        if (!$this->getDescription() || strpos($this->getDescription(), static::INCOMPLETE_MARKER) !== false) {
            return false;
        }
        return true;
    }

    private function validateEvent() : bool {
        return $this->getEventId() > 0;
    }

    private function validateName() : bool {
        return !(!$this->getName() || strpos($this->getName(), static::INCOMPLETE_MARKER) !== false);
    }

    private function validateLocation() : bool {
        return !(!$this->getLocation() || strpos($this->getLocation(), static::INCOMPLETE_MARKER) !== false);
    }

    private function validateHost() : bool {
        return !(!$this->getHost() || strpos($this->getHost(), static::INCOMPLETE_MARKER) !== false);
    }

    private function validateMapStatus() : bool {
        return !(!$this->getMapStatus() || strpos($this->getMapStatus(), static::INCOMPLETE_MARKER) !== false);
    }

    private function validateStart() : bool {
        return $this->getStart() > new DateTime('2020-02-01');
    }

    private function validateGuides() : bool {
        return $this->hasGuides();
    }

    private function calculateErrors() : array {
        $errors = array();
        if (!$this->validateDescription()) $errors[] = Mod::ERRORS["DESC_INCOMPLETE"];
        if (!$this->validateEvent()) $errors[] = Mod::ERRORS["NO_EVENT"];
        if (!$this->validateName()) $errors[] = Mod::ERRORS["NO_NAME"];
        if (!$this->validateLocation()) $errors[] = Mod::ERRORS["NO_LOCATION"];
        if (!$this->validateHost()) $errors[] = Mod::ERRORS["NO_HOST"];
        if (!$this->validateGuides()) $errors[] = Mod::ERRORS["NO_GUIDES"];
        if (!$this->validateMapStatus()) $errors[] = Mod::ERRORS["NO_MAP_STATUS"];

        // More complex checks.
        switch(strtolower($this->getHost())) {
            case "roll20":
            case "astral":
            case "discord":
            case "skipped":
                // Do nothing.
                break;
            default:
                $errors[] = Mod::ERRORS["INVALID_HOST"];
                break;
        }

        if (!$this->validateStart()) $errors[] = Mod::ERRORS["NO_START"];

        // Check map status.
        switch(strtolower($this->getMapStatus())) {
            case "uploaded":
                break;
            case "none":
                // Validate against host.
                if (strtolower($this->getHost()) == "discord") {
                    // Done.
                    break;
                } else {
                    // Host is invalid for no map.
                    $errors[] = Mod::ERRORS["INVALID_MAP_STATUS"];
                    break;
                }
            default:
                // If it has a different status, the map is incomplete.
                $errors[] = Mod::ERRORS["NO_MAP"];
                break;
        }

        // Check if it's statted.
        if (!$this->isStatted()) $errors[] = Mod::ERRORS["NO_STATS"];

        // Check Tabletop Status.
        switch(strtolower($this->getTabletopStatus())) {
            case "ready":
                break;
            case "none":
                if (strtolower($this->getHost()) == "roll20" || strtolower($this->getHost()) == "astral") {
                    $errors[] = Mod::ERRORS["INVALID_TABLETOP_STATUS"];
                } else {
                    // This is fine.
                    break;
                }
            default:
                // The tabletop is incomplete.
                $errors[] = Mod::ERRORS["TABLETOP_INCOMPLETE"];
        }

        // Last check.
        if (!$this->isReady()) $errors[] = Mod::ERRORS["VERIFICATION"];

        return $errors;
    }

    private function calculateStatus() : int {
        ////////////////////////////////
        // WRITING /////////////////////
        ////////////////////////////////
        if (!$this->validateDescription()) return static::STATUS_BEING_WRITTEN;

        ////////////////////////////////
        // DETAILING ///////////////////
        ////////////////////////////////

        // Check for any fields which are standard necessary details.

        // This must have an event.
        if (!$this->validateEvent()) return static::STATUS_NEEDS_DETAILS;

        if (!$this->validateName()) {
            // Incomplete name.
            return static::STATUS_NEEDS_DETAILS;
        }

        if (!$this->getLocation() || strpos($this->getLocation(), static::INCOMPLETE_MARKER) !== false) {
            // Incomplete location.
            return static::STATUS_NEEDS_DETAILS;
        }

        if (!$this->getHost() || strpos($this->getHost(), static::INCOMPLETE_MARKER) !== false) {
            // Incomplete host.
            return static::STATUS_NEEDS_DETAILS;
        }

        if (!$this->getMapStatus() || strpos($this->getMapStatus(), static::INCOMPLETE_MARKER) !== false) {
            // Incomplete map status.
            return static::STATUS_NEEDS_DETAILS;
        }

        // If the host is Roll20 or Astral, check for maps.
        switch(strtolower($this->getHost())) {
            case "roll20":
            case "astral":
                // These will be handled when map checking.
            case "discord":
            case "skipped":
                // Do nothing.
                break;
            default:
                return static::STATUS_NEEDS_DETAILS;
        }

        ////////////////////////////////
        // SCHEDULING //////////////////
        ////////////////////////////////

        if ($this->getStart() < new DateTime('2020-02-01')) {
            // The start date is prior to the creation of the site. GET A REAL DATE.
            return static::STATUS_UNSCHEDULED;
        }

        if (!$this->validateGuides()) {
            // This can't be scheduled without guides.
            return static::STATUS_UNSCHEDULED;
        }

        ////////////////////////////////
        // MAPPING /////////////////////
        ////////////////////////////////

        // Check map status.
        switch(strtolower($this->getMapStatus())) {
            case "uploaded":
                // Done!
                break;
            case "none":
                // Validate against host.
                if (strtolower($this->getHost()) == "discord") {
                    // Done.
                    break;
                } else {
                    // Host is invalid for no map.
                }
            default:
                // If it has a different status, the map is incomplete.
                return static::STATUS_MAP_INCOMPLETE;
        }

        ////////////////////////////////
        // STATTING ////////////////////
        ////////////////////////////////

        // Check if it's statted.
        if (!$this->isStatted()) {
            return static::STATUS_NEEDS_STATS;
        }

        ////////////////////////////////
        // TABLETOP ////////////////////
        ////////////////////////////////

        // Check Tabletop Status.
        switch(strtolower($this->getTabletopStatus())) {
            case "ready":
                break;
            case "none":
                if (strtolower($this->getHost()) == "roll20"|| strtolower($this->getHost()) == "astral") {
                    return static::STATUS_TABLETOP_INCOMPLETE;
                }
            default:
                // The tabletop is incomplete.
                return static::STATUS_TABLETOP_INCOMPLETE;
        }

        ////////////////////////////////
        // VERIFICATION ////////////////
        ////////////////////////////////

        if (!$this->isReady()) {
            return static::STATUS_VERIFICATION;
        }

        ////////////////////////////////
        // READY ///////////////////////
        ////////////////////////////////

        // WE'RE READY!!!
        return static::STATUS_READY;

        // Host: Discord || Roll20 || Skipped || Astral
        // Map Status: UPLOADED || NULL
        // TabletopStatus: READY || NONE
    }

    const INCOMPLETE_MARKER = "???";

    const STATUS_BEING_WRITTEN = 10;
    const STATUS_NEEDS_DETAILS = 20;
    const STATUS_UNSCHEDULED = 30;
    const STATUS_MAP_INCOMPLETE = 50;
    const STATUS_NEEDS_STATS = 60;
    const STATUS_TABLETOP_INCOMPLETE = 80;
    const STATUS_VERIFICATION = 90;
    const STATUS_READY = 100;

    const STATUS = array(
        "BEING_WRITTEN" => Mod::STATUS_BEING_WRITTEN,
        "NEEDS_DETAILS" => Mod::STATUS_NEEDS_DETAILS,
        "UNSCHEDULED" => Mod::STATUS_UNSCHEDULED,
        "MAP_INCOMPLETE" => Mod::STATUS_MAP_INCOMPLETE,
        "NEEDS_STATS" => Mod::STATUS_NEEDS_STATS,
        "TABLETOP_INCOMPLETE" => Mod::STATUS_TABLETOP_INCOMPLETE,
        "VERIFICATION" => Mod::STATUS_VERIFICATION,
        "READY" => Mod::STATUS_READY,
    );

    const ERRORS = array(
        "DESC_INCOMPLETE" => array(
            "icon" => "📜",
            "message" => "The description is incomplete."
        ),
        "NO_EVENT" => array(
            "icon" => "📆",
            "message" => "This mod doesn't have an event."
        ),
        "NO_NAME" => array(
            "icon" => "✏️",
            "message" => "This mod doesn't have a name."
        ),
        "NO_LOCATION" => array(
            "icon" => "🏝️",
            "message" => "This mod doesn't have a location."
        ),
        "NO_HOST" => array(
            "icon" => "🖥️",
            "message" => "This mod doesn't have a host."
        ),
        "NO_GUIDES" => array(
            "icon" => "😮",
            "message" => "This mod doesn't have any guides."
        ),
        "NO_MAP_STATUS" => array(
            "icon" => "🗺️",
            "message" => "This mod doesn't have a map status."
        ),
        "INVALID_HOST" => array(
            "icon" => "🖥️",
            "message" => "The host is invalid."
        ),
        "NO_START" => array(
            "icon" => "🕛",
            "message" => "This mod hasn't been scheduled."
        ),
        "INVALID_MAP_STATUS" => array(
            "icon" => "⚠",
            "message" => "A tabletop mod can't have no map."
        ),
        "NO_MAP" => array(
            "icon" => "🗺️",
            "message" => "The map isn't ready yet."
        ),
        "NO_STATS" => array(
            "icon" => "⚔️",
            "message" => "This mod doesn't have stats."
        ),
        "INVALID_TABLETOP_STATUS" => array(
            "icon" => "⚠",
            "message" => "This mod can't be run without the virtual tabletop ready (mismatched state)."
        ),
        "TABLETOP_INCOMPLETE" => array(
            "icon" => "👥",
            "message" => "This mod's virtual tabletop isn't ready."
        ),
        "VERIFICATION" => array(
            "icon" => "",
            "message" => "This mod still needs final verification/testing."
        ),
    );

}