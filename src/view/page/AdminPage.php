<?php

namespace drflvirtual\src\view\page;


class AdminPage extends Page {

    public function __construct() {
        parent::__construct("Administration", "admin");

    }

    function renderBody() {
        ?>
        <main>
            <ul>
                <li><a href="<?=SITE_HOST?>/admin_mod.php">All Events: Update Mods</a></li>
                <li><a href="<?=SITE_HOST?>/admin_mod.php?filter=event&filter_id=2">Event 2: Update Mods</a></li>

            </ul>
        </main>
        <?php
    }
}