<html>
<head>
<title>OJ7 - Sign up</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include('oj-header.php');
?>

<div align='center' width='800px'>
<?php

function checkID($x) {
	$len = strlen($x);
	if ($len == 0 || $len > 20)
		return false;
	for ($i = 0; $i < $len; ++ $i)
		if (($x[$i] > 'z' || $x[$i] < 'a') && ($x[$i] > '9' || $x[$i] < '0') && ($x[$i] != '_'))
			return false;
	return true;
}

if ($_GET['cmd'] == 'recv') {
	$uid = $_POST['uid'];
	$uname = $_POST['uname'];
	$grade = $_POST['grade'];
	if (!checkID($uname))
		header("Location: error.php?word=Please use real name!");
	else {
		$fln = "./users/". $uid. ".uinfo";
		if (is_file($fln))
			header("Location: error.php?word=User id already used");
		$opf = fopen($fln, "w");
		fprintf($opf, "%s\n%s\n", $uname, $grade);
		fclose($opf);
		echo "<font style='font-size: 24px'>";
		echo "Successfully signed up<br/>";
		echo "User id: ". $uid. "<br/>";
		echo "Name: ". $uname. "<br/>";
		echo "Graudate year: ". $grade. "<br/>";
		echo "</font>";
	}
}
else {
	include("forms/signup.php");
}
?>
</div>

<?php
include('oj-footer.php');
?>
</body>
</html>
