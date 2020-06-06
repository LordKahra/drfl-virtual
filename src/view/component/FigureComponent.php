<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Figure;

class FigureComponent extends Component {
    protected $figure;

    public function __construct(Figure $figure) {
        $this->figure = $figure;
    }

    function render() {
        $filename = null;

        if (file_exists(SITE_ROOT . "/res/images/figures/" . $this->figure->getId() . ".png" )) {
            $filename = SITE_HOST . "/res/images/figures/" . $this->figure->getId() . ".png";
        } elseif (file_exists(SITE_ROOT . "/res/images/figures/" . $this->figure->getId() . ".jpg")) {
            $filename = SITE_HOST . "/res/images/figures/" . $this->figure->getId() . ".jpg";
        }

        if ($filename) {
            ?>
            <div data-style="figure" data-fold="true" data-active="false" id="figure_<?= $this->figure->getId() ?>">
                <header>
                    <button data-ui="button" href="#" onclick="toggleById('figure_<?= $this->figure->getId(); ?>')">🔎</button>
                    <span data-type="name"><b><?= $this->figure->getId() ?> - <?= $this->figure->getName() ?></b></span>
                    <p><?= $this->figure->getDescription() ?></p>
                </header>
                <main data-style="image">
                    <img src="<?=($filename ? $filename : "")?>">
                </main>
            </div>
            <?php
        }
    }
}