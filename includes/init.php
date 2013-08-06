<?php
const INIT  = true;
const DEBUG = true;

$requestURL = function() {
    $result = $_SERVER['REQUEST_URI'];
    $query = $_SERVER['QUERY_STRING'];
    if (!empty($query)) {
        $result = str_replace('?' . $query, '', $result);
    }

    return $result;
};

// This is the base URL for js/css in HTML
$requestURL = $requestURL();

// Load local environment variables that depend on the hosting configuration
require_once  __DIR__ .'/localenv.php';

define('CACHE', $cachePath);

// Set debug environment
if(DEBUG) {
    ini_set("log_errors", 1);
    ini_set("error_log", "/tmp/fxoslocale-errors.log");
    error_log("---------------------------");
}

// Include utility functions
require_once __DIR__ .'/functions.php';

// Load all base data we need to build the table
require_once __DIR__ .'/projects.data.php';
