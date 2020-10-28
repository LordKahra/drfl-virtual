<?php

namespace drflvirtual\src\admin;

use drflvirtual\src\api\GameAPIConnection;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Player;
use PlayerNotFoundException;

class Authentication {
    const TOKEN = "token";
    const PLAYER_ID = "player_id";
    const PLAYER_NAME = "name";
    const IS_GUIDE = "is_guide";
    const IS_ADMIN = "is_admin";

    //private $player_id;

    //private $player;

    function isLoggedIn() : bool {
        // Is there a session?
        if (!isset($_SESSION[static::TOKEN]) || !$_SESSION[static::TOKEN]) {
            return false;
        }

        // Is there a player id? If so, they're logged in as that player.
        return $_SESSION[static::TOKEN] ? true : false;
    }

    private function setLoginDetails(array $response) {
        $_SESSION[static::TOKEN] = $response["data"][static::TOKEN];
        $_SESSION[static::PLAYER_ID] = $response["data"][static::PLAYER_ID];
        $_SESSION[static::PLAYER_NAME] = $response["data"][static::PLAYER_NAME];
        $_SESSION[static::IS_GUIDE] = $response["data"][static::IS_GUIDE];
        $_SESSION[static::IS_ADMIN] = $response["data"][static::IS_ADMIN];
    }

    private function clearLoginDetails() {
        unset($_SESSION[self::TOKEN]);
        unset($_SESSION[self::PLAYER_ID]);
        unset($_SESSION[self::PLAYER_NAME]);
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

        // Call the API.
        $response = GameAPIConnection::sendLoginRequest($player_id, $password);

        // Echo API response.
        echo "\n<br/>API Response:";
        var_dump($response);

        if ($response["code"] == 200) {
            // Login successful.
            self::setLoginDetails($response);
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

    public static function getPlayerId() {
        return (isset($_SESSION[static::PLAYER_ID]) && $_SESSION[static::PLAYER_ID]);
    }

    public static function isGuide() : bool {
        return (isset($_SESSION[static::IS_GUIDE]) && $_SESSION[static::IS_GUIDE]);
    }

    public static function isAdmin() : bool {
        return (isset($_SESSION[static::IS_ADMIN]) && $_SESSION[static::IS_ADMIN]);
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
                if (!static::isGuide()) $this->redirectToError("You must be a guide to view that page.");
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
                if (!static::isAdmin()) $this->redirectToError("You must be an admin to view that page.");
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