<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Character;
use drflvirtual\src\model\database\EventDatabase;

class AdminCharacterPage extends Page {
    protected $db;

    protected $characters;

    protected $players;

    /**
     * AdminCharacterPage constructor.
     * @param Character[] $characters
     */
    public function __construct(array $characters) {
        parent::__construct("Administration - Characters", "admin");

        global /** @var EventDatabase $db */ $db;
        $this->db = $db;

        $this->characters = $characters;
        $this->players = $this->db->getPlayers();
    }

    /**
     * @return Character[]
     */
    public function getCharacters(): array {
        return $this->characters;
    }

    function renderBody() {
        $strains = $this->db->getStrains();

        ?>
        <main>
            <header>Update Characters</header>
            <?php
            foreach ($this->getCharacters() as $character) {
                $this->renderCharacter($character);
            }
            ?>

            <div data-type="character" data-ui="card">
                <header id="add_character">Add Character</header>
                <form  data-type="character" action="<?=SITE_HOST?>/src/admin/admin_character_add.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <input type="text" name="name" placeholder="Name" required />

                    <b>Strain:</b> <select id="strain_id" name="strain_id" required>
                        <?php foreach ($strains as $strain) { ?>
                            <option value="<?= $strain->getId() ?>"><?= $strain->getName() ?></option>
                        <?php } ?>
                    </select>

                    <br/><input type="number" name="attack" placeholder="ATK" maxlength="5" size="5" required />
                    <input type="number" name="defense" placeholder="defense" required />
                    <input type="number" name="successes" placeholder="successes" required />

                    <br/><input type="text" name="description" placeholder="description" required />
                    <br/><b>Core:</b> <input type="checkbox" name="core" />
                    <input type="submit" value="Submit"/>
                </form>
            </div>

            <br/><br/>

            <div data-type="skill" data-ui="card">
                <header id="add_skill">Add Skill</header>
                <form action="<?=SITE_HOST?>/src/admin/admin_skill_add.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <b>Name:</b> <input type="text" name="name" placeholder="Name" required />
                    <b>Text:</b> <textarea name="text" rows="3" cols="50" required></textarea>
                    <input type="submit" value="Submit"/>
                </form>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
        </main>
        <?php
    }

    function renderCharacter(Character $character) {
        ?>
        <div data-type="character" data-style="admin" id="character_<?=$character->getId()?>">
            <header><?=$character->getId()?> <?=$character->getName()?></a></header>

            <div data-type="types"><?=$character->getType()?> - <?=$character->getLineage()?> - <?=$character->getStrain()?></div>
            <div data-type="description">

                <main><?=nl2br($character->getDescription())?></main>
            </div>
            <?php

            foreach($character->getSkills() as $skill) { ?>
                <div data-type="skill">
                    <header><?=$skill->getName() . ($skill->getUses() == 1 ? "" : " x {$skill->getUses()}")?></header>
                    <div><?=$skill->getText()?></div>
                </div>
            <?php } ?>

            <div data-type="attributes">
                <div>⚔️4d6<?=($character->getAttack() ? $character->getAttack() : '')?></div>
                <div>🛡️ <?=$character->getDefense()?></div>
                <div>💓️ <?=($character->getSuccesses() ? $character->getSuccesses() :'')?></div>
            </div>

            <?php $this->renderCharacterDetailLists($character); ?>
            <?php $this->renderCharacterDropdowns($character); ?>
        </div>
        <?php
    }

    function renderCharacterDetailLists(Character $character) {
        ?>
        <main>

            <div>
                <p>Current Casting:</p>
                <ul>
                    <?php foreach ($character->getCasting() as $player) { ?>
                        <li>
                            <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/admin_character_casting.php"
                                  method="POST">
                                <?= $player->getName() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="character_id" value="<?= $character->getId() ?>">
                                <input type="hidden" name="player_id" value="<?= $player->getId() ?>">
                                <input type="submit" value="✖"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div>
                <p>Current Skills:</p>
                <ul>
                    <?php foreach ($character->getSkills() as $skill) { ?>
                        <li>
                            <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/admin_character_skill.php"
                                  method="POST">
                                <?= $skill->getUses() . "x " . $skill->getName() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="character_id" value="<?= $character->getId() ?>">
                                <input type="hidden" name="skill_id" value="<?= $skill->getId() ?>">
                                <input type="submit" value="✖"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </main>
        <?php
    }

    function renderCharacterDropdowns($character) {
        // Need the skills.
        $skills = $this->db->getSkills();

        ?>
        <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_character_casting.php" method="POST">
            <header>Add Players to Character</header>
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="character_id" value="<?= $character->getId() ?>">
            <select id="player_id" name="player_id">
                <?php foreach ($this->players as $player) { ?>
                    <option value="<?= $player->getId() ?>"><?= $player->getName() ?></option>
                <?php } ?>
            </select>
            <input type="submit"/>
        </form>

        <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_character_skill.php" method="POST">
            <header>Add Skills to Character</header>
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="character_id" value="<?= $character->getId() ?>">
            <select id="skill_id" name="skill_id">
                <?php foreach ($skills as $skill) { ?>
                    <option value="<?= $skill->getId() ?>"><?= $skill->getName() ?></option>
                <?php } ?>
            </select>
            <br/><b>Uses:</b> <input type="number" name="uses" value="1"/>
            <input type="submit"/>
        </form>
        <?php
    }
}