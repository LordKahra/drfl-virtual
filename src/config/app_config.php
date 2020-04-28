<?php

define("SITE_ROOT", "S:/Git/drfl-virtual");
define("SITE_HOST", "http://localhost:8080/git/drfl-virtual");

//define("SITE_ROOT", getenv("SITE_ROOT_TOURNAMENT_CLOUD"));
//define("SITE_HOST", getenv("SITE_HOST_TOURNAMENT_CLOUD"));
//define("API_HOST", getenv("SITE_HOST_API_TOURNAMENT"));

// MYSQL
define("DATABASE_HOST", getenv("DATABASE_HOST"));
define("DATABASE_USERNAME", getenv("DATABASE_USERNAME"));
define("DATABASE_PASSWORD", getenv("DATABASE_PASSWORD"));
define("DATABASE_NAME", "drfl_virtual");
//define("DATABASE_NAME", getenv("DATABASE_NAME_BOFFER_WIKI"));
// TODO: Determine if mysql access is needed.

// Debugging Switch.
define("DEBUG_MODE", true);

// GLOBAL REQUIREMENTS
require_once SITE_ROOT . "/src/config/global_config.php";