<?php
if (!defined('INIT')) die;

// Fetch external data source
$langchecker        = 'http://l10n.mozilla-community.org/~pascalc/langchecker/';
$gaia_community     = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia-community'))['items'];
$gaia_l10n          = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia&tree=gaia-community'))['items'];
$gaia_1_1           = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia-v1_1'))['items'];
$gaia_1_2           = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia-v1_2'))['items'];
$gaia_1_3           = getJsonArray(cacheUrl('https://l10n.mozilla.org/shipping/api/status?tree=gaia-v1_3'))['items'];

$gaia_status_community = getGaiaCompletion($gaia_community);
$gaia_status_l10n      = getGaiaCompletion($gaia_l10n);
$gaia_status_1_1       = getGaiaCompletion($gaia_1_1);
$gaia_status_1_2       = getGaiaCompletion($gaia_1_2);
$gaia_status_1_3       = getGaiaCompletion($gaia_1_3);

$slogans            = getJsonArray(cacheUrl($langchecker . '?locale=all&website=5&file=firefoxos.lang&json'))['firefoxos.lang'];
$marketplace_badge  = getJsonArray(cacheUrl($langchecker . '?locale=all&website=5&file=marketplacebadge.lang&json'))['marketplacebadge.lang'];
$partners_site      = getJsonArray(cacheUrl($langchecker . '?locale=all&website=0&file=firefox/partners/index.lang&json'))['firefox/partners/index.lang'];
$consumers_site     = getJsonArray(cacheUrl($langchecker . '?locale=all&website=0&file=firefox/os/index.lang&json'))['firefox/os/index.lang'];
$marketplace        = marketplaceStatus(cacheUrl('http://l10n.mozilla-community.org/~flod/mpstats/marketplace.json'));

// Normalize our locale codes to display them coherently
$gaia_status_community = normalizeGaiaLocales($gaia_status_community);
$gaia_status_l10n      = normalizeGaiaLocales($gaia_status_l10n);
$gaia_status_1_1       = normalizeGaiaLocales($gaia_status_1_1);
$gaia_status_1_2       = normalizeGaiaLocales($gaia_status_1_2);
$gaia_status_1_3       = normalizeGaiaLocales($gaia_status_1_3);
$marketplace           = normalizeGaiaLocales($marketplace);

$temp_inprogress = $temp_done = [];

// locales in shipping dashboards dthat we don't actually ship
$postponed_locales = ['fr', 'sv-SE', ];


foreach ($gaia_status_l10n as $key => $val) {
    if ($val >= 85) {
        $temp_done[] = $key;
    } elseif ($val >= 80) {
        $temp_inprogress[] = $key;
    }
}

$repo_status = function($repo) {
    $status = [
        'done'       => [],
        'inprogress' => [],
    ];
    foreach ($repo as $locale => $completion) {
        if ($completion >= 85) {
            $status['done'][] = $locale;
        } elseif ($completion >= 80) {
            $status['inprogress'][] = $locale;
        }
    }
    return $status;
};

// This is the list of our projects
$projects = [
    'Gaia_l10n' => [
        'requested'        => array_diff(array_keys($gaia_status_l10n), array_keys($gaia_status_community)),
        'inprogress'       => $repo_status($gaia_status_l10n)['inprogress'],
        'done'             => $repo_status($gaia_status_l10n)['done'],
        'owners'           => 'Axel',
        'link'             => 'https://l10n.mozilla.org/shipping/dashboard?tree=gaia',
        'link_description' => 'L10n Dashboard',
        'automated'        => true,
        'display_name'     => 'Gaia-l10n<br>+community',
    ],

    'Gaia_1_1' => [
        'requested'        => array_diff(array_keys($gaia_status_1_1), $postponed_locales),
        'inprogress'       => $repo_status($gaia_status_1_1)['inprogress'],
        'done'             => $repo_status($gaia_status_1_1)['done'],
        'owners'           => 'Axel',
        'link'             => 'https://l10n.mozilla.org/shipping/dashboard?tree=gaia-v1_1',
        'link_description' => 'L10n Dashboard',
        'automated'        => true,
        'display_name'     => 'Gaia 1.1',
    ],

    'Gaia_1_2' => [
        'requested'        => array_diff(array_keys($gaia_status_1_2), $postponed_locales),
        'inprogress'       => $repo_status($gaia_status_1_2)['inprogress'],
        'done'             => $repo_status($gaia_status_1_2)['done'],
        'owners'           => 'Axel',
        'link'             => 'https://l10n.mozilla.org/shipping/dashboard?tree=gaia-v1_2',
        'link_description' => 'L10n Dashboard',
        'automated'        => true,
        'display_name'     => 'Gaia 1.2',
    ],

    'Gaia_1_3' => [
        'requested'        => array_diff(array_keys($gaia_status_1_3), $postponed_locales),
        'inprogress'       => $repo_status($gaia_status_1_3)['inprogress'],
        'done'             => $repo_status($gaia_status_1_3)['done'],
        'owners'           => 'Axel',
        'link'             => 'https://l10n.mozilla.org/shipping/dashboard?tree=gaia-v1_3',
        'link_description' => 'L10n Dashboard',
        'automated'        => true,
        'display_name'     => 'Gaia 1.3',
    ],

    'marketplace' => [
        'requested'        => ['ca', 'cs', 'de', 'el', 'es-ES', 'hr', 'it', 'mk', 'nl', 'pl', 'pt-BR', 'ro', 'sr', 'sr-Latn', 'tr'],
        'inprogress'       => [],
        'done'             => [],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=903145',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],

    'partners_site' => [
        'requested'        => ['ca', 'de', 'el', 'es-ES', 'it', 'ja', 'ko', 'pl', 'pt-BR', 'ro', 'zh-CN', 'zh-TW'],
        'inprogress'       => dotlangTranslated($partners_site),
        'done'             => dotlangActivated($partners_site),
        'owners'           => 'Pascal',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=840960',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],

    'consumers_site' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'hu', 'it', 'pl', 'pt-BR', 'ro', 'sr'],
        'inprogress'       => dotlangTranslated($consumers_site),
        'done'             => dotlangActivated($consumers_site),
        'owners'           => 'Pascal',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=883788',
        'link_description' => 'Tracking Bug',
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
        'inprogress'       => [],
        'done'             => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'pl', 'pt-BR', 'ro', 'sr'],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=902571',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],

    'consumer_headlines' => [
        'requested'        => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress'       => [],
        'done'             => ['bg', 'cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'mk', 'pl', 'pt-BR', 'ro', 'sr'],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=893094',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],

    'consumer_docs' => [
        'requested'        => ['bg', 'cs', 'de', 'el', 'hr', 'hu', 'mk', 'pt-BR', 'ro', 'sq', 'sr'],
        'inprogress'       => [],
        'done'             => ['bg', 'cs', 'de', 'el', 'hr', 'hu', 'mk', 'pt-BR', 'ro', 'sq', 'sr'],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=902056',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],

    'Desktop_whatsnew_promo' => [
        'requested'        => ['hu', 'pl'],
        'inprogress'       => [],
        'done'             => ['hu', 'pl'],
        'owners'           => 'Pascal',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=896611',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],

/* cancelled ?
    'marketplace_badge' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'hr', 'hu', 'nl', 'pl', 'pt-BR', 'ro', 'ru', 'sk', 'sr', 'tr'],
        'inprogress'       => dotlangTranslated($marketplace_badge),
        'done'             => dotlangActivated($marketplace_badge),
        'owners'           => 'Pascal & Flod',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=900060',
        'link_description' => 'Tracking Bug',
        'automated'        => true,
    ],
*/
    'masterfirefoxos' => [
        'requested'        => ['cs', 'de', 'el', 'es-ES', 'de', 'hr', 'hu', 'pt-BR', 'sr'],
        'inprogress'       => ['cs', 'de', 'el', 'hr', 'hu', 'sr'],
        'done'             => ['es-ES', 'pt-BR'],
        'owners'           => 'Peiying',
        'link'             => 'https://bugzilla.mozilla.org/show_bug.cgi?id=904896',
        'link_description' => 'Tracking Bug',
        'automated'        => false,
    ],
];
//~ echo '<pre>';var_dump($projects['partners_site']);echo '<pre>';

/* define locales and priorities */
$gaia_locales = array_unique(array_merge(
    array_keys($gaia_status_community),
    array_keys($gaia_status_l10n),
    array_keys($gaia_status_1_1),
    array_keys($gaia_status_1_2),
    array_keys($gaia_status_1_3)
));

sort($gaia_locales);
$localeDetails = [];

// populate $gaia_locale status automatically
foreach($gaia_locales as $locale) {
    $localeDetails[$locale]['comment'] = '';
    $localeDetails[$locale]['shipped'] = false;
    if (in_array($locale, array_diff(array_keys($gaia_status_1_2), $postponed_locales))) {
        $localeDetails[$locale]['priority'] = 1;
    } elseif (in_array($locale, array_keys($gaia_status_community))) {
        $localeDetails[$locale]['priority'] = 2;
    } else {
        $localeDetails[$locale]['priority'] = 3;
    }
}

// deal with exceptions in $gaia_locales
$shipped = ['de', 'es-ES', 'pl', 'pt-BR'];
foreach($shipped as $val) {
    $localeDetails[$val]['shipped'] = true;
}

// Based on the extracted data and the $projects array, determine our list of locales
$extractLocales = function() use ($projects, $gaia_locales, $localeDetails) {

    $locales = [];
    foreach (['requested', 'done', 'inprogress'] as $status) {
        foreach ($projects as $projectName => $val) {
            $locales = array_merge($locales, $projects[$projectName][$status]);
        }
    }
    $locales = array_merge($locales, $gaia_locales);
    $locales = array_unique($locales);

    $priorityLocales = [];
    foreach ($locales as $locale) {
        if (array_key_exists($locale, $localeDetails)) {
            $priorityLocales[$locale] = $localeDetails[$locale]['priority'];
        } else {
            $priorityLocales[$locale] = 4;
        }
    }

    array_multisort($priorityLocales, $locales);

    return array_keys($priorityLocales);
};

$locales = $extractLocales();

// add locales that don't work on Gaia but work on webparts
foreach ($locales as $locale) {
    if (!array_key_exists($locale, $localeDetails)) {
        $localeDetails[$locale]['comment'] = '';
        $localeDetails[$locale]['shipped'] = false;
        $localeDetails[$locale]['priority'] = 4;
    }
}

$locale_status = function($locale, $localeDetails) {
    return ($localeDetails[$locale]['shipped'] == true)
            ? 'shipped'
            : '';
};
