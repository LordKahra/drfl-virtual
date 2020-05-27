<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Mod;

class AdminModPage extends Page {
    protected $db;

    protected $mods;

    public function __construct(array $mods) {
        parent::__construct("Administration - Mods", "admin");

        $this->mods = $mods;

        $this->db = new EventDatabase();
    }

    function renderBody() {
        $guides = $this->db->getGuides();

        ?>
        <main>
            <header>Assign Guides to Mods</header>
        <?php
        foreach ($this->getMods() as $mod) {
            ?>
            <content data-type="mod">
                <header><?=$mod->getName()?></header>
                <main>
                    <header></header>
                    <p>Current Guides:</p>
                    <ul>
                        <?php foreach ($mod->getGuides() as $guide) { ?>
                            <li>
                                <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/add_guide_to_mod.php"
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
                </main>

                <form style="background: #ddd;" data-action="add" action="<?= SITE_HOST ?>/src/admin/add_guide_to_mod.php" method="POST">
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
            </content>
            <?php
        }
        ?>
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