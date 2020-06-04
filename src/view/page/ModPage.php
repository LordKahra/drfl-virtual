<?php

namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Character;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;
use PlayerNotFoundException;

class ModPage extends Page {
    protected $db;

    protected $mod;

    public function __construct(string $title, Mod $mod) {
        parent::__construct($title, "mod");
        $this->db = new EventDatabase();
        $this->mod = $mod;
    }

    /**
     * @return Mod
     */
    public function getMod(): Mod {
        return $this->mod;
    }

    function renderBody() {
        ?>
        <div data-type="mod">
            <header>
                <div data-type="name"><a href="../../mod.php?id=<?=$this->getMod()->getId()?>"><?=$this->getMod()->getName()?></a></div>
                <div class="row">
                    <div><b>Location:</b> <?=$this->getMod()->getLocation()?></div>
                    <div><b>Where: </b><?=$this->getMod()->getHost()?></div>
                    <div><b>When: </b><?=$this->getMod()->getStartString()?></div>
                </div>
                <div class="row">
                    <div></div>
                    <div><b>Map Status:</b> <?=$this->getMod()->getMapStatus()?></div>
                    <div><b>Tabletop Status:</b> <?=$this->getMod()->getTabletopStatus()?></div>
                    <div><b>Ready:</b> <?=($this->getMod()->isReady() ? "Yes" : "No")?></div>
                </div>
            </header>
            <div><?=nl2br($this->getMod()->getDescription())?></div>
            <div><?php foreach($this->getMod()->getMaps() as $map) $this->renderMap($map); ?></div>
            <?php
            if($this->getMod()->getCharacters()) {
            ?><div data-type="characters">
                <header>Characters</header><?php
                foreach ($this->getMod()->getCharacters() as $character) $this->renderCharacter($character);
                }
                ?></div>
        </div>
        <?php
    }

    function renderMap(Map $map) {
        $player = false;
        try {
            $player = $this->db->getPlayer($map->getCreatorId());
        } catch (PlayerNotFoundException $e) {
            // Do nothing.
        }

        ?>
        <div data-style="map" data-fold="true" data-active="false" id="map_<?=$map->getId()?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('map_<?=$map->getId();?>')">🔎</button>
                <span data-type="name"><b><a href="map.php?id=<?=$map->getId()?>"><?=$map->getId()?> - <?=$map->getName()?></a> ---  <?=$map->getStatus()?></b></span>
                <p>Creator: #<?=$map->getCreatorId()?><?=($player ? " - {$player->getName()}" : "")?></p>
                <p><?=$map->getDescription()?></p>
            </header>
            <main data-style="image">
                <img src="<?=SITE_HOST . "/res/images/maps/{$map->getId()}.png"?>">
            </main>
        </div>
    <?php }

    function renderCharacter(Character $character) {
        ?>
        <div data-type="character">
            <header><a href="character.php?id=<?=$character->getId()?>"><?=$character->getName()?></a></header>

            <div data-type="types"><?=$character->getType()?> - <?=$character->getLineage()?> - <?=$character->getStrain()?></div>
            <div data-type="description">

                <main><?=nl2br($character->getDescription())?></main>
            </div>
            <?php
            if(is_array($character->getSkills()))
                foreach($character->getSkills() as $skill) { ?>
                    <div data-type="skill">
                        <header><?=$skill->getName() . ($skill->getUses() == 1 ? "" : " x {$skill->getUses()}")?></header>
                        <div><?=$skill->getText()?></div>
                    </div>
                <?php } ?>
            <div data-type="attributes">
                <div>⚔️4d6<?=($character->getAttack()?$character->getAttack():'')?></div>
                <div>🛡️ <?=$character->getDefense()?></div>
                <div>💓️ <?=($character->getSuccesses()?$character->getSuccesses():'')?></div>
            </div>

        </div>
        <?php
    }
}