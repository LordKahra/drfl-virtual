<?php

use drflvirtual\src\view\page\AdminPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

(new AdminPage())->render();