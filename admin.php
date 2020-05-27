<?php

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Authentication.
// TODO: This will be part of every page eventually.
require_once SITE_ROOT . "/src/admin/auth.php";

(new \drflvirtual\src\view\page\AdminPage())->render();