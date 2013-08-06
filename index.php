<?php

require_once __DIR__ .'/includes/init.php';

// Get overview
ob_start();
include __DIR__ .'/views/overview.php';
$content = ob_get_contents();
ob_end_clean();
$pageTitle = 'Mini-Dashboard showing the global shipping state for Firefox OS per locale';

// Show dashboard in template
include __DIR__ .'/templates/base.tpl.php';
