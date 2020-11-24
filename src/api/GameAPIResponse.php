<?php

namespace drflvirtual\src\api;

class GameAPIResponse {
    private int $code;
    private string $message;
    private array $data;

    public function __construct(array $response) {
        $this->code = intval($response['code']);
        $this->message = $response['message'];
        $this->data = array_key_exists('data', $response) ? $response['data'] : array();
    }

    /**
     * @return int
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    public function isSuccess() {
        return ($this->code >= 200) && ($this->code < 300);
    }
}