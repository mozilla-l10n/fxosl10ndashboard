<?php if (!defined('INIT')) die; ?>
<!doctype html>
<html>
<head>
	<title><?=$pageTitle?></title>
	<meta charset="utf-8">
	<link href="<?=$requestURL ?>media/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="<?=$requestURL ?>media/css/styles.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="<?=$requestURL ?>media/js/sorttable.js"></script>
</head>
<body class="base">
<?=$content;?>
	<table class="floatL">
        <caption>Color codes</caption>
		<tbody>
			<tr>
				<td class="done"></td>
				<td>Done</td>
			</tr>
			<tr>
				<td class="missing"></td>
				<td>Missing</td>
			</tr>
			<tr>
				<td class="inprogress"></td>
				<td>In progress</td>
			</tr>
			<tr>
				<td class="bonus"></td>
				<td>Bonus locale</td>
			</tr>
			<tr>
				<td class="shipped"></td>
				<td>Locale shipped</td>
			</tr>
			<tr>
				<td class="automated"></td>
				<td>Project which data is automatically generated</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
