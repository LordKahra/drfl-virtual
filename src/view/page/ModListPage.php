<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Mod;

class ModListPage extends Page {
    protected $mods;

    /**
     * ModListPage constructor.
     * @param Mod[] $mods
     */
    public function __construct(array $mods) {
        parent::__construct("Mod List", "mod");

        $this->mods = $mods;
    }

    function renderBody() {
        foreach($this->mods as $mod) {
            $this->renderMod($mod);
        }
    }

    function renderMod(Mod $mod) {
        ?>
        <div data-type="mod" data-style="small" data-fold="true" data-active="false" id="mod_<?=$mod->getId();?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('mod_<?=$mod->getId();?>')">🔎</button>
                <span data-type="name"><b><a href="mod.php?id=<?=$mod->getId()?>"><?=$mod->getName()?></a></b></span>
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

                <div><?=
                    strlen($mod->getDescription()) > 300 ?
                        nl2br(substr($mod->getDescription(), 0, 300)) . "..." :
                        nl2br($mod->getDescription())
                    ?></div>
            </main>



        </div>
        <?php
    }
}