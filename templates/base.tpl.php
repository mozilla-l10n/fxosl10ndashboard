<?php if (!defined('INIT')) die; ?>

<html>
<head>
<title>locales</title>
<style>
body {
  font-size:12px;
}
table, td, th {
    border: 1px solid lightgray;
    border-collapse: collapse;
    padding:4px 8px;
    font-size:14px;
    font-weight:normal;
    text-align:center;
}

td {
    text-align:right;
}



.automated {
    background-color:rgb(220,220,220);
}

.missing {
    background-color: red;
    color:white;
    text-align:right;
}

.inprogress {
    background-color: orange;
    text-align:right;
}

.bonus {
    background-color: blue;
    color:white;
    text-align:right;
}

.done {
    background-color: green;
    color:white;
    text-align:right;
}

.shipped {
    background-color: purple;
    color:white;
}

.shipping-july {
    background-color: rgb(250, 0, 0);
    color:white;
}

.shipping-August {
    background-color: rgb(200, 50, 0);
    color:white;
}

.shipping-September {
    background-color: rgb(150, 100, 0);
    color:white;
}

.shipping-October {
    background-color: rgb(100, 100, 0);
    color:white;
}

.owner td {
    background-color: lightblue;
    text-align: center;
}
</style>

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
