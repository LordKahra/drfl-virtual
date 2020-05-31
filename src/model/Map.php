<?php

namespace drflvirtual\src\model;


class Map {
    protected $id;
    protected $name;
    protected $status;
    protected $creator_id;
    protected $description;

    public function __construct(int $id, string $name, string $status, int $creator_id, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->creator_id = $creator_id;
        $this->description = $description;
    }

    public static function constructFromArray(array $array) {
        // Get variables.
        $id = $array['id'];
        $name = $array['name'];
        $status = $array['status'];
        $creator_id = $array['creator_id'];
        $description = array_key_exists('description', $array) && $array['description'] ? $array['description'] : "";
        return new Map($id, $name, $status, $creator_id, $description);
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
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

}