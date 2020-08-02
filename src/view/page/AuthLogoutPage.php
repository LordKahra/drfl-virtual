<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\view\component\LoginComponent;

class AuthLogoutPage extends Page {

    public function __construct() {
        parent::__construct("Logged Out", "auth", "public");

        $this->auth->logout();
    }

    function renderBody() {
        if (!$this->auth->isLoggedIn()) {
            ?>You are now logged out.<?php
        } else {
            ?>Failed to log out? That's strange.<?php
        }

        $this->auth->logout();

        if (!$this->auth->isLoggedIn()) {
            (new LoginComponent())->render();
        }
    }
}