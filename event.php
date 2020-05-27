<?php

use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\Event;
use drflvirtual\src\view\page\EventPage;
use drflvirtual\src\view\page\EventListPage;

require_once 'src/config/app_config.php';
require_once 'src/config/global_config.php';
require_once 'src/procedural/character_functions.php';
require_once 'src/procedural/event_functions.php';

global /** @var mysqli $mysqli */ $mysqli;
$db = new EventDatabase();

// Load the ID.
$event_id = (isset($_GET["id"])     ? $db->escape($_GET["id"])     : false);
$filter = (isset($_GET["filter"])   ? $db->escape($_GET["filter"]) : false);

// If valid ID, render single event page.
if ($event_id) {

    // Get the event.
    //$event_array = getEventWithMods($event_id);
    $event = false;
    try {
        $event = $db->getEvent($event_id);
    } catch (EventNotFoundException $e) {
        exit("Failed to find event.");
    }

    // Render.
    $page = new EventPage($event->getName(), $event);
    $page->render();
    //renderSingleEventPage($event);

} else {
    //$event_arrays = false;
    $events = false;
    $title = false;

    switch($filter) {
        case "unfinished":
        case false:
        default:
            $events = $db->getEvents();
            $title = "Events";
            break;
    }

    // Parse the events.
    /*$events = array();
    foreach($event_arrays as $event_array) {
        $event = Event::constructFromArray($event_array);
        $events[$event->getId()] = $event;
    }*/

    // Render.
    $page = new EventListPage($title, $events);
    $page->render();
}