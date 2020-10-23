<?php

namespace drflvirtual\src\model;

class Faction extends StoryObject {
    protected $summary;
    protected $description;

    protected array $characters;

    /**
     * Faction constructor.
     * @param int $id
     * @param string $name
     * @param string $summary
     * @param string $description
     * @param Character[] $characters
     */
    public function __construct(int $id, string $name, string $summary, string $description, array $characters) {
        $this->id = $id;
        $this->name = $name;
        $this->summary = $summary;
        $this->description = $description;

        $this->characters = $characters;
    }

    public static function constructFromArray(array $faction) {
        // Create a holding array for characters.
        $characters = array();

        // Get the details.
        if ($faction['characters']) foreach($faction['characters'] as $char_array)  $characters[] = Character::constructFromArray($char_array);

        return new Faction(
            $faction['id'],
            $faction['name'],
            $faction['summary'],
            $faction['description'],
            $characters
        );
    }

    /**
     * @return string
     */
    public function getSummary(): string {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return Character[]
     */
    public function getCharacters() {
        return $this->characters;
    }

    public function toArray(): array {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName()
        );
    }
}