<?php

use drflvirtual\src\view\page\AuthLoginPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

//Load the page.
(new AuthLoginPage())->render();