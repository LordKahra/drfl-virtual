<?php



// Error Reporting.
error_reporting(DEBUG_MODE ? E_ALL : 0);

// Session.
session_start();

////////////////////////////
//// SOURCE CODE ////////
////////////////////////////

// DATABASE
require_once SITE_ROOT . "/src/config/database_connection.php";

// MODEL
//require_once SITE_ROOT . "/src/model/Skill.php";
//require_once SITE_ROOT . "/src/model/Exercise.php";

// VIEW - PAGES
require_once SITE_ROOT . "/src/view/page/Page.php";
require_once SITE_ROOT . "/src/view/page/ErrorPage.php";
require_once SITE_ROOT . "/src/view/page/ListPage.php";
require_once SITE_ROOT . "/src/view/page/MissingPage.php";
require_once SITE_ROOT . "/src/view/page/SkillListPage.php";
require_once SITE_ROOT . "/src/view/page/SkillPage.php";

// VIEW - RECORDS

