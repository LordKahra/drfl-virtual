<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Player;
use PlayerNotFoundException;

class MapComponent extends Component {
    protected $db;
    protected $map;

    /**
     * ModCardComponent constructor.
     * @param Map $map
     * @param EventDatabase $db
     */
    public function __construct(Map $map) {
        $this->map = $map;
    }


    function render() {
        ?>
        <div data-style="map" data-fold="true" data-active="false" id="map_<?=$this->map->getId()?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('map_<?=$this->map->getId();?>')">🔎</button>
                <span data-type="name"><b><a href="map.php?id=<?=$this->map->getId()?>"><?=$this->map->getId()?> - <?=$this->map->getName()?></a> ---  <?=$this->map->getStatus()?></b></span>
                <p>Creator: #<?=$this->map->getCreatorId()?><?=($this->map->getCreator() ? " - {$this->map->getCreator()->getName()}" : "")?></p>
                <p><?=$this->map->getDescription()?></p>
            </header>
            <main data-style="image">
                <img src="<?=SITE_HOST . "/res/images/maps/{$this->map->getId()}.png"?>">
            </main>
        </div>
        <?php
    }
}