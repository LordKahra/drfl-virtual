<?php

namespace boffer\src\view\page;


class ErrorPage extends Page {
    protected $error;

    public function __construct(string $title, string $message) {
        parent::__construct($title);
        $this->error = $message;
    }

    /**
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }

    function renderBody() {
        ?>That page was not found.<?php
    }
}