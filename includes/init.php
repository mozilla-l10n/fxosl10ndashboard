<?php
const INIT = true;

// Load local environment variables that depend on the hosting configuration
require_once  __DIR__ .'/localenv.php';

define('CACHE', $cachePath);

ini_set("log_errors", 1);
ini_set("error_log", "/tmp/fxoslocale-errors.log");
error_log("---------------------------");

if (!is_dir(CACHE)) {
    mkdir(CACHE, 0777);
}

// Include utility functions
require_once __DIR__ .'/functions.php';

// Load all base data we need to build the table
require_once __DIR__ .'/projects.data.php';
