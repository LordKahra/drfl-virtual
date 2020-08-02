<?php

namespace drflvirtual\src\view\page;

use DateTime;
use drflvirtual\src\model\Event;
use drflvirtual\src\model\Mod;
use drflvirtual\src\view\component\ModCardComponent;

class EventSchedulePage extends Page {
    protected $event;

    protected $schedule;
    protected $timestamps;
    protected $spaces;

    public function __construct(string $title, Event $event) {
        parent::__construct("Schedule - " . $event->getName(), "event", "guide");
        $this->event = $event;

        $this->generateModSchedule();
        //var_dump($this->schedule);
        //var_dump($this->timestamps);
        //var_dump($this->spaces);
    }

    /**
     * @return Event
     */
    public function getEvent(): Event {
        return $this->event;
    }

    /**
     * Gets an array of Mod[] arrays sorted by timestamp.
     * @return array
     */
    public function getSchedule(): array {
        return $this->schedule;
    }

    private function generateModSchedule() {
        $schedule = array();
        $timestamps = array();
        $spaces = array();

        foreach($this->getEvent()->getMods() as $mod) {
            // Get the details.
            $date = $mod->getStart();
            $timestamp = $mod->getStartTimestamp();
            $space = $mod->getSpace();

            // Store the details.
            if (!array_key_exists($timestamp, $timestamps)) {
                // New timestamp.

                // Save in list of timestamps.
                $timestamps[$timestamp] = $date;

                // Create array in schedule.
                $schedule[$timestamp] = array();

                // Add all spaces to the timestamp.
                foreach($spaces as &$old_space) {
                    $schedule[$timestamp][$old_space] = array();
                }
            }
            if (!in_array($space, $spaces)) {
                // New space. Add to all timestamps.

                // Save in list of spaces.
                $spaces[$space] = $space;

                // Add to all timestamps.
                foreach ($schedule as &$old_timestamps) {
                    $old_timestamps[$space] = array();
                }
            }

            // Add the mod.
            $schedule[$timestamp][$space][] = $mod;
        }

        // Sort the schedule.
        ksort($schedule);
        foreach ($schedule as &$timeslots) ksort($timeslots);
        ksort($timestamps);
        ksort($spaces);

        $this->schedule = $schedule;
        $this->timestamps = $timestamps;
        $this->spaces = $spaces;
    }

    function renderBody() {
        ?>
        <table data-type="event" data-style="schedule">
            <tr>
                <th></th>
                <?php foreach ($this->spaces as $space) { ?>
                    <th><?=$space?></th>
                <?php } ?>
            </tr>

            <?php /** @var DateTime $date */
            foreach ($this->timestamps as $timestamp => $date) { ?>
                <tr>
                    <th><?=$date->format('l Hi')?></th>
                    <?php foreach ($this->spaces as $space) { ?>
                        <td>
                            <?php foreach ($this->getSchedule()[$timestamp][$space] as $mod) (new ModCardComponent($mod))->render(); ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>

        </table>
        <?php
    }
}