<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\Plot;
use drflvirtual\src\view\component\PlotComponent;

class PlotListPage extends Page {
    protected $plots;

    /**
     * PlotListPage constructor.
     * @param Plot[] $plots
     */
    public function __construct(array $plots) {
        parent::__construct("Plots", "plot", "guide");
        $this->plots = $plots;
    }

    function renderBody() {
        foreach ($this->plots as $plot) {
            (new PlotComponent($plot))->render();
        }
    }
}