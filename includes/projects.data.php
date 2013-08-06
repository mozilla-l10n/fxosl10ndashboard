<?php
if (!defined('INIT')) die;

// Fetch external data source
$langchecker        = 'http://l10n.mozilla-community.org/~pascalc/langchecker/';
$gaia               = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia-community&tree=gaia'))['items'];
$gaiaStatus         = getGaiaCompletion($gaia);
$slogans            = getJsonArray(cacheUrl($langchecker . '?locale=all&website=5&file=firefoxos.lang&json'))['firefoxos.lang'];
$marketplace_badge  = getJsonArray(cacheUrl($langchecker . '?locale=all&website=5&file=marketplacebadge.lang&json'))['marketplacebadge.lang'];
$partners_site      = getJsonArray(cacheUrl($langchecker . '?locale=all&website=0&file=firefox/partners/index.lang&json'))['firefox/partners/index.lang'];
$consumers_site     = getJsonArray(cacheUrl($langchecker . '?locale=all&website=0&file=firefox/os/index.lang&json'))['firefox/os/index.lang'];
$marketplace        = marketplaceStatus(cacheUrl('http://flod.org/pei/marketplace.json'));

// Normalize our locale codes to display them coherently
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

$temp_inprogress = $temp_done = [];

foreach ($gaiaStatus as $key => $val) {
    if ($val >= 85) {
        $temp_done[] = $key;
    } elseif ($val >= 80) {
        $temp_inprogress[] = $key;
    }
}

// This is the list of our projects
$projects = [
    'Firefox_os' => [
        'requested'  => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
        'inprogress' => $temp_inprogress,
        'done'       => $temp_done,
        'owners'     => 'Axel',
        'automated'  => true,
    ],

    'marketplace' => [
        'requested'  => ['cs', 'de', 'el', 'es-ES', 'pl', 'pt-BR'],
        'inprogress' => [],
        'done'       => [],
        'owners'     => 'Peiying',
        'automated'  => true,
    ],

    'partners_site' => [
        'requested'  => ['de', 'es-ES', 'it', 'ja', 'ko', 'pl', 'pt-BR', 'zh-CN', 'zh-TW'],
        'inprogress' => [],
        'done'       => activated($partners_site),
        'owners'     => 'Pascal',
        'automated'  => true,
    ],

    'consumers_site' => [
        'requested'  => ['cs', 'de', 'el', 'es-ES', 'hu', 'pl', 'pt-BR', 'sr'],
        'inprogress' => [],
        'done'       => activated($consumers_site),
        'owners'     => 'Pascal',
        'automated'  => true,
    ],

    'slogans' => [
        'requested'  => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress' => [],
        'done'       => activated($slogans),
        'owners'     => 'Pascal & Flod',
        'automated'  => true,
    ],

    'screenshots' => [
        'requested'  => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress' => [],
        'done'       => ['es-ES', 'pl'],
        'owners'     => 'Peiying',
        'automated'  => false,
    ],

    'whatsnew_promo' => [
        'requested'  => ['es-ES', 'pl'],
        'inprogress' => [],
        'done'       => [],
        'owners'     => 'Pascal',
        'automated'  => false,
    ],

    'marketplace_badge' => [
        'requested'  => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
        'inprogress' => [],
        'done'       => activated($marketplace_badge),
        'owners'     => 'Pascal & Flod',
        'automated'  => true,
    ],
];

$shipped = [ 'es-ES','pl'];

$localesPriority = [
    'ar'    => 3,
    'as'    => 3,
    'ast'   => 3,
    'be'    => 3,
    'bg'    => 2,
    'bn-BD' => 3,
    'bn-IN' => 3,
    'bs'    => 2,
    'ca'    => 3,
    'cs'    => 2,
    'cy'    => 3,
    'da'    => 3,
    'de'    => 1,
    'el'    => 2,
    'eo'    => 3,
    'es-ES' => 1,
    'et'    => 3,
    'eu'    => 3,
    'ff'    => 3,
    'fr'    => 3,
    'fy-NL' => 3,
    'ga-IE' => 3,
    'gd'    => 3,
    'gl'    => 3,
    'gu'    => 3,
    'he'    => 3,
    'hi-IN' => 3,
    'hr'    => 2,
    'ht'    => 3,
    'hu'    => 2,
    'id'    => 3,
    'it'    => 2,
    'ja'    => 3,
    'km'    => 3,
    'kn'    => 3,
    'ko'    => 3,
    'lij'   => 3,
    'mk'    => 2,
    'ml'    => 3,
    'ms'    => 3,
    'ne-NP' => 3,
    'nl'    => 2,
    'or'    => 3,
    'pa-IN' => 3,
    'pl'    => 1,
    'pt-BR' => 1,
    'ro'    => 2,
    'ru'    => 3,
    'si'    => 3,
    'sk'    => 2,
    'sl'    => 3,
    'sq'    => 2,
    'sr'    => 2,
    'sr-Latn' => 2,
    'sv-SE' => 3,
    'te'    => 3,
    'th'    => 3,
    'tr'    => 2,
    'ur'    => 3,
    'vi'    => 3,
    'zh-CN' => 3,
    'zh-TW' => 3,
];

// Based on the extracted data and the $projects array, determine our list of locales
$locales = [];
foreach (['requested', 'done', 'inprogress'] as $val1) {
    foreach ($projects as $key => $val2) {
        $locales = array_merge($locales, $projects[$key][$val1]);
    }
}

$locales = array_merge($locales, array_keys($gaiaStatus));
$locales = array_unique($locales);
sort($locales);
$locale_status = function($locale, $shipped) {
    return in_array($locale, $shipped)
            ? 'shipped'
            : '';
};
