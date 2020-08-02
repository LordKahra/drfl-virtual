<?php

namespace drflvirtual\src\view\page;


class ErrorPage extends Page {
    protected $error;

    public function __construct(string $message) {
        parent::__construct($message, "error", "public");
        $this->error = $message;
    }

    /**
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }

    function renderBody() {
        echo $this->getError();
        ?><?php
    }
}