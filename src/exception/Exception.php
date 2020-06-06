<?php

class RecordNotFoundException extends Exception {
    private $id;
    function __construct(int $id) {
        parent::__construct("Record $id not found in the database.");
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
}

class EventNotFoundException extends RecordNotFoundException {}
class MapNotFoundException extends RecordNotFoundException {}
class ModNotFoundException extends RecordNotFoundException {}
class PlayerNotFoundException extends RecordNotFoundException {}
class PlotNotFoundException extends RecordNotFoundException {}

class SQLException extends Exception {
    private $query;
    function __construct($message, $query=false) {
        parent::__construct($message);
        $this->query = $query ? $query : "Unknown query.";
        Debug::log(get_class($this), "Exception thrown: $message.\r\nQuery:$query", true);
    }

    public function getQuery() {
        return $this->query;
    }
}
class SQLInsertException extends SQLException {}
class SQLUpdateException extends SQLException {}
class SQLUpsertException extends SQLException {}