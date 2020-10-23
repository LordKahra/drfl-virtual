<?php


namespace drflvirtual\src\model;

interface Serializable {
    public function toArray() : array;
}