<?php

namespace drflvirtual\src\model;

abstract class StoryObject implements NamedObject, Serializable {
    protected $id;
    protected $name;

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }
}