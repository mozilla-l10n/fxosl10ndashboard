<?php
if (!defined('INIT')) die;

echo '    <table class="table sortable">' . "\n";
echo '        <caption>L10n Mini Dashboard for Firefox OS</caption>' . "\n";
echo '        <thead>' . "\n";
echo '            <tr>' . "\n";
echo '                <th>Priority</th>' . "\n";
echo '                <th>Locale</th>' . "\n";


foreach ($projects as $key => $val) {
    $th = ($val['automated'] == true) ? '                <th class="automated">' : '                <th>';

    if (startsWith($key, 'Gaia')) {
        echo $th .  $projects[$key]['display_name'];
    } elseif ($key == 'marketplace') {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '<br><small>fireplace zamboni webpay commbadge rocketfuel</small>';
    } else {
        echo $th .  ucwords(str_replace('_', ' ', $key));
    }

    echo '</th>' . "\n";
}

echo '                <th>Comments</th>' . "\n";
echo '            </tr>' . "\n";
echo '        </thead>' . "\n";
echo '        <tbody>' . "\n";
foreach ($locales as $locale) {

    $active = function($projects, $key)
                use ($locale,
                     $gaia_status_community,
                     $gaia_status_l10n,
                     $gaia_status_1_1,
                     $gaia_status_1_2, $marketplace) {
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

        if ($key == 'Gaia_l10n' && array_key_exists($locale, $gaia_status_l10n)) {
            $cell = $gaia_status_l10n[$locale]. '%';
            $class .= ' showCell';
        }

        if ($key == 'Gaia_1_1' && array_key_exists($locale, $gaia_status_1_1)) {
            $cell = $gaia_status_1_1[$locale]. '%';
            $class .= ' showCell';
        }

        if ($key == 'Gaia_1_2' && array_key_exists($locale, $gaia_status_1_2)) {
            $cell = $gaia_status_1_2[$locale]. '%';
            $class .= ' showCell';
        }

        if ($key == 'marketplace' && array_key_exists($locale, $marketplace)) {
            $cell = implode(' / ', $marketplace[$locale]);

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

        return '               <td class="' . $class . '">' . $cell . '</td>' . "\n";
    };

    echo '           <tr>' . "\n";
    echo '               <td>' . $localeDetails[$locale]['priority'] . '</td>' . "\n";
    echo '               <td class="' .  $locale_status($locale, $localeDetails) . '">' . $locale . '</td>' . "\n";
    foreach ($projects as $key => $val) {
        echo $active($projects, $key);
    }
    echo '               <td>' . $localeDetails[$locale]['comment'] . '</td>' . "\n";
    echo '           </tr>' .  "\n";
}
echo '        <tbody>' .  "\n";
echo '        <tfoot>' .  "\n";

// Footer: external links
echo '           <tr class="externallinks">' . "\n";
echo '               <th colspan="2">Links -></th>' . "\n";
foreach ($projects as $key => $val) {
        echo '               <th><a href="' .  $val['link'] . '">' .  $val['link_description'] . '</a></th>' . "\n";
}
echo '               <th></th>' . "\n";
echo '           </tr>' . "\n";

// Footer: owners
echo '           <tr class="owner">' . "\n";
echo '               <th colspan="2">Owners -></th>' . "\n";
foreach ($projects as $key => $val) {
        echo '               <th>' .  $val['owners'] . '</th>' . "\n";
}
echo '               <th></th>' . "\n";
echo '           </tr>' . "\n";

echo '        </tfoot>' . "\n";
echo '    </table>' . "\n";
