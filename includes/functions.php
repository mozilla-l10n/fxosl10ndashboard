<?php
if (!defined('INIT')) die;

// Utility functions for the Dashboard

function getJsonArray($url) {
    return json_decode(file_get_contents($url), true);
}

function activated($arr) {
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

function marketplaceStatus($dataUrl)
{
    $marketplaceData = getJsonArray($dataUrl);
    $status = array();
    $projectsNames = ['fireplace', 'zamboni', 'webpay'];
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
    $temp = [];
    foreach ($gaia as $key => $value) {
        if (array_key_exists('tree', $value) && ($value['tree'] == 'gaia-community' or $value['tree'] == 'gaia') ) {
            $temp[$value['locale']] = $value['completion'];
        }
    }
    ksort($temp);
    return $temp;
}


function cacheUrl($url, $time = 600)
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
