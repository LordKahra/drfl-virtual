<?php

namespace drflvirtual\src\model;


use drflvirtual\src\model\database\EventDatabase;
use PlayerNotFoundException;

class Map implements NamedObject {
    protected $id;
    protected $name;
    protected $status;
    protected $creator_id;
    protected $description;

    protected $creator;

    public function __construct(int $id, string $name, string $status, int $creator_id, string $description, Player $creator=null) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->creator_id = $creator_id;
        $this->description = $description;

        $this->creator = $creator;
    }

    public static function constructFromArray(array $array) {
        // Get variables.
        $id = $array['id'];
        $name = $array['name'];
        $status = $array['status'];
        $creator_id = $array['creator_id'];
        $description = array_key_exists('description', $array) && $array['description'] ? $array['description'] : "";

        global /** @var EventDatabase $db */ $db;
        $player = null;
        try {
            $player = $db->getPlayer($array['creator_id']);
        } catch (PlayerNotFoundException $e) {
            // Do nothing, null is fine.
        }

        return new Map($id, $name, $status, $creator_id, $description, $player);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCreatorId(): int {
        return $this->creator_id;
    }

    /**
     * @return Player|null
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

}