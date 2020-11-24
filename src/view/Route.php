<?php


namespace drflvirtual\src\view;

use drflvirtual\src\admin\Authentication;

class Route {
    public static function toLogin(string $message) {
        // Log them out first.
        //Authentication::logout();

        header("Location: ". SITE_HOST . "/login.php?message=".urlencode($message));
        exit;
    }
}