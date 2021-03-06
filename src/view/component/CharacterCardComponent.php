<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Character;

class CharacterCardComponent extends Component {
    protected Character $character;

    /**
     * ModCardComponent constructor.
     * @param Character $character
     * @param string $tag
     * @param bool $isFoldable
     * @param bool $isFolded
     */
    public function __construct(Character $character, string $tagPrefix="", bool $isFoldable=true, bool $isFolded=false) {
        parent::__construct($tagPrefix . "character_" . $character->getId());
        $this->character = $character;
        $this->isFoldable = $isFoldable;
        $this->isFolded = $isFolded;
        //var_dump($this->tag);
    }

    function render() {
        ?>
        <figure data-type="character" data-fold="true" data-active="<?=($this->isActiveString())?>" id="<?=$this->getTag();?>">
            <header>
                <button data-ui="button" href="#" <?php if ($this->isFoldable()) { ?> onclick="toggleById('<?=$this->getTag();?>')" <?php } ?>>🔎</button>
                <span data-type="name"><a href="character.php?id=<?=$this->character->getId()?>"><?=$this->character->getName()?></a></span>
            </header>

            <main>

            <div data-type="types"><?=$this->character->getType()?> - <?=$this->character->getLineage()?> - <?=$this->character->getStrain()?></div>
            <div data-type="description">

                <main>
                    <?=nl2br($this->character->getDescription())?>
                    <?php if (file_exists(SITE_ROOT . "/res/images/characters/" . $this->character->getId() . ".png" )) { ?>
                          <div><img data-type="character" src="<?=SITE_HOST;?>/res/images/characters/<?=$this->character->getId()?>.png" /></div>
                    <?php } ?>
                </main>
            </div>
            <?php
            foreach($this->character->getSkills() as $skill) { ?>
                <div data-type="skill">
                    <header><?=$skill->getName() . ($skill->getUses() == 1 ? "" : " x {$skill->getUses()}")?></header>
                    <div><?=$skill->getText()?></div>
                </div>
            <?php } ?>
            <div data-type="attributes">
                <div>⚔️4d6<?=($this->character->getAttack() ? $this->character->getAttack() : '')?></div>
                <div>🛡️ <?=$this->character->getDefense()?></div>
                <div>💓️ <?=($this->character->getSuccesses() ? $this->character->getSuccesses() : '')?></div>
            </div>

            </main>

        </figure>
        <?php
    }
}