<?php

namespace drflvirtual\src\admin;

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Player;
use PlayerNotFoundException;

class Authentication {
    const PLAYER_ID = "player_id";
    const IS_GUIDE = "is_guide";
    const IS_ADMIN = "is_admin";

    private $player;

    public function __construct() {
        // Set the player.
        try {
            $this->player = self::loadPlayer();
        } catch (PlayerNotFoundException $e) {
            $this->player = false;
        }
    }

    function isLoggedIn() : bool {
        return $this->player ? true : false;
    }

    private function setLoginDetails(Player $player) {
        $this->player = $player;

        $_SESSION[self::PLAYER_ID] = $player->getId();
        $_SESSION[self::IS_GUIDE] = $player->isGuide();
        $_SESSION[self::IS_ADMIN] = $player->isAdmin();

    }

    private function clearLoginDetails() {
        $this->player = false;

        unset($_SESSION[self::PLAYER_ID]);
        unset($_SESSION[self::IS_GUIDE]);
        unset($_SESSION[self::IS_ADMIN]);
    }

    /**
     * @param int $player_id
     * @param string $password
     * @throws PlayerNotFoundException
     */
    function login(int $player_id, string $password) {
        // Load the database.
        global /** @var EventDatabase $db */ $db;
        // Get the player.
        $player = $db->getPlayer($player_id);

        // Validate the password.
        if (self::isValidPassword($player->getId(), $password)) {
            // Set proper session variables.
            self::setLoginDetails($player);
        }

        // Done. Return.
        return $this->isLoggedIn();
    }

    function logout() {
        $this->clearLoginDetails();
    }

    static function setPassword(int $player_id, string $password) : bool {
        // Load the database.
        global /** @var EventDatabase $db */ $db;

        return $db->setPassword($player_id, $password);
    }

    static function isValidPassword(int $player_id, string $password) : bool {
        // Load the database.
        global /** @var EventDatabase $db */ $db;

        return $db->isValidPassword($player_id, $password);
    }

    private static function loadPlayer() : Player {
        // Is the player ID set?
        if (!isset($_SESSION[self::PLAYER_ID]) || !$_SESSION[self::PLAYER_ID])
            throw new PlayerNotFoundException(-1);

        // Is the player ID an int?
        if (!is_numeric($_SESSION[self::PLAYER_ID]))
            throw new PlayerNotFoundException(-1);


        global /** @var EventDatabase $db */ $db;

        // Try to get the player.
        return $db->getPlayer($_SESSION[self::PLAYER_ID]);
    }

    public function getPlayer() : Player {
        if ($this->player) return $this->player;

        throw new PlayerNotFoundException(-1);
    }

    public function enforceSecurity(string $level) {
        $this->enforceLoggedIn($level);
        $this->enforceGuide($level);
        $this->enforceAdmin($level);
    }

    public function enforceLoggedIn(string $level) {
        // Enforce logged in.
        switch ($level) {
            case "public":
                // Do nothing.
                break;
            case "player":
            case "guide":
            case "admin":
                if (!$this->isLoggedIn()) $this->redirectToLogin();
                break;
        }
    }

    public function enforceGuide(string $level) {
        // Enforce logged in.
        switch ($level) {
            case "public":
            case "player":
                // Do nothing.
                break;
            case "guide":
            case "admin":
                if (!$this->getPlayer()->isGuide()) $this->redirectToError("You must be a guide to view that page.");
                break;
        }
    }

    public function enforceAdmin(string $level) {
        // Enforce logged in.
        switch ($level) {
            case "public":
            case "player":
            case "guide":
                // Do nothing.
                break;
            case "admin":
                if (!$this->getPlayer()->isAdmin()) $this->redirectToError("You must be an admin to view that page.");
                break;
        }
    }

    public function redirectHome(string $message="") {
        // Player is not logged in. Redirect them to the login page.
        header("Location: " . SITE_HOST . "/index.php?message=" . urlencode($message));
        exit();
    }

    public function redirectToLogin(string $message="") {
        // Player is not logged in. Redirect them to the login page.
        header("Location: " . SITE_HOST . "/login.php?message=" . urlencode($message));
        exit();
    }

    public function redirectToError(string $message) {
        // Player is not logged in. Redirect them to the login page.
        header("Location: " . SITE_HOST . "/error.php?message=" . urlencode($message));
        exit();
    }
}