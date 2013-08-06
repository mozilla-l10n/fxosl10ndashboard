<?php
if (!defined('INIT')) die;

$langchecker = 'http://l10n.mozilla-community.org/~pascalc/langchecker/';
$gaia = $getJsonArray('https://l10n.mozilla.org/shipping/api/status?tree=gaia-community&tree=gaia')['items'];
$gaiaStatus         = $getGaiaCompletion($gaia);
$slogans            = $getJsonArray($langchecker . '?locale=all&website=5&file=firefoxos.lang&json')['firefoxos.lang'];
$marketplace_badge  = $getJsonArray($langchecker . '?locale=all&website=5&file=marketplacebadge.lang&json')['marketplacebadge.lang'];
$partners_site      = $getJsonArray($langchecker . '?locale=all&website=0&file=firefox/partners/index.lang&json')['firefox/partners/index.lang'];
$consumers_site     = $getJsonArray($langchecker . '?locale=all&website=0&file=firefox/os/index.lang&json')['firefox/os/index.lang'];
$marketplace        = $getJsonArray('http://flod.org/pei/marketplace.json');
$checkproject = function () use ($marketplace) {
    $retval = array();
    $projectsNames = ['fireplace', 'zamboni', 'webpay'];
    foreach ($marketplace as $locale => $projects) {
        foreach ($projectsNames as $name) {
            if (isset($projects[$name])) {
                $retval[$locale][$name] = round($projects[$name]['percentage']);
            } else {
                $retval[$locale][$name] = '--';
            }
        }
    }
    return $retval;
};

$marketplace        = $checkproject();

$tweakLocaleCode = function($code1, $code2) use (&$gaiaStatus, &$marketplace) {
    if (array_key_exists($code2, $gaiaStatus)) {
        $gaiaStatus[$code1] = $gaiaStatus[$code2];
        unset($gaiaStatus[$code2]);
    }

    if (array_key_exists($code2, $marketplace)) {
        $marketplace[$code1] = $marketplace[$code2];
        unset($marketplace[$code2]);
    }
};


$tweakLocaleCode('es-ES', 'es');
$tweakLocaleCode('pt-BR', 'pt');
$tweakLocaleCode('sr', 'sr-Cyrl');
$tweakLocaleCode('pa-IN', 'pa');
$gaia = array_keys($gaiaStatus);
$temp_inprogress = $temp_done = [];



$requested = [
    // from https://l10n.mozilla.org/shipping/dashboard?tree=gaia
    // Turkish data is from https://intranet.mozilla.org/Program_Management/Firefox_OS/Localization
    'Firefox_os'        => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
    'marketplace'       => ['cs', 'de', 'el', 'es-ES', 'pl', 'pt-BR'],
    'partners_site'     => ['de', 'es-ES', 'it', 'ja', 'ko', 'pl', 'pt-BR', 'zh-CN', 'zh-TW'],
    'consumers_site'    => ['cs', 'de', 'el', 'es-ES', 'hu', 'pl', 'pt-BR', 'sr'],
    'slogans'           => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
    'screenshots'       => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
    'whatsnew_promo'    => ['es-ES', 'pl'],
    'marketplace_badge' => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
];

$inprogress = [
    'Firefox_os'        => $temp_inprogress,
    'marketplace'       => [],
    'partners_site'     => [],
    'consumers_site'    => [],
    'slogans'           => ['cs', 'de', 'el', 'es-ES', 'hu', 'pl', 'ro'],
    'screenshots'       => [],
    'whatsnew_promo'    => [],
    'marketplace_badge' => [],
];

$done = [
    'Firefox_os'        => $temp_done,
    'marketplace'       => [],
    'partners_site'     => $activated($partners_site),
    'consumers_site'    => $activated($consumers_site),
    'slogans'           => $activated($slogans),
    'screenshots'       => ['es-ES', 'pl'],
    'whatsnew_promo'    => [],
    'marketplace_badge' => $activated($marketplace_badge),

];

$owners = [
    'Firefox_os'        => 'Axel',
    'marketplace'       => 'Peiying',
    'partners_site'     => 'Pascal',
    'consumers_site'    => 'Pascal',
    'slogans'           => 'Pascal & Flod',
    'screenshots'       => 'Peiying',
    'whatsnew_promo'    => 'Pascal',
    'marketplace_badge' => 'Pascal & Flod',
];

$onmarket = [
    'es-ES' => 'shipped',
    'pl' => 'shipped',
];

$requested_automation = [
    'Firefox_os'        => true,
    'marketplace'       => true,
    'partners_site'     => true,
    'consumers_site'    => true,
    'slogans'           => true,
    'screenshots'       => false,
    'whatsnew_promo'    => false,
    'marketplace_badge' => true,
];

$locales = [];
foreach (['requested', 'done', 'inprogress'] as $val1) {
    foreach (array_keys($requested) as $val2) {
        $locales = array_merge($locales, ${$val1}[$val2]);
    }
}

$locales = array_merge($locales, $gaia);
$locales = array_unique($locales);
sort($locales);
$locale_status = function($locale) use ($onmarket) {
    return in_array($locale, array_keys($onmarket))
            ? $onmarket[$locale]
            : '';
};


