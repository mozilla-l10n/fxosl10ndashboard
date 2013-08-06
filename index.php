<?php

require_once __DIR__ .'/includes/init.php';

// Get overview
ob_start();
include __DIR__ .'/views/overview.php';
$content = ob_get_contents();
ob_end_clean();
$pageTitle = 'Mini-Dashboard showing the global shipping state for Firefox OS per locale';

// Show dashboard in template

if (isset($_GET['theme']) && in_array($_GET['theme'], ['base', 'sandstone'])) {
    $theme = $_GET['theme'];
} else {
    $theme = 'base';
}

include __DIR__ .'/templates/' . $theme . '.tpl.php';
