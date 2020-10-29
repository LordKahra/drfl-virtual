<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Character;

class CharacterComponent extends Component {
    protected $character;

    /**
     * ModCardComponent constructor.
     * @param Character $character
     */
    public function __construct(Character $character) {
        parent::__construct();
        $this->character = $character;
    }

    function render() {
        ?>
        <article data-type="character">
            <header><span data-type="name"><a href="character.php?id=<?=$this->character->getId()?>"><?=$this->character->getName()?></a></span></header>

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

        </article>
        <?php
    }
}