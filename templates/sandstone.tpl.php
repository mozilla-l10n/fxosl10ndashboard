<?php if (!defined('INIT')) die; ?>
<!doctype html>
<html>
<head>
<title><?=$pageTitle?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" media="screen,projection,tv" href="//mozorg.cdn.mozilla.net/media/css/tabzilla-min.css?build=42497ef" />
<link rel="stylesheet" media="screen,projection,tv" href="http://www.mozilla.org//media/css/responsive-min.css?build=3bdd4c7" />
<link href="<?=$_SERVER['REQUEST_URI'] ?>media/css/styles.css" media="screen" rel="stylesheet" type="text/css" />
<script src="<?=$_SERVER['REQUEST_URI'] ?>media/js/sorttable.js"></script>

</head>
<body class="sand">
    <div id="outer-wrapper">
        <div id="content">
            <header id="masthead"><a href="https://www.mozilla.org" id="tabzilla">Mozilla</a></header>
            <?=$content;?>
            <table>
                <caption>Color codes</caption>
                <tr><td class="done"></td><td>Done</td></tr>
                <tr><td class="missing"></td><td>Missing</td></tr>
                <tr><td class="inprogress"></td><td>In progress</td></tr>
                <tr><td class="bonus"></td><td>Bonus locale</td></tr>
                <tr><td class="shipped"></td><td>Locale shipped</td></tr>
                <tr><td class="automated"></td><td>Project which data is automatically generated</td></tr>
            </table>
        </div>
    </div>

<script src="//mozorg.cdn.mozilla.net/en-US/tabzilla/tabzilla.js?build=42497ef"></script>
</body>
</html>
