<?php

namespace drflvirtual\src\model;


class Plot {
    protected $description;
    protected $mods;

    /**
     * Plot constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param Mod[] $mods
     */
    public function __construct(int $id, string $name, string $description, array $mods) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        $this->mods = $mods;
    }

    public static function constructFromArray(array $array) {
        // Get the mods.
        $mods = array();
        if (array_key_exists('mods', $array)) foreach($array['mods'] as $mod_array) {
            $mods[$mod_array['id']] = Mod::constructFromArray($mod_array);
        }

        return new Plot(
            $array['id'],
            $array['name'],
            $array['description'],
            $mods
        );
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return Mod[]
     */
    public function getMods() {
        return $this->mods;
    }

    public function toArray(): array {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName()
        );
    }
}