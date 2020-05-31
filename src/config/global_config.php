<?php



// Error Reporting.
error_reporting(DEBUG_MODE ? E_ALL : 0);

// Session.
session_start();

// VARIABLES THAT WILL CHANGE FREQUENTLY
define("CURRENT_EVENT", 2);

////////////////////////////
//// SOURCE CODE ////////
////////////////////////////

// EXCEPTION
require_once SITE_ROOT . "/src/exception/Exception.php";

// DATABASE
require_once SITE_ROOT . "/src/config/database_connection.php";
require_once SITE_ROOT . "/src/database/EventDatabase.php";

// MODEL
require_once SITE_ROOT . "/src/model/Player.php";
require_once SITE_ROOT . "/src/model/Map.php";
require_once SITE_ROOT . "/src/model/Skill.php";
require_once SITE_ROOT . "/src/model/Character.php";
require_once SITE_ROOT . "/src/model/Mod.php";
require_once SITE_ROOT . "/src/model/Event.php";

// VIEW - PAGES
require_once SITE_ROOT . "/src/view/page/Page.php";
require_once SITE_ROOT . "/src/view/page/ErrorPage.php";
require_once SITE_ROOT . "/src/view/page/ListPage.php";
require_once SITE_ROOT . "/src/view/page/MissingPage.php";

require_once SITE_ROOT . "/src/view/page/EventListPage.php";
require_once SITE_ROOT . "/src/view/page/EventPage.php";
require_once SITE_ROOT . "/src/view/page/MapPage.php";
require_once SITE_ROOT . "/src/view/page/ModPage.php";
require_once SITE_ROOT . "/src/view/page/ModListPage.php";
require_once SITE_ROOT . "/src/view/page/MapListPage.php";

// ADMIN
require_once SITE_ROOT . "/src/view/page/AdminPage.php";
require_once SITE_ROOT . "/src/view/page/AdminModPage.php";



// VIEW - RECORDS

// VIEW - TRASH AGILE RUSH JOB BULLSHIT LMAO
//require_once SITE_ROOT . '/src/procedural/character_functions.php';

