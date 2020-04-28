<?php

namespace boffer\src\view\page;

abstract class Page {
    protected $title;

    public function __construct(string $title) {
        $this->title = $title;
    }

    // ACCESS

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    // RENDERS

    function render() {
        $this->renderHeader();
        $this->renderBody();
        $this->renderFooter();
    }

    function renderHeader() {
        ?><!DOCTYPE html>
<html>
<head>
    <title><?=$this->getTitle();?></title>

    <style>* { font-family: Verdana; }</style>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_HOST; ?>/css/main.css"/>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="skill.php">Skills</a></li>
                <li><a href="exercise.php">Exercises</a></li>
            </ul>
        </nav>
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