<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\model\database\EventDatabase;abstract class Page {
    protected $title;
    protected $type;

    public function __construct(string $title, string $type) {
        $this->title = $title;
        $this->type = $type;
    }

    // ACCESS

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string {
         return $this->type;
    }

    // RENDERS

    function render() {
        $this->renderHeader();
        $this->renderBody();
        $this->renderFooter();
    }

    function renderHeader() {
        $this->renderHeaderStart();
        $this->renderHeaderMiddle();
        $this->renderHeaderEnd();
    }

    function renderHeaderStart() {
        ?><!DOCTYPE html>
    <html>
    <head>
        <title><?=$this->getTitle();?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/main.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/nav.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/forms.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/event.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/map.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/mod.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/visible.css"/>
        <script src="<?php echo SITE_HOST; ?>/js/jquery-1.12.3.js"></script>
        <script src="<?php echo SITE_HOST; ?>/js/view.js"></script>
    </head>
    <body>
    <header>
        <main>
            <main><a href="../../index.php">DRFL Virtual Event</a></main>
            <nav>
                <ul>
                    <li><a href="character.php">Characters</a></li>
                    <li><a href="event.php">Events</a></li>
                    <li><a href="map.php">Maps</a></li>
                    <li><a href="mod.php">Mods</a></li>
                    <li><a href="admin.php">Admin</a></li>
                </ul>
            </nav>
        </main>
        <?php
    }

    function renderHeaderMiddle() {
        switch(strtolower($this->getType())) {
            case "mod":
                // Need database.
                $db = new EventDatabase();
                // Need events without details.
                $events = $db->getEvents("", false);
                ?>
                <nav>
                    <ul>
                        <li><b>FILTERS</b></li>
                        <li><a href="mod.php">All</a></li>
                        <li><a href="mod.php?filter=unfinished">Unfinished</a></li>
                        <li>
                            <form action="mod.php" method="get">
                                <input type="hidden" name="filter" value="event"/>
                                <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                                    <option label=" ">Select an Event</option>
                                    <?php foreach ($events as $event) { ?>
                                        <option value="<?=$event->getId() ?>"><?=$event->getName()?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </li>
                    </ul>
                </nav>
                <?php
                break;
            case "map":
                // Need database.
                $db = new EventDatabase();
                // Need events without details.
                $events = $db->getEvents("", false);
                // Need guides.
                $players = $db->getGuides();
                ?>
                <nav>
                    <ul>
                        <li><b>FILTERS</b></li>
                        <li><a href="map.php?filter=all">All</a></li>
                        <li><a href="map.php?filter=current">Current</a></li>
                        <li>
                            <form action="map.php" method="get">
                                <input type="hidden" name="filter" value="event"/>
                                <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                                    <option label=" ">Select an Event</option>
                                    <?php foreach ($events as $event) { ?>
                                        <option value="<?=$event->getId() ?>"><?=$event->getName()?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </li>
                        <li>
                            <form action="map.php" method="get">
                                <input type="hidden" name="filter" value="player"/>
                                <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                                    <option label=" ">Select a Guide</option>
                                    <?php foreach ($players as $player) { ?>
                                        <option value="<?=$player->getId() ?>"><?=$player->getName()?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </li>
                    </ul>
                </nav>
                <?php
                break;
            default:
                break;
        }
    }

    function renderHeaderEnd() { ?>
        </header>
        <?php
    }

    function renderFooter() {
        ?>
</body>
</html>
        <?php
    }

    abstract function renderBody();
}