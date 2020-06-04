<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Mod;
use drflvirtual\src\model\Player;
use drflvirtual\src\model\Map;

class AdminModPage extends Page {
    protected $db;

    protected $mods;

    protected $guides;
    protected $characters;
    protected $maps;

    public function __construct(array $mods) {
        parent::__construct("Administration - Mods", "admin");

        $this->mods = $mods;

        global /** @var EventDatabase $db */ $db;
        $this->db = $db;

        // Load data.
        $this->guides = $this->db->getGuides();
        $this->characters = $this->db->getCharacters();
        $this->maps = $this->db->getMaps();
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
        <main data-type="mod" data-style="admin" id="mod_<?=$mod->getId()?>">
            <header>
                <div data-type="name"><a href="<?=SITE_HOST?>/mod.php?id=<?=$mod->getId()?>"><?=$mod->getName()?></a></div>

                <div class="row">
                    <div><b>Location:</b> <?=$mod->getLocation()?></div>
                    <div><b>Where: </b><?=$mod->getHost()?></div>
                    <div><b>When: </b><?=$mod->getStartString()?></div>
                </div>

                <?php if ($mod->isTabletop()) { ?>
                <div>
                    <form data-action="set" action="<?=SITE_HOST?>/src/admin/admin_mod_details.php" method="POST">
                        <input type="hidden" name="action" value="set"/>
                        <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>"/>
                        <input type="hidden" name="field" value="map_status"/>
                        Map Status: <select id="value" name="value" onchange="this.form.submit()">
                            <option value="<?=$mod->getMapStatus()?>"><?=$mod->getMapStatus()?></option>
                            <option value="DISCOVERY">DISCOVERY</option>
                            <option value="ASSIGNED">ASSIGNED</option>
                            <option value="CREATED">CREATED</option>
                            <option value="UPLOADED">UPLOADED</option>
                            <option value="NONE">NONE</option>
                        </select>
                    </form>
                </div>
                <?php } ?>

                <?php if ($mod->isTabletop()) { ?>
                <div>
                    <form data-action="set" action="<?=SITE_HOST?>/src/admin/admin_mod_details.php" method="POST">
                        <input type="hidden" name="action" value="set"/>
                        <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>"/>
                        <input type="hidden" name="field" value="tabletop_status"/>
                        Tabletop Status: <select id="value" name="value" onchange="this.form.submit()">
                            <option value="<?=$mod->getTabletopStatus()?>"><?=$mod->getTabletopStatus()?></option>
                            <option value="DISCOVERY">DISCOVERY</option>
                            <option value="CREATED">CREATED</option>
                            <option value="MAPPED">MAPPED</option>
                            <option value="STATTED">STATTED</option>
                            <option value="TOKENED">TOKENED</option>
                            <option value="TESTING">TESTING</option>
                            <option value="READY">READY</option>
                            <option value="NONE">NONE</option>
                        </select>
                    </form>
                </div>
                <?php } ?>

                <div>
                    <form data-action="set" action="<?=SITE_HOST?>/src/admin/admin_mod_details.php" method="POST">
                        <input type="hidden" name="action" value="set"/>
                        <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>"/>
                        <input type="hidden" name="field" value="is_ready"/>
                        Ready: <input type="checkbox" name="value" value="1" <?=($mod->isReady() ? "checked" : "")?>  onchange="this.form.submit()" />
                    </form>
                </div>

                <div data-fold="true" id="mod_raw_<?=$mod->getId()?>" data-active="false">
                    <header><button data-ui="button" href="#" onclick="toggleById('mod_raw_<?=$mod->getId();?>')">🔎</button> View Raw Data</header>
                    <main data-style="raw"><?php var_dump($mod) ?></main>
                </div>

                <div data-fold="true" id="mod_maps_<?=$mod->getId()?>" data-active="false">
                    <header><button data-ui="button" href="#" onclick="toggleById('mod_maps_<?=$mod->getId();?>')">🔎</button> View Maps</header>
                    <main data-style="image"><?php foreach ($mod->getMaps() as $map) { ?>
                        <p><?=$map->getName()?> - #<?=$map->getCreatorId()?> <?php
                            if (array_key_exists($map->getCreatorId(), $this->guides)) {
                                echo $this->guides[$map->getCreatorId()]->getName();
                            }
                                ?></p>
                        <img src="<?=SITE_HOST . "/res/images/maps/{$map->getId()}.png"?>">
                        <?php } ?></main>
                </div>

            </header>



            <?php $this->renderModDetailLists($mod); ?>
            <?php $this->renderModDropdowns($mod); ?>

        </main>
        <?php
    }

    private function renderModDetailLists(Mod $mod) {
        ?>
        <main>

            <div>
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
            </div>

            <div>
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
            </div>

            <?php if ($mod->isTabletop()) { ?>
            <div>
            <p>Current Maps:</p>
                <ul>
                    <?php foreach ($mod->getMaps() as $map) { ?>
                        <li>
                            <form data-action="delete" action="<?= SITE_HOST ?>/src/admin/admin_mod_maps.php"
                                  method="POST">
                                <?= $map->getName() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                                <input type="hidden" name="map_id" value="<?= $map->getId() ?>">
                                <input type="submit" value="✖"/>
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>

        </main>
        <?php
    }

    private function renderModDropdowns(Mod $mod) {
        ?>
        <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_mod_guides.php" method="POST">
                <header>Add Guides to Mod</header>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                <select id="guide_id" name="guide_id">
                    <?php foreach ($this->guides as $guide) { ?>
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

        <?php if ($mod->isTabletop()) { ?>
            <form data-action="add" action="<?= SITE_HOST ?>/src/admin/admin_mod_maps.php" method="POST">
                <header>Add Maps to Mod</header>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="mod_id" value="<?= $mod->getId() ?>">
                <select id="guide_id" name="map_id">
                    <?php foreach ($this->maps as $map) { ?>
                        <option value="<?= $map->getId() ?>"><?= $map->getName() ?></option>
                    <?php } ?>
                </select>
                <input type="submit"/>
            </form>
        <?php } ?>
        <?php
    }

    /**
    * @return Mod[]
    */
    public  function getMods(): array {
        return $this->mods;
    }
}