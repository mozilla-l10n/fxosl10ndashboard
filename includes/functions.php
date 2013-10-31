<?php
if (!defined('INIT')) die;

// Utility functions for the Dashboard

function getJsonArray($url) {
    return json_decode(file_get_contents($url), true);
}


/*
 * return an array of locales that have the ## active ## tag set for a
 * a lang file, indicating that the task is done and on production
 *
 * dotlangActivated()
 * @param array
 * @return array
 */
function dotlangActivated($arr)
{
    $locales = [];
    foreach ($arr as $key => $val) {
        if ($arr[$key]['activated'] == true) {
            if (in_array($key, ['es-MX', 'es-CL', 'es-AR'])) {
                continue;
            }
            $locales[] = $key;
        }
    }

    return $locales;
}

/*
 * return an array of locales that have all strings translated
 * for a .lang file.
 *
 * dotlangTranslated()
 * @param array
 * @return array
 */
function dotlangTranslated($arr)
{
    $locales = [];
    foreach ($arr as $key => $val) {
        if ($arr[$key]['Identical'] == 0) {
            if (in_array($key, ['es-MX', 'es-CL', 'es-AR'])) {
                continue;
            }
            $locales[] = $key;
        }
    }

    return $locales;
}

function marketplaceStatus($dataUrl)
{
    $marketplaceData = getJsonArray($dataUrl);
    $status = array();
    $projectsNames = ['fireplace', 'zamboni', 'webpay', 'commbadge', 'rocketfuel'];
    foreach ($marketplaceData as $locale => $projects) {
        foreach ($projectsNames as $name) {
            if (isset($projects[$name])) {
                $status[$locale][$name] = round($projects[$name]['percentage']);
            } else {
                $status[$locale][$name] = '--';
            }
        }
    }

    return $status;
}


function getGaiaCompletion($gaia)
{
    $completion = [];

    foreach ($gaia as $value) {
        if (array_key_exists('tree', $value)
            && in_array(
                $value['tree'],
                ['gaia-community', 'gaia', 'gaia-v1_1', 'gaia-v1_2']
            )
        ) {
            $completion[$value['locale']] = $value['completion'];
        }
    }

    ksort($completion);

    return $completion;
}


function normalizeGaiaLocales($gaia)
{
    $map = [
        'es'      => 'es-ES',
        'pa'      => 'pa-IN',
        'pt'      => 'pt-BR',
        'sr-Cyrl' => 'sr',
    ];

    foreach ($map as $old => $new) {
        if (array_key_exists($old, $gaia)) {
            $gaia[$new] = $gaia[$old];
            unset($gaia[$old]);
        }
    }

    return $gaia;
}

function cacheUrl($url, $time = CACHE_EXPIRE)
{
    $cache = CACHE . sha1($url) . '.cache';
    if (is_file($cache)) {
        $age = $_SERVER['REQUEST_TIME'] - filemtime($cache);
        if ($age < $time) {
            return $cache;
        }
    }

    $file = file_get_contents($url);
    file_put_contents($cache, $file);
    return $cache;
}



/*
 * Check if $string starts with the $start string
 *
 * @param $string string
 * @param $start string
 * @return boolean
 */
function startsWith($string, $start)
{
    return !strncmp($string, $start, strlen($start));
}
