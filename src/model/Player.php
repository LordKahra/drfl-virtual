<?php

namespace drflvirtual\src\model;


class Player implements NamedObject {
    protected $id;
    protected $name;
    protected $is_guide;

    public function __construct(int $id, string $name, bool $is_guide) {
        $this->id = $id;
        $this->name = $name;
        $this->is_guide = $is_guide;
    }

    public static function constructFromArray(array $player_array) {
        return new Player(
            $player_array['id'],
            $player_array['name'],
            $player_array['is_guide']
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
     * @return bool
     */
    public function isGuide(): bool {
        return $this->is_guide;
    }

}