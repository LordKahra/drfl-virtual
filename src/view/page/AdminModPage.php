<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Mod;
use drflvirtual\src\model\Player;

class AdminModPage extends Page {
    protected $db;

    protected $mods;

    protected $guides;
    protected $characters;

    public function __construct(array $mods) {
        parent::__construct("Administration - Mods", "admin");

        $this->mods = $mods;

        $this->db = new EventDatabase();

        // Load data.
        $this->guides = $this->db->getGuides();
        $this->characters = $this->db->getCharacters();
    }

    function renderBody() {
        ?>
        <main>
            <header>Update Mods</header>
        <?php
        foreach ($this->getMods() as $mod) {
            $this->renderMod($mod, $this->guides);
        }
        ?>
        </main>
        <?php
    }

    /**
     * @param Mod $mod
     * @param Player[] $guides
     */
    function renderMod(Mod $mod, array $guides) {
        ?>
        <main data-type="mod" data-style="admin">
            <header>
                <div data-type="name"><?=$mod->getName()?></div>
                <div class="row">
                    <div><b>Location:</b> <?=$mod->getLocation()?></div>
                    <div><b>Where: </b><?=$mod->getHost()?></div>
                    <div><b>When: </b><?=$mod->getStartString()?></div>
                </div>
                <div class="row">
                    <div></div>
                    <div><b>Map Status:</b> <?=$mod->getMapStatus()?></div>
                    <div><b>Roll20 Status:</b> <?=$mod->getTabletopStatus()?></div>
                    <div><b>Ready:</b> <?=($mod->isReady() ? "Yes" : "No")?></div>
                </div>
            </header>

            <main>
                <header>
                </header>
                <p>Current Guides:</p>
                <ul>
                    <?php foreach ($mod->getGuides() as $guide) { ?>
                        <li>
                            <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/admin_mod_guides.php"
                                  method="POST">
                                <?= $guide->getName() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                                <input type="hidden" name="guide_id" value="<?= $guide->getId() ?>">
                                <input type="submit" value="✖"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
                <p>Current Characters:</p>
                <ul>
                    <?php foreach ($mod->getCharacters() as $character) { ?>
                        <li>
                            <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/admin_mod_characters.php"
                                  method="POST">
                                <?= $character->getName() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                                <input type="hidden" name="character_id" value="<?= $character->getId() ?>">
                                <input type="submit" value="✖"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </main>

            <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_mod_guides.php" method="POST">
                <header>Add Guides to Mod</header>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                <select id="guide_id" name="guide_id">
                    <?php foreach ($guides as $guide) { ?>
                        <option value="<?= $guide->getId() ?>"><?= $guide->getName() ?></option>
                    <?php } ?>
                </select>
                <input type="submit"/>
            </form>

            <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_mod_characters.php" method="POST">
                <header>Add Characters to Mod</header>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                <select id="guide_id" name="character_id">
                    <?php foreach ($this->characters as $character) { ?>
                        <option value="<?= $character->getId() ?>"><?= $character->getName() ?></option>
                    <?php } ?>
                </select>
                <input type="submit"/>
            </form>
        </main>
        <?php
    }

    /**
    * @return Mod[]
    */
    public  function getMods(): array {
        return $this->mods;
    }
}