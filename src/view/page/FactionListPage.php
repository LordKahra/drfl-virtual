<?php


namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Faction;
use drflvirtual\src\view\component\FactionComponent;

class FactionListPage extends Page {
    protected array $factions;

    /**
     * FactionListPage constructor.
     * @param Faction[] $factions
     */
    public function __construct(array $factions) {
        parent::__construct("Faction List", "faction", "guide");

        $this->factions = $factions;
    }

    function renderBody() {
        foreach ($this->factions as $faction) {
            $this->renderFaction($faction);
        }
    }

    function renderFaction(Faction $faction) {
        (new FactionComponent($faction))->render();
    }
}