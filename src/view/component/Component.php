<?php

abstract class Component {
    protected bool $isFoldable=false;
    protected bool $isFolded=false;
    protected string $tag;

    public function __construct(string $tag="") {
        $this->tag = $tag;
    }

    abstract function render();

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