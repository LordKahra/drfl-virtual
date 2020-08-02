<?php

use drflvirtual\src\view\page\IndexPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

require_once SITE_ROOT . '/src/procedural/character_functions.php';

//Load the page.
(new IndexPage())->render();