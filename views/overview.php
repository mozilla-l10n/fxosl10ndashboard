<?php
if (!defined('INIT')) die;

echo '<table  class="sortable">';
echo '<tr>';
echo '<th  class="sorttable_numeric">Priority</th><th>Locale</th>';

foreach ($projects as $key => $val) {
    $th = ($val['automated'] == true) ? '<th class="automated">' : '<th>';

    if ($key == 'Firefox_os') {
        echo $th .  ucwords(str_replace('_', ' ', $key));
    } elseif ($key == 'marketplace') {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '<br><small>fireplace/zamboni/webpay</small>';
    } else {
        echo $th .  ucwords(str_replace('_', ' ', $key));
    }

    echo '<span class="owner">' . $val['owners'] . '</span></th>';
}

echo '</tr>';


foreach ($locales as $locale) {

    $active = function($projects, $key) use ($locale, $gaiaStatus, $marketplace) {

        $cell = '';

        if (in_array($locale, $projects[$key]['requested'])
            && !in_array($locale, $projects[$key]['done'])
            && !in_array($locale, $projects[$key]['inprogress'])) {
            $class = 'missing';
            $cell = 0;
        } elseif (!in_array($locale, $projects[$key]['requested'])
                   && in_array($locale, $projects[$key]['done'])) {
            $class = 'bonus';
            $cell = 1;
        } elseif (in_array($locale, $projects[$key]['requested'])
                  && in_array($locale, $projects[$key]['done'])) {
            $class = 'done';
            $cell = 2;
        } elseif (in_array($locale, $projects[$key]['requested'])
                  && in_array($locale, $projects[$key]['inprogress'])) {
            $class = 'inprogress';
            $cell = 3;
        } else {
            $class = '';
        }

        if ($key == 'Firefox_os' && array_key_exists($locale, $gaiaStatus)) {
            $cell = $gaiaStatus[$locale]. '%';
            $class .= ' showCell';
        }

        if ($key == 'marketplace' && array_key_exists($locale, $marketplace)) {
            $cell = implode('/', $marketplace[$locale]);

            if ( $marketplace[$locale]['fireplace'] == 100
                && $marketplace[$locale]['zamboni'] >= 99
                && $marketplace[$locale]['webpay'] >= 94)
            {
                if (in_array($locale, $projects[$key]['requested'])) {
                    $class = 'done showCell';
                } else {
                    $class = 'bonus showCell';
                }
            } elseif (array_sum($marketplace[$locale]) > 270) {
                $class = 'inprogress showCell';
            } elseif (in_array($locale, $projects[$key]['requested'])) {
                $class = 'missing showCell';
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
    echo '<th>' . $localesPriority[$locale] . '</th>';
    echo '<th class="' .  $locale_status($locale, $shipped) . '">' . $locale . '</th>';
    foreach ($projects as $key => $val) {
        echo $active($projects, $key);
    }
    echo '</tr>';
}
echo '</table>';
