<?php

namespace drflvirtual\src\model;

class Skill implements NamedObject {
    protected $id;
    protected $name;
    protected $text;
    protected $uses;

    public function __construct(int $id, string $name, string $text, int $uses) {
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->uses = $uses;
    }

    public static function constructFromArray(array $skill_array) {
        return new Skill(
            $skill_array['id'],
            $skill_array['name'],
            $skill_array['text'],
            $skill_array['uses']
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
    public function getText(): string {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getUses(): int {
        return $this->uses;
    }
}