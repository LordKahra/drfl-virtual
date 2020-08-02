<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\admin\Authentication;
use drflvirtual\src\view\component\LoginComponent;

class AuthLoginPage extends Page {

    public function __construct() {
        parent::__construct("Login", "auth", "public");
    }

    function renderBody() {
        ?>
        You are <?=$this->auth->isLoggedIn() ? "" : "not " ?>logged in.
        <?php

        if (!$this->auth->isLoggedIn()) {
            (new LoginComponent())->render();
        }
    }
}