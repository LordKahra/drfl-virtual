<?php
namespace drflvirtual\src\view\page;


use drflvirtual\src\model\Event;

class EventListPage extends Page {
    protected $events = array();

    /**
     * EventListPage constructor.
     * @param string $title
     * @param string $type
     * @param Event[] $events
     */
    public function __construct(string $title, array $events) {
        parent::__construct($title, "event_list", "guide");

        foreach ($events as $event) {
            $this->events[$event->getId()] = $event;
        }

    }

    /**
     * @return Event[]
     */
    public function getEvents(): array {
        return $this->events;
    }

    function renderBody() {
        ?>
        <main>
            <ul>
                <?php
                foreach ($this->getEvents() as $event) {
                    ?><li><a href="event.php?id=<?=$event->getId()?>"><?=$event->getName()?></a></li><?php
                }
                ?>
            </ul>
        </main>
        <?php
    }
}