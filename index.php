<?php

require_once __DIR__ .'/includes/init.php';

ob_start();

foreach ($gaiaStatus as $key => $val) {
    if ($val >= 85) {
        $temp_done[] = $key;
    } elseif ($val >= 80) {
        $temp_inprogress[] = $key;
    }
}

echo '<table>';
echo '<tr>';
echo '<th></th>';

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
