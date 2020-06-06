<?php

namespace drflvirtual\src\view\component;

use Component;
use drflvirtual\src\model\Plot;
use drflvirtual\src\view\page\Page;

class PlotComponent extends Component {
    protected $plot;

    public function __construct(Plot $plot) {
        $this->plot = $plot;
    }

    function render() {
        ?>
        <div data-style="plot" data-fold="true" data-active="true" id="plot_<?= $this->plot->getId() ?>">
            <header>
                <button data-ui="button" href="#" onclick="toggleById('plot_<?= $this->plot->getId(); ?>')">🔎</button>
                <span data-type="name"><b><?= $this->plot->getId() ?> - <?= $this->plot->getName() ?></b></span>

            </header>
            <main data-style="image">
                <p><?= Page::formatDescription($this->plot->getDescription()) ?></p>
            </main>
        </div>
        <?php
    }
}