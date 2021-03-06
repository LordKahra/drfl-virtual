<?php

namespace drflvirtual\src\view\page;


class MissingPage extends Page {
    protected $message;

    public function __construct(string $title, string $message) {
        parent::__construct($title, "error", "public");
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    function renderBody() {
        ?><?=$this->getMessage();?><?php
    }
}