<?php

namespace drflvirtual\src\model;


class Plot {
    protected $id;
    protected $name;
    protected $description;

    public function __construct(int $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public static function constructFromArray(array $array) {
        return new Plot(
            $array['id'],
            $array['name'],
            $array['description']
        );
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
    public function getDescription(): string {
        return $this->description;
    }
}