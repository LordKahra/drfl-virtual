<?php

use drflvirtual\src\model\Skill;

class Character {
    private $id;
    private $name;
    private $strain_id;
    private $attack;
    private $defense;
    private $successes;
    private $description;
    private $core;

    private $skills = array();

    /**
     * Character constructor.
     * @param int $id
     * @param string $name
     * @param int $strain_id
     * @param int $attack
     * @param int $defense
     * @param int $successes
     * @param string $description
     * @param bool $core
     * @param string $type
     * @param string $lineage
     * @param string $strain
     * @param Skill[] $skills
     */
    public function __construct(
        int $id,
        string $name,
        int $strain_id,
        int $attack,
        int $defense,
        int $successes,
        string $description,
        bool $core,
        string $type,
        string $lineage,
        string $strain,
        array $skills
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->strain_id = $strain_id;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->successes = $successes;
        $this->description = $description;
        $this->core = $core;
        $this->type = $type;
        $this->lineage = $lineage;
        $this->strain = $strain;

        foreach ($skills as $skill) {
            $this->skills[$skill->getId()] = $skill;
        }
    }

    public static function constructFromArray(array $character) {
        // Create array for skills.
        $skills = array();

        foreach ($character['skills'] as $skill_array) {
            $skills[] = Skill::constructFromArray($skill_array);
        }

        return new Character(
            $character['id'],
            $character['name'],
            $character['strain_id'],
            $character['attack'],
            $character['defense'],
            $character['successes'],
            $character['description'],
            $character['core'],
            $character['type'],
            $character['lineage'],
            $character['strain'],
            $skills
        );
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getStrainId() {
        return $this->strain_id;
    }

    public function getAttack() {
        return $this->attack;
    }

    public function getDefense() {
        return $this->defense;
    }

    /**
     * @return int
     */
    public function getSuccesses(): int {
        return $this->successes;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isCore(): bool {
        return $this->core;
    }

    /**
     * @return Skill[]
     */
    public function getSkills():array {
        return $this->skills;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLineage(): string {
        return $this->lineage;
    }

    /**
     * @return string
     */
    public function getStrain(): string {
        return $this->strain;
    }
}