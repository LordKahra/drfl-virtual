<?php
/**
 * Created by PhpStorm.
 * User: Lord Kahra
 * Date: 6/5/2020
 * Time: 22:52
 */

namespace drflvirtual\src\model;


class Figure {
    protected $description;

    public function __construct(int $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    public function toArray(): array {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName()
        );
    }
}