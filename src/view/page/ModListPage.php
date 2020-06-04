<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Mod;

class ModListPage extends Page {
    protected $mods;

    protected $unfolded;

    /**
     * ModListPage constructor.
     * @param Mod[] $mods
     */
    public function __construct(array $mods, bool $unfolded=false) {
        parent::__construct("Mod List", "mod");

        $this->mods = $mods;

        $this->unfolded = $unfolded;
    }

    function renderBody() {
        foreach($this->mods as $mod) {
            $this->renderMod($mod, $this->unfolded);
        }
    }

    function renderMod(Mod $mod, bool $active=false) {
        ?>
        <div data-type="mod" data-ui="list" data-fold="true" data-active="<?=($active ? "true" : "false")?>" id="mod_<?=$mod->getId();?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('mod_<?=$mod->getId();?>')">🔎</button>
                <span data-type="name">
                    <b><a href="mod.php?id=<?=$mod->getId()?>"><?=$mod->getName()?></a></b>
                </span>

                <div data-ui="subtitle"><b>Where: </b><?=$mod->getHost()?> - <?=$mod->getSpace()?></div>
                <div data-ui="subtitle">
                    When: <?=$mod->getStartFormatted('F - l Hi')?>
                </div>
            </header>
            <main>
                <div class="row">
                    <div><b>Location:</b> <?=$mod->getLocation()?></div>
                    <div><b>Where: </b><?=$mod->getHost()?></div>
                    <div><b>When: </b><?=$mod->getStartString()?></div>
                </div>
                <div class="row">
                    <div>
                        <b>Characters:</b>
                        <?php
                            $character_names = array();
                            foreach ($mod->getCharacters() as $character) {
                                $character_names[] = $character->getName();
                            }
                            echo implode(", ", $character_names);
                         ?>
                    </div>
                    <div><b>Map Status:</b> <?=$mod->getMapStatus()?></div>
                    <div><b>Tabletop Status:</b> <?=$mod->getTabletopStatus()?></div>
                    <div><b>Ready:</b> <?=($mod->isReady() ? "Yes" : "No")?></div>
                </div>

                <div><?=nl2br($mod->getDescription())?></div>
            </main>



        </div>
        <?php
    }
}