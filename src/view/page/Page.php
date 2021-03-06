<?php

namespace drflvirtual\src\view\page;

use drflvirtual\src\admin\Authentication;
use drflvirtual\src\model\database\EventDatabase;
use drflvirtual\src\model\NamedObject;
use drflvirtual\src\view\View;

abstract class Page extends View {
    protected Authentication $auth;

    protected $title;
    protected $type;

    protected string $security;

    public function __construct(string $title, string $type, string $security="admin") {
        global /** @var Authentication $auth */ $auth;
        $this->auth = $auth;

        $this->title = $title;
        $this->type = $type;
        $this->security = $security;

        $this->auth->enforceSecurity($this->security);
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
        echo "<script>console.log('Page.render(): Entered.')</script>";

        $this->renderHeader();
        $this->renderBody();
        $this->renderFooter();

        echo "<script>console.log('Page.render(): Done.')</script>";
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
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/article.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/card.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/list.css"/>

        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/event.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/map.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/mod.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/character.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/visible.css"/>
        <script src="<?php echo SITE_HOST; ?>/js/jquery-1.12.3.js"></script>
        <script src="<?php echo SITE_HOST; ?>/js/view.js"></script>
        <script src="<?php echo SITE_HOST; ?>/js/api.js"></script>
    </head>
    <body>
    <header>
        <main>
            <main><a href="index.php">DRFL Virtual Event</a></main>
            <nav>
                <script>console.log('Page.php: About to render unordered list.')</script>
                <ul>
                    <li><a href="event.php">Events</a></li>
                    <li><a href="event_schedule.php?id=<?=CURRENT_EVENT?>">Schedule</a></li>
                    <li><a href="faction.php">Factions</a></li>
                    <li><a href="mod.php">Mods</a></li>
                    <li><a href="character.php">Characters</a></li>
                    <li><a href="map.php">Maps</a></li>
                    <li><a href="admin.php">Admin</a></li>
                    <li>
                        <?=($this->auth->isLoggedIn()
                            ? "Logged in: #" . Authentication::getPlayerId() . " <a href='logout.php'>Logout</a>"
                            : "<a href='login.php'>Login</a>"
                        )?>
                    </li>
                </ul>
                <script>console.log('Page.php: Done rendering unordered list.')</script>
            </nav>
        </main>
        <?php
    }

    function renderHeaderMiddle() {
        switch(strtolower($this->getType())) {
            case "mod":
                $this->renderModHeaderMiddle();
                break;
            case "map":
                $this->renderMapHeaderMiddle();
                break;
            case "admin":
                $this->renderAdminHeaderMiddle();
                break;
            default:
                break;
        }
    }

    private function renderAdminHeaderMiddle() {
        // Need database.
        global /** @var EventDatabase $db */ $db;
        // Need events without details.
        $events = $db->getEvents("", false);
        // Need guides.
        $players = $db->getGuides();
        ?>
        <nav>
            <ul>
                <li><b>FILTERS</b></li>
                <li><a href="admin_mod.php?filter=all">Admin Mods - All</a></li>
                <li><a href="admin_mod.php?filter=current">Admin Mods - Current</a></li>
                <li>
                    <form action="admin_mod.php" method="get">Admin Mods by Event:
                        <input type="hidden" name="filter" value="event"/>
                        <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                            <option label=" ">Select an Event</option>
                            <?php foreach ($events as $event) { ?>
                                <option value="<?=$event->getId() ?>"><?=$event->getName()?></option>
                            <?php } ?>
                        </select>
                    </form>
                </li>

                <li><a href="admin_character.php">Admin Characters</a></li>
                <!--li>
                    <form action="admin.php" method="get">Admin Mods by Guide:
                        <input type="hidden" name="filter" value="player"/>
                        <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                            <option label=" ">Select a Guide</option>
                            <?php foreach ($players as $player) { ?>
                                <option value="<?=$player->getId() ?>"><?=$player->getName()?></option>
                            <?php } ?>
                        </select>
                    </form>
                </li-->
            </ul>
        </nav>
        <?php
    }

    private function renderMapHeaderMiddle() {
        // Need database.
        global /** @var EventDatabase $db */ $db;
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
                    <?php $this->renderSelectForm("By Event:", "event", $events, "map.php"); ?>
                </li>
                <li>
                    <?php $this->renderSelectForm("By Guide:", "player", $players, "map.php"); ?>
                </li>
                <li>
                    <?php $this->renderSelectForm("By Guide (Current Event):", "player_current", $players, "map.php"); ?>
                </li>
            </ul>
        </nav>
        <?php
    }

    private function renderModHeaderMiddle() {
        // Need database.
        global /** @var EventDatabase $db */ $db;


        // Need events without details.
        $events = $db->getEvents("", false);
        // Need characters without details.
        $characters = $db->getCharacters("");
        // Need guides.
        $players = $db->getGuides();
        ?>
        <nav>
            <ul>
                <li><b>FILTERS</b></li>
                <li><a href="mod.php?filter=all">All</a></li>
                <li><a href="mod.php?filter=current">All</a></li>
                <li><a href="mod.php?filter=unfinished">Unfinished</a></li>
                <li>
                    <?php $this->renderSelectForm("By Event:", "event", $events,"mod.php") ?>
                </li>
                <li>
                    <?php $this->renderSelectForm("By Character:", "character", $characters,"mod.php") ?>
                </li>
                <li>
                    <?php $this->renderSelectForm("By Guide (Current Event):", "current_guide", $players, "mod.php"); ?>
                </li>
            </ul>
        </nav>
        <?php


    }

        /**
         * @param string $label
         * @param string $filter
         * @param NamedObject[] $objects
         * @param string $target
         * @param string $method
         */
        function renderSelectForm(string $label, string $filter, array $objects, string $target, string $method="get") {
        ?>
        <form action="<?=$target?>" method="<?=$method?>"><?=$label?>
            <input type="hidden" name="filter" value="<?=$filter?>"/>
            <select id="filter_id" name="filter_id" onchange="this.form.submit()">
                <option label=" ">Select</option>
                <?php foreach ($objects as $object) { ?>
                    <option value="<?=$object->getId() ?>"><?=$object->getName()?></option>
                <?php } ?>
            </select>
        </form>
        <?php
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

    public static function formatText(string $text) {
        // First off, add <br/> for each line.
        $text = nl2br($text);

        // Show figures.
        $text = preg_replace('/{!figure_(\d+)}/',
            "<a href='" . SITE_HOST . "/res/images/figures/$1.png'><img data-ui='thumbnail' data-type='figure' src='" . SITE_HOST . "/res/images/figures/$1.png' /></a>", $text);

        // Show images.
        $text = preg_replace('/{!misc_([\w-]+)}/',
            "<a href='" . SITE_HOST . "/res/images/misc/$1.png'><img data-ui='thumbnail' data-type='figure' src='" . SITE_HOST . "/res/images/misc/$1.png' /></a>", $text);

        return $text;
    }

    protected function renderFigure(int $id) {

    }
}