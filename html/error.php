<html>
<head>
<link rel='icon' href='src/ic.png' type='image/x-icon'/>
<title>OJ7 - Error</title>
</head>
<body>
<?php
include('oj-header.php');
$lref = $_SERVER['HTTP_REFERER'];
if (strstr($lref, "error") == false && strstr($lref, "si") == false)
	setcookie("lurl", $_SERVER['HTTP_REFERER']);
?>
<table align='center' border='0' width='800px'>
<tr>
<td style='color: Red; font-size: 30px;' align='center'>
<?php
echo $_GET['word'];
?>
</td>
</tr>
</table>
<?php
include("oj-footer.php");
?>
</body>

</html>
