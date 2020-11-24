<?php

use drflvirtual\src\view\View;

abstract class Component extends View {
    protected bool $isFoldable=false;
    protected bool $isFolded=false;
    protected string $tag;

    public function __construct(string $tag="") {
        $this->tag = $tag;
    }

    /**
     * @return bool
     */
    public function isFoldable(): bool {
        return $this->isFoldable;
    }

    /**
     * @return bool
     */
    public function isFolded(): bool {
        return $this->isFolded;
    }

    /**
     * @return string
     */
    public function isActiveString(): string {
        return $this->isFolded ? "false" : "true";
    }

    /**
     * @return string
     */
    public function getTag(): string {
        return $this->tag;
    }
}