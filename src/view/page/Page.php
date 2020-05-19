<?php

namespace drflvirtual\src\view\page;

abstract class Page {
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
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/event.css"/>
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
                    <li><a href="mod.php">Mods</a></li>
                    <li><a href="event.php">Events</a></li>
                </ul>
            </nav>
        </main>
        <?php
    }

    function renderHeaderMiddle() {
        // Intentionally left blank.
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