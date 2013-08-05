<?php

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

