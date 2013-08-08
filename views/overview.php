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

    if ($key == 'Firefox_os') {
        echo $th .  ucwords(str_replace('_', ' ', $key));
    } elseif ($key == 'marketplace') {
        echo $th .  ucwords(str_replace('_', ' ', $key)) . '<br><small>fireplace/zamboni/webpay</small>';
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

        return '               <td class="' . $class . '">' . $cell . '</td>' . "\n";
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
