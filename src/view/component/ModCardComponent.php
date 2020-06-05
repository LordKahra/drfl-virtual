<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Mod;

class ModCardComponent extends Component {
    protected $mod;

    /**
     * ModCardComponent constructor.
     * @param Mod $
     */
    public function __construct(Mod $mod) {
        $this->mod = $mod;
    }

    function render() {
        ?>
        <div data-type="mod" data-style="card" data-fold="true" data-active="false" data-status="<?=$this->mod->getCalculatedStatus()?>" id="mod_<?=$this->mod->getId();?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('mod_<?=$this->mod->getId();?>')">🔎</button>
                <span data-type="name"><b><a href="mod.php?id=<?=$this->mod->getId()?>"><?=$this->mod->getName()?></a></b></span>
                <div data-ui="subtitle">Guide: <?=$this->mod->getGuideString();?></div>
                <div data-ui="icons">
                    <?php foreach ($this->mod->getErrors() as $error) { ?>
                        <?=$error["icon"]?>
                    <?php } ?>
                </div>
            </header>
            <main>
                <ul>
                    <?php foreach ($this->mod->getErrors() as $error) { ?>
                        <li class="error"><?=$error["message"]?></li>
                    <?php } ?>
                </ul>
                <ul>
                    <li><b>Location:</b> <?=$this->mod->getLocation()?></li>
                    <li><b>Guide:</b> <?=$this->mod->getGuideString();?></li>
                    <li><b>Where: </b><?=$this->mod->getHost()?></li>
                    <li><b>When: </b><?=$this->mod->getStartString()?></li>
                    <li><b>Map Status:</b> <?=$this->mod->getMapStatus()?></li>
                    <li><b>Tabletop Status:</b> <?=$this->mod->getTabletopStatus()?></li>
                    <li><b>Ready:</b> <?=($this->mod->isReady() ? "Yes" : "No")?></li>
                    <li></li>
                    <li></li>
                </ul>
                <div>
                    <b>Characters:</b>
                    <?php if (is_array($this->mod->getCharacters())) {
                        $character_names = array();
                        foreach ($this->mod->getCharacters() as $character) {
                            //$character_names[] = "<a href='character.php?id=" . $character['id'] . "'>" . $character['name'] . "</a>";
                            $character_names[] = $character->getName();
                        }
                        echo implode(", ", $character_names);
                    } else {
                        echo "None.";
                    } ?>
                </div>

                <div><?=
                    strlen($this->mod->getDescription()) > 300 ?
                        nl2br(substr($this->mod->getDescription(), 0, 300)) . "..." :
                        nl2br($this->mod->getDescription())
                    ?></div>
            </main>
        </div>
        <?php
    }
}