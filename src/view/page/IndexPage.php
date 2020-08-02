<?php

namespace drflvirtual\src\view\page;

class IndexPage extends Page {

    public function __construct() {
        parent::__construct(SITE_NAME, "index", "guide");
    }

    function renderBody() {
        ?>Hello.<?php
    }
}