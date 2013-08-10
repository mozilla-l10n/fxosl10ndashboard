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
$marketplace        = marketplaceStatus(cacheUrl('http://l10n.mozilla-community.org/~flod/mpstats/marketplace.json'));

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
        'requested'        => ['ca', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'sr-Latn', 'tr'],
        'inprogress'       => $temp_inprogress,
        'done'             => $temp_done,
        'owners'           => 'Axel',
        'link'             => 'https://l10n.mozilla.org/shipping/dashboard?tree=gaia',
        'link_description' => 'L10n Dashboard',
        'automated'        => true,
    ],

    'marketplace' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'pl', 'pt-BR'],
        'inprogress'       => [],
        'done'             => [],
        'owners'           => 'Peiying',
        'link'             => '',
        'link_description' => '',
        'automated'        => true,
    ],

    'partners_site' => [
        'requested'        => ['de', 'es-ES', 'it', 'ja', 'ko', 'pl', 'pt-BR', 'zh-CN', 'zh-TW'],
        'inprogress'       => dotlangTranslated($partners_site),
        'done'             => dotlangActivated($partners_site),
        'owners'           => 'Pascal',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=840960',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],

    'consumers_site' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'hu', 'pl', 'pt-BR', 'sr'],
        'inprogress'       => dotlangTranslated($consumers_site),
        'done'             => dotlangActivated($consumers_site),
        'owners'           => 'Pascal',
        'link'             => '',
        'link_description' => '',
        'automated'        => true,
    ],

    'slogans' => [
        'requested'        => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress'       => dotlangTranslated($slogans),
        'done'             => dotlangActivated($slogans),
        'owners'           => 'Pascal & Flod',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=893094',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],

    'screenshots' => [
        'requested'        => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress'       => ['de', 'hu', 'pt-BR', 'ro' ],
        'done'             => ['es-ES', 'pl'],
        'owners'           => 'Peiying',
        'link'             => '',
        'link_description' => '',
        'automated'        => false,
    ],

    'consumer_message' => [
        'requested'        => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress'       => [],
        'done'             => [],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=893094',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],

    'whatsnew_promo' => [
        'requested'        => ['es-ES', 'pl'],
        'inprogress'       => [],
        'done'             => [],
        'owners'           => 'Pascal',
        'link'             => '',
        'link_description' => '',
        'automated'        => false,
    ],

    'marketplace_badge' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
        'inprogress'       => dotlangTranslated($marketplace_badge),
        'done'             => dotlangActivated($marketplace_badge),
        'owners'           => 'Pascal & Flod',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=900060',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],
];

$shipped = [ 'es-ES','pl'];

$localeDetails = [
    'ar' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => 'Test comment'
    ],
    'as' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ast' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'be' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'bg' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'bn-BD' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'bn-IN' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'bs' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ca' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'cs' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],

    'cy' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'da' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'de' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],
    'el' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'eo' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'es-ES' =>
    [
        'priority' => 1,
        'shipped' => true,
        'comment' => ''
    ],
    'et' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'eu' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ff' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'fr' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'fy-NL' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ga-IE' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'gd' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'gl' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'gu' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'he' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'hi-IN' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'hr' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],
    'ht' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'hu' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],
    'id' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'it' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ja' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'km' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'kn' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ko' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'lij' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'mk' =>
    [
        'priority' => 3,
        'shipped' => false,
        'comment' => ''
    ],
    'ml' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ms' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'ne-NP' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'nl' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'or' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'pa-IN' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'pl' =>
    [
        'priority' => 1,
        'shipped' => true,
        'comment' => ''
    ],
    'pt-BR' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],
    'ro' =>
    [
        'priority' => 1,
        'shipped' => false,
        'comment' => ''
    ],
    'ru' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'si' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'sk' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'sl' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'sq' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'sr' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => 'sr or sr-Cyrl is Serbian Cyrillic '
    ],
    'sr-Latn' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'sv-SE' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'te' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'th' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'tr' =>
    [
        'priority' => 2,
        'shipped' => false,
        'comment' => ''
    ],
    'ur' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'vi' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
    'zh-CN' =>
    [
        'priority' => 3,
        'shipped' => false,
        'comment' => ''
    ],
    'zh-TW' =>
    [
        'priority' => 4,
        'shipped' => false,
        'comment' => ''
    ],
];

// Based on the extracted data and the $projects array, determine our list of locales
$extractLocales = function() use($projects, $gaiaStatus, $localeDetails) {

    $locales = [];
    foreach (['requested', 'done', 'inprogress'] as $status) {
        foreach ($projects as $projectName => $val) {
            $locales = array_merge($locales, $projects[$projectName][$status]);
        }
    }

    $locales = array_merge($locales, array_keys($gaiaStatus));
    $locales = array_unique($locales);

    $priorityLocales = [];
    foreach ($locales as $locale) {
        if (array_key_exists($locale, $localeDetails)) {
            $priorityLocales[$locale] = $localeDetails[$locale]['priority'];
        } else {
            $priorityLocales[$locale] = 4;
        }
    }

    asort($priorityLocales);

    return array_keys($priorityLocales);
};

$locales = $extractLocales();


$locale_status = function($locale, $localeDetails) {
    return ($localeDetails[$locale]['shipped'] == true)
            ? 'shipped'
            : '';
};
