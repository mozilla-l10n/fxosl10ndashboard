<?php
const INIT = true;
define('CACHE',  $_SERVER['DOCUMENT_ROOT'] . '/cache/');

if (!is_dir(CACHE)) {
     mkdir(CACHE, 0755);
}

// Include utility functions
require_once __DIR__ .'/functions.php';

// Load all base data we need to build the table
require_once __DIR__ .'/projects.data.php';
