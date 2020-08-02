<?php

use drflvirtual\src\view\page\ErrorPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';

// Load the message.
$message = (isset($_GET["message"])     ? $db->escape($_GET["message"])     : false);

//Load the page.
(new ErrorPage($message))->render();