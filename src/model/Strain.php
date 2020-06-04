<?php

namespace drflvirtual\src\model;

class Strain implements NamedObject {
    protected $id;
    protected $name;
    protected $lineage_id;

    public function __construct(int $id, string $name, int $lineage_id) {
        $this->id = $id;
        $this->name = $name;
        $this->lineage_id = $lineage_id;
    }

    public static function constructFromArray(array $strain) {
        return new Strain($strain['id'], $strain['name'], $strain['lineage_id']);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLineageId(): int {
        return $this->lineage_id;
    }
}