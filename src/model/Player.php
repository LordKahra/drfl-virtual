<?php

namespace drflvirtual\src\model;


class Player implements NamedObject {
    protected $id;
    protected $name;
    protected $is_guide;
    protected $is_admin;
    private $salt;
    private $password;

    public function __construct(int $id, string $name, bool $is_guide, bool $is_admin, string $password) {
        $this->id = $id;
        $this->name = $name;
        $this->is_guide = $is_guide;
        $this->is_admin = $is_admin;
        $this->password = $password;
    }

    public static function constructFromArray(array $player_array) {
        return new Player(
            $player_array['id'],
            $player_array['name'],
            $player_array['is_guide'],
            $player_array['is_admin'],
            $player_array['password'] ? $player_array['password'] : ""
        );
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isGuide(): bool {
        return $this->is_guide;
    }

    public function isAdmin() : bool {
        return $this->is_admin;
    }

    public function matchesPassword(string $password) : bool {
        // If there is no password, return false.
        if (!$this->password) return false;

        // Verify the password.
        return password_verify($password, $this->password);
    }
}