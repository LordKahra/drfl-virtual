<?php
namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;
use PlayerNotFoundException;

class MapListPage extends Page {
    protected $maps;

    protected $db;

    protected $mods;
    protected $mod_map_relations;

    /**
     * MapListPage constructor.
     * @param Map[] $maps
     */
    public function __construct(array $maps) {
        parent::__construct("Map List", "map", "guide");

        $this->maps = $maps;

        global /** @var EventDatabase $db */ $db;
        $this->db = $db;

        // We're just going to need the mods.
        $map_ids = implode(",", array_keys($this->maps));
        $this->mods = $this->db->getMods("id IN (SELECT mod_id from r_mod_maps WHERE map_id IN ($map_ids))");

        // We're also going to need the relations.
        $mod_map_relations = $this->db->getModMapRelations("map_id IN ($map_ids)");

        // Create usable array.
        $relation_array = array();
        foreach ($this->maps as $map) {
            $relation_array[$map->getId()] = array();
        }

        // Add relations to array.
        if ($mod_map_relations) {
            foreach($mod_map_relations as $relation) {
                $relation_array[$relation['map_id']][$relation['mod_id']] = $this->mods[$relation['mod_id']];
            }
        }

        $this->mod_map_relations = $relation_array;
    }

    function renderBody() {
        ?>
        <main>
            <header>Maps</header>
            <?php
            foreach ($this->getMaps() as $map) {
                $this->renderMap($map);
            }
            ?>
        </main>
        <?php
    }

    function renderMap(Map $map) {
        $player = false;
        try {
            $player = $this->db->getPlayer($map->getCreatorId());
        } catch (PlayerNotFoundException $e) {
            // Do nothing.
        }

        ?>
        <div data-type="map">
            <div data-style="map" data-fold="true" data-active="false" id="map_<?=$map->getId()?>">
                <header>
                    <button data-ui="button" href="#" onclick="toggleById('map_<?=$map->getId();?>')">🔎</button>
                    <span data-type="name"><b><a href="map.php?id=<?=$map->getId()?>"><?=$map->getId()?> - <?=$map->getName()?></a> ---  <?=$map->getStatus()?></b></span>
                    <p>Creator: #<?=$map->getCreatorId()?><?=($player ? " - {$player->getName()}" : "")?></p>
                    <p><?=$map->getDescription()?></p>
                </header>
                <main data-style="image">

                    <img src="<?=SITE_HOST . "/res/images/maps/{$map->getId()}.png"?>">

                    <ul>
                        <?php /** @var Mod $mod */
                        foreach ($this->mod_map_relations[$map->getId()] as $mod) { ?>
                            <li>
                                <header><?=$mod->getName() ?></header>
                                <p><?=nl2br($mod->getDescription())?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </main>
            </div>


        </div>
        <?php
    }

    /**
     * @return Map[]
     */
    public function getMaps() {
        return $this->maps;
    }
}