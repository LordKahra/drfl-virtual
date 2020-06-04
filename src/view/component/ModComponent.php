<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Character;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;

class ModComponent extends Component {
    protected $mod;

    protected $folded;

    public function __construct(Mod $mod, $folded=true) {
        $this->mod = $mod;
        $this->folded = $folded;
    }

    function render() {
        ?>
        <div data-type="mod" data-ui="list" data-fold="true" data-active="<?=(!$this->folded ? "true" : "false")?>" id="mod_<?=$this->mod->getId();?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('mod_<?=$this->mod->getId();?>')">🔎</button>
                <span data-type="name">
                    <b><a href="mod.php?id=<?=$this->mod->getId()?>"><?=$this->mod->getName()?></a></b>
                </span>

                <div data-ui="subtitle"><b>Where: </b><?=$this->mod->getHost()?> - <?=$this->mod->getSpace()?></div>
                <div data-ui="subtitle">
                    When: <?=$this->mod->getStartFormatted('F - l Hi')?>
                </div>
            </header>
            <main>
                <div class="row">
                    <div><b>Location:</b> <?=$this->mod->getLocation()?></div>
                    <div><b>Where: </b><?=$this->mod->getHost()?></div>
                    <div><b>When: </b><?=$this->mod->getStartString()?></div>
                </div>
                <div class="row">
                    <div>
                        <b>Characters:</b>
                        <?php
                        $character_names = array();
                        foreach ($this->mod->getCharacters() as $character) {
                            $character_names[] = $character->getName();
                        }
                        echo implode(", ", $character_names);
                        ?>
                    </div>
                    <div><b>Map Status:</b> <?=$this->mod->getMapStatus()?></div>
                    <div><b>Tabletop Status:</b> <?=$this->mod->getTabletopStatus()?></div>
                    <div><b>Ready:</b> <?=($this->mod->isReady() ? "Yes" : "No")?></div>
                </div>

                <div><?=nl2br($this->mod->getDescription())?></div>

                <div><?php foreach($this->mod->getMaps() as $map) $this->renderMap($map); ?></div>

                <?php
                if($this->mod->getCharacters()) { ?>
                    <div data-type="characters">
                        <header>Characters</header>
                        <?php foreach ($this->mod->getCharacters() as $character) $this->renderCharacter($character); ?>
                    </div>
                <?php } ?>

            </main>



        </div>
        <?php
    }

    private function renderCharacter(Character $character) {
        (new CharacterCardComponent($character))->render();
    }

    private function renderMap(Map $map) {
        (new MapComponent($map))->render();
    }
}