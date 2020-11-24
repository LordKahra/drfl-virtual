<?php


namespace drflvirtual\src\view\component;


use drflvirtual\src\model\Faction;
use drflvirtual\src\view\page\Page;

class FactionComponent extends \Component {
    protected $faction;

    public function __construct(Faction $faction) {
        $this->faction = $faction;
    }

    function render() {
        ?>
        <article data-type="faction" data-ui="article">
            <header>
                <div data-type="name"><a href="faction.php?id=<?=$this->faction->getId()?>"><?=$this->faction->getName();?></a></div>
                <div data-ui="subtitle">SUBTITLE</div>
            </header>
            <main>
                <div data-type="summary"><?=$this->faction->getSummary();?></div>
                <div class="row">
                    <div>
                        <b>Characters:</b>
                        <?php
                        $character_names = array();
                        foreach ($this->faction->getCharacters() as $character) {
                            $character_names[] = $character->getName();
                        }
                        echo implode(", ", $character_names);
                        ?>
                    </div>
                </div>
                <div><?=Page::formatText($this->faction->getDescription());?></div>
                <?php
                    foreach ($this->faction->getCharacters() as $character)
                        (new CharacterCardComponent($character))->render();
                ?>
            </main>
        </article>
        <?php
    }
}