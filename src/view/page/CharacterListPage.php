<?php
/**
 * Created by PhpStorm.
 * User: Lord Kahra
 * Date: 6/3/2020
 * Time: 23:50
 */

namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Character;
use drflvirtual\src\view\component\CharacterCardComponent;

class CharacterListPage extends Page {
    protected $characters;

    public function __construct(array $characters) {
        parent::__construct("Character List", "character");

        $this->characters = $characters;
    }

    function renderBody() {
        foreach($this->characters as $characters) {
            $this->renderCharacter($characters);
        }
    }

    function renderCharacter(Character $character) {
        (new CharacterCardComponent($character))->render();
    }
}