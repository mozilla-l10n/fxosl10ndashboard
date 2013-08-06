<?php
if (!defined('INIT')) die;


echo '<table>';
echo '<tr>';
echo '<th></th>';

foreach ($projects as $key => $val) {
    $th = ($val['automated'] == true) ? '<th class="automated">' : '<th>';

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
foreach ($projects as $key => $val) {
        echo '<td>' .  $val['owners'] . '</td>';
}
echo '</tr>';

foreach ($locales as $locale) {

    $active = function($projects, $key) use ($locale, $gaiaStatus, $marketplace) {

        $cell = '';

        if (in_array($locale, $projects[$key]['requested'])
            && !in_array($locale, $projects[$key]['done'])
            && !in_array($locale, $projects[$key]['inprogress'])) {
            $class = 'missing';
        } elseif (!in_array($locale, $projects[$key]['requested'])
                   && in_array($locale, $projects[$key]['done'])) {
            $class = 'bonus';
        } elseif (in_array($locale, $projects[$key]['requested'])
                  && in_array($locale, $projects[$key]['done'])) {
            $class = 'done';
        } elseif (in_array($locale, $projects[$key]['requested'])
                  && in_array($locale, $projects[$key]['inprogress'])) {
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
                if (in_array($locale, $projects[$key]['requested'])) {
                    $class = 'done';
                } else {
                    $class = 'bonus';
                }
            } elseif (array_sum($marketplace[$locale]) > 270) {
                $class = 'inprogress';
            } elseif (in_array($locale, $projects[$key]['requested'])) {
                $class = 'missing';
            } else {
                $class = '';
            }

        }

        return '<td class="' . $class . '">' . $cell . '</td>';
    };



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

    echo '<tr>';
    echo '<th class="' .  $locale_status($locale) . '">' . $locale . '</th>';
    foreach ($projects as $key => $val) {
        echo $active($projects, $key);
    }
    echo '</tr>';
}
echo '</table>';
