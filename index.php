<?php

require_once __DIR__ .'/includes/init.php';

ob_start();

$getJsonArray = function($url) {
    return json_decode(file_get_contents($url), true);
};

$activated = function($arr) {
    $locales = [];
    foreach ($arr as $key => $val) {
        if ($arr[$key]['activated'] == true) {
            if (in_array($key, ['es-MX', 'es-CL', 'es-AR'])) continue;

            $locales[] = $key;
        }
    }
    return $locales;
};

//~ $inprogress = function($arr) {
    //~ $locales = [];
    //~ foreach ($arr as $key => $val) {
        //~ if ($arr[$key]['activated'] == false) {
            //~ $total
            //~ $locales[] = $key;
        //~ }
    //~ }
    //~ return $locales;
//~ };

$langchecker = 'http://l10n.mozilla-community.org/~pascalc/langchecker/';
$gaia = $getJsonArray('https://l10n.mozilla.org/shipping/api/status?tree=gaia-community&tree=gaia')['items'];

$getGaiaCompletion = function ($gaia) {
    $temp = [];
    foreach ($gaia as $key => $value) {
        if (array_key_exists('tree', $value) && ($value['tree'] == 'gaia-community' or $value['tree'] == 'gaia') ) {
            $temp[$value['locale']] = $value['completion'];
        }
    }
    ksort($temp);
    return $temp;
};

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

$marketplace = $checkproject();

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

$temp_inprogress = $temp_done = [];

foreach ($gaiaStatus as $key => $val) {
    if ($val >= 85) {
        $temp_done[] = $key;
    } elseif ($val >= 80) {
        $temp_inprogress[] = $key;
    }
}

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

$onmarket = [
    //~ 'de' => 'shipping-September',
    'es-ES' => 'shipped',
    'pl' => 'shipped',
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

echo '<table>';
echo '<tr>';
echo '<th></th>';

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

foreach ($requested_automation as $key => $val) {
    $th = ($val == true) ? '<th class="automated">' : '<th>';

    if ($key == 'Firefox_os') {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '<br><small><a href="https://l10n.mozilla.org/shipping/dashboard?tree=gaia-community&tree=gaia">(Data Source)</a></small></th>';
    } elseif ($key == 'marketplace') {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '<br><small>fireplace/zamboni/webpay</small></th>';
    } else {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '</th>';
    }
}

echo '</tr>';

echo '<tr class="owner">';
echo '<th>Owner</th>';
foreach ($owners as $key => $val) {
        echo '<td>' .  $val . '</td>';
}
echo '</tr>';




foreach ($locales as $locale) {

    $active = function($requested, $done, $inprogress, $key) use($locale, $gaiaStatus, $marketplace) {

        $cell = '';

        if (in_array($locale, $requested) && !in_array($locale, $done) && !in_array($locale, $inprogress)) {
            $class = 'missing';
        } else if (!in_array($locale, $requested) && in_array($locale, $done)) {
            $class = 'bonus';
        } else if (in_array($locale, $requested) && in_array($locale, $done)) {
            $class = 'done';
        } else if (in_array($locale, $requested) && in_array($locale, $inprogress)) {
            $class = 'inprogress';
        } else {
            $class = '';
        }

        if ($key == 'Firefox_os' && array_key_exists($locale, $gaiaStatus)) {
            $cell = $gaiaStatus[$locale]. '%';
        }

        if ($key == 'marketplace' && array_key_exists($locale, $marketplace)) {
            $cell = implode('/', $marketplace[$locale]);

            if ( $marketplace[$locale]['fireplace'] == 100
                && $marketplace[$locale]['zamboni'] >= 99
                && $marketplace[$locale]['webpay'] >= 94)
            {
                if (in_array($locale, $requested)) {
                    $class = 'done';
                } else {
                    $class = 'bonus';
                }
            } elseif (array_sum($marketplace[$locale]) > 270) {
                $class = 'inprogress';
            } elseif (in_array($locale, $requested)) {
                $class = 'missing';
            } else {
                $class = '';
            }

        }

        return '<td class="' . $class . '">' . $cell . '</td>';
    };


    $locale_status = function($locale) use ($onmarket) {
        return in_array($locale, array_keys($onmarket))
                ? $onmarket[$locale]
                : '';
    };

    echo '<tr>';
    echo '<th class="' .  $locale_status($locale) . '">' . $locale . '</th>';
    foreach ($requested as $key => $val) {
        echo $active($requested[$key], $done[$key], $inprogress[$key], $key);
    }
    echo '</tr>';
}
echo '</table>';

$content = ob_get_contents();
ob_end_clean();

// Show dashboard in template
include __DIR__ .'/templates/base.tpl.php';
