<?php



// Error Reporting.
use drflvirtual\src\model\database\EventDatabase;

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
require_once SITE_ROOT . "/src/model/NamedObject.php";
require_once SITE_ROOT . "/src/model/Strain.php";
require_once SITE_ROOT . "/src/model/Figure.php";
require_once SITE_ROOT . "/src/model/Player.php";
require_once SITE_ROOT . "/src/model/Map.php";
require_once SITE_ROOT . "/src/model/Skill.php";
require_once SITE_ROOT . "/src/model/Character.php";
require_once SITE_ROOT . "/src/model/Mod.php";
require_once SITE_ROOT . "/src/model/Event.php";
require_once SITE_ROOT . "/src/model/Plot.php";

// VIEW - COMPONENTS
require_once SITE_ROOT . "/src/view/component/Component.php";
require_once SITE_ROOT . "/src/view/component/CharacterCardComponent.php";
require_once SITE_ROOT . "/src/view/component/FigureComponent.php";
require_once SITE_ROOT . "/src/view/component/MapComponent.php";
require_once SITE_ROOT . "/src/view/component/ModComponent.php";
require_once SITE_ROOT . "/src/view/component/ModCardComponent.php";
require_once SITE_ROOT . "/src/view/component/PlotComponent.php";

// VIEW - PAGES
require_once SITE_ROOT . "/src/view/page/Page.php";
require_once SITE_ROOT . "/src/view/page/ErrorPage.php";
require_once SITE_ROOT . "/src/view/page/ListPage.php";
require_once SITE_ROOT . "/src/view/page/MissingPage.php";

require_once SITE_ROOT . "/src/view/page/PlotPage.php";
require_once SITE_ROOT . "/src/view/page/PlotListPage.php";
require_once SITE_ROOT . "/src/view/page/EventListPage.php";
require_once SITE_ROOT . "/src/view/page/EventSchedulePage.php";
require_once SITE_ROOT . "/src/view/page/EventPage.php";
require_once SITE_ROOT . "/src/view/page/MapPage.php";
require_once SITE_ROOT . "/src/view/page/ModPage.php";
require_once SITE_ROOT . "/src/view/page/ModListPage.php";
require_once SITE_ROOT . "/src/view/page/MapListPage.php";
require_once SITE_ROOT . "/src/view/page/CharacterPage.php";
require_once SITE_ROOT . "/src/view/page/CharacterListPage.php";

// ADMIN
require_once SITE_ROOT . "/src/view/page/AdminPage.php";
require_once SITE_ROOT . "/src/view/page/AdminCharacterPage.php";
require_once SITE_ROOT . "/src/view/page/AdminModPage.php";

// INSTANTIATE DATABASE
$GLOBALS['db'] = new EventDatabase();

// TEMP FIX
error_reporting(E_ALL);

// VIEW - RECORDS

// VIEW - TRASH AGILE RUSH JOB BULLSHIT LMAO
//require_once SITE_ROOT . '/src/procedural/character_functions.php';

