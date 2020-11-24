<?php

namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Character;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Map;
use drflvirtual\src\model\Mod;
use drflvirtual\src\view\component\ModComponent;
use PlayerNotFoundException;

class ModPage extends Page {
    protected $db;

    protected $mod;

    public function __construct(string $title, Mod $mod) {
        parent::__construct($title, "mod", "guide");
        global /** @var EventDatabase $db */ $db;
        //echo "<script>console.log('ModPage.__construct(...): Entered.')</script>";
        $this->db = $db;
        $this->mod = $mod;
        //echo "<script>console.log('ModPage.__construct(...): Done.')</script>";
    }

    /**
     * @return Mod
     */
    public function getMod(): Mod {
        return $this->mod;
    }

    function renderBody() {
        echo "<script>console.log('ModPage.renderBody(...): Entered.')</script>";
        (new ModComponent($this->mod, false))->render();
        echo "<script>console.log('ModPage.renderBody(...): Done.')</script>";
    }
}