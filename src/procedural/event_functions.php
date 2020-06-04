<?php

use drflvirtual\src\model\database\EventDatabase;

function getEvent(int $id) {
    $event_array = getQueryResults("SELECT * FROM events WHERE id = $id");

    if (!$event_array) return false;
    if (is_array($event_array['0'])) return $event_array['0'];
    return false;
}

function getEventWithMods(int $id) {
    $event = getEvent($id);
    if (!$event) return false;

    // Get their mods.
    $mod_arrays = getEventMods($id);

    $event['mods'] = (is_array($mod_arrays)) ? $mod_arrays : array();

    return $event;
}

function getAllEventsWithMods(string $where="") {
    // Create the query.
    $query = "SELECT * FROM events " .
        ($where ? "WHERE $where" : "") .
        "ORDER BY `name`";

    // Get all mods.
    $events = getQueryResults($query);
    if (!$events) return false;

    // Get their characters.
    foreach($events as &$event) {
        $event_mods = getEventMods($event['id']);

        $event['mods'] = (is_array($event_mods)) ? $event_mods : array();
    }

    return $events;
}

function getEventMods(int $id) {
    global /** @var EventDatabase $db */ $db;

    return getAllModsWithCharacters("event_id = $id");
}

function renderSingleEventPage($event) {

}

function renderMultiEventPage($events) {
    renderHeader("Events", "event");
    ?>

    <?php
    renderFooter();
}