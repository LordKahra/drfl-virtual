<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Character;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;
use drflvirtual\src\view\component\CharacterCardComponent;
use drflvirtual\src\view\component\MapComponent;
use drflvirtual\src\view\component\ModComponent;

class ModListPage extends Page {
    protected $mods;

    protected $folded;

    /**
     * ModListPage constructor.
     * @param Mod[] $mods
     * @param bool $folded
     */
    public function __construct(array $mods, bool $folded=true) {
        parent::__construct("Mod List", "mod");

        $this->mods = $mods;

        $this->folded = $folded;
    }

    function renderBody() {
        foreach($this->mods as $mod) {
            $this->renderMod($mod, $this->folded);
        }
    }

    function renderMod(Mod $mod) {
        (new ModComponent($mod, $this->folded))->render();
    }

    private function renderCharacter(Character $character) {
        (new CharacterCardComponent($character))->render();
    }

    private function renderMap(Map $map) {
        (new MapComponent($map))->render();
    }


}