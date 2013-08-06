<?php if (!defined('INIT')) die; ?>
<!doctype html>
<html>
<head>
<title><?=$pageTitle?></title>
<meta charset="utf-8">
<link href="<?=$_SERVER['REQUEST_URI'] ?>media/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?=$_SERVER['REQUEST_URI'] ?>media/css/styles.css" media="screen" rel="stylesheet" type="text/css" />
<script src="<?=$_SERVER['REQUEST_URI'] ?>media/js/sorttable.js"></script>
</head>
<body>
<?=$content;?>
<br>
<table class="floatL">
<caption>Color codes</caption>
<tr><td class="done"></td><td>Done</td></tr>
<tr><td class="missing"></td><td>Missing</td></tr>
<tr><td class="inprogress"></td><td>In progress</td></tr>
<tr><td class="bonus"></td><td>Bonus locale</td></tr>
<tr><td class="shipped"></td><td>Locale shipped</td></tr>
<tr><td class="automated"></td><td>Project which data is automatically generated</td></tr>
</table>
</body>
</html>
