<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Faction;
use drflvirtual\src\view\component\FactionComponent;

class FactionPage extends Page {
    protected $faction;

    public function __construct(string $title, Faction $faction) {
        parent::__construct($title, "event", "guide");
        $this->faction = $faction;
    }
    protected $db;

    /**
     * @return Faction
     */
    public function getFaction(): Faction {
        return $this->faction;
    }


    function renderBody() {
        // TODO: Implement renderBody() method.
        (new FactionComponent($this->faction))->render();
    }
}