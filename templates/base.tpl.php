<?php if (!defined('INIT')) die; ?>
<!doctype html>
<html>
<head>
<title><?=$pageTitle?></title>
<meta charset="utf-8">
<link href="<?=$_SERVER['REQUEST_URI'] ?>media/css/styles.css" media="screen" rel="stylesheet" type="text/css" />

</head>
<body>
<?=$content;?>
<br>
<table>
<caption>Color codes</caption>
<tr><td class="done"></td><td>Done</td></tr>
<tr><td class="missing"></td><td>Missing</td></tr>
<tr><td class="inprogress"></td><td>In progress</td></tr>
<tr><td class="bonus"></td><td>Bonus locale</td></tr>
<tr><td class="shipped"></td><td>Locale shipped</td></tr>
<tr><td class="shipping-July"></td><td>Locale shipping in July</td></tr>
<tr><td class="shipping-August"></td><td>Locale shipping in August</td></tr>
<tr><td class="shipping-September"></td><td>Locale shipping in September</td></tr>
<tr><td class="shipping-October"></td><td>Locale shipping in October</td></tr>
<tr><td class="automated"></td><td>Project which data is automatically generated</td></tr>
</table>
</body>
</html>
