<?php

namespace drflvirtual\src\model;


class Event extends StoryObject {
    protected $start;
    protected $end;
    protected $soft_rp_open;
    protected $soft_rp_close;
    protected $announce_open;
    protected $announce_close;
    protected $description;

    protected $mods = array();

    /**
     * Event constructor.
     * @param int $id
     * @param string $name
     * @param string $start
     * @param string $end
     * @param string $soft_rp_open
     * @param string $soft_rp_close
     * @param string $announce_open
     * @param string $announce_close
     * @param string $description
     * @param Mod[] $mods
     */
    public function __construct(
        int $id,
        string $name,
        string $start,
        string $end,
        string $soft_rp_open,
        string $soft_rp_close,
        string $announce_open,
        string $announce_close,
        string $description,
        array $mods
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->soft_rp_open = $soft_rp_open;
        $this->soft_rp_close = $soft_rp_close;
        $this->announce_open = $announce_open;
        $this->announce_close = $announce_close;
        $this->description = $description;

        foreach ($mods as $mod) {
            $this->mods[$mod->getId()] = $mod;
        }
    }

    public static function constructFromArray(array $event) {
        // Create a holding array for mods.
        $mods = array();
        //var_dump($event);

        // Get the mods.
        if (array_key_exists('mods', $event)) foreach($event['mods'] as $mod_array) {
            $mods[] = Mod::constructFromArray($mod_array);
        }

        return new Event(
            $event['id'],
            $event['name'],
            $event['start'],
            $event['end'],
            $event['soft_rp_open'],
            $event['soft_rp_close'],
            $event['announce_open'],
            $event['announce_close'],
            $event['description'] ? $event['description'] : "",
            $mods
        );

    }

    /**
     * @return string
     */
    public function getStart(): string {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd(): string {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getSoftRpOpen(): string {
        return $this->soft_rp_open;
    }

    /**
     * @return string
     */
    public function getSoftRpClose(): string {
        return $this->soft_rp_close;
    }

    /**
     * @return string
     */
    public function getAnnounceOpen(): string {
        return $this->announce_open;
    }

    /**
     * @return string
     */
    public function getAnnounceClose(): string {
        return $this->announce_close;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return Mod[]
     */
    public function getMods(): array {
        return $this->mods;
    }

    public function toArray(): array {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName()
        );
    }

}