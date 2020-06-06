<?php
/**
 * Created by PhpStorm.
 * User: Lord Kahra
 * Date: 6/5/2020
 * Time: 22:52
 */

namespace drflvirtual\src\model;


class Figure {
    protected $id;
    protected $name;
    protected $description;

    public function __construct(int $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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