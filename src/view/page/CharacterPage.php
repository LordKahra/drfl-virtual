<?php

namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Character;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Mod;
use drflvirtual\src\view\component\CharacterComponent;

class CharacterPage extends Page {
    protected $db;

    protected $character;

    public function __construct(Character $character) {
        parent::__construct($character->getName() . " - DRFL", "character", "guide");
        global /** @var EventDatabase $db */ $db;
        $this->db = $db;
        $this->character = $character;
    }

    /**
     * @return Character
     */
    public function getCharacter(): Character {
        return $this->character;
    }

    function renderBody() {
        (new CharacterComponent($this->character))->render();
    }
}