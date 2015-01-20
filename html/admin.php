<html>
<head>
<title>OJ7 - Admin</title>
</head>
<body>
<?php
include('oj-header.php');
?>

<script>
function clritem(iid) {
	document.getElementById(iid).innerHTML="";
	//setTimeout("window.location.reload()",1);
}
</script>

<div class='idiv'>
<?php
function check_key($key) {
	$pf = fopen("./conf/admin_key.conf", "r");
	list($k0) = fscanf($pf, "%s");
	fclose($pf);
	return $k0 == $key;
}
function read_file($fln) {
	if (!is_file($fln))
		return "";
	$ipf = fopen($fln, "r");
	$ret = fread($ipf, 2048);
	fclose($ipf);
	return $ret;
}
function read_fline($fln) {
	if (!is_file($fln))
		return "";
	$ipf = fopen($fln, "r");
	list($ret) = fscanf($ipf, "%s");
	fclose($ipf);
	return $ret;
}
function write_file($filename, $item) {
	$pf = fopen($filename, "w");
	//echo "<pre>Text to ". $filename. "\n". $item. "</pre>";
	fwrite($pf, $item);
	fclose($pf);
	return true;
}
function write_cfg($cid, $pid, $ptitle) {
	if (!is_dir("./data/". $cid)) {
		if (!mkdir("./data/". $cid)) {
			echo "Mkdir failed";
			return false;
		}
	}
	$pf = fopen(("data/". $cid. "/". $pid. ".cfg"), "w");
	//fputs($pf, $ptitle);
	fprintf($pf, "%s\n%s.in\n%s.out\n", $ptitle, $ptitle, $ptitle);
	fprintf($pf, "%s/%s%%d.in\n", $ptitle, $ptitle);
	fprintf($pf, "%s/%s%%d.out\n", $ptitle, $ptitle);
	fprintf($pf, "1 10\n1000 128\n");
	fclose($pf);
	return true;
}

function write_ccfg($cid) {
	if (!is_dir("./data/". $cid)) {
		if (!mkdir("./data/". $cid)) {
			echo "Mkdir failed";
			return false;
		}
	}
	$pf = fopen(("data/". $cid. "/.contcfg"), "w");
	fprintf($pf,"stat 1");
	fclose($pf);
}

if (!strlen($_GET['cmd'])) {
	include("forms/adminlist.php");
}
else if ($_GET['cmd'] == 'ccont') {
	include("forms/ccont.php");
}
else if ($_GET['cmd'] == 'ccont_get') {
	if (!check_key($_POST['key'])) {
		header("Location: error.php?word=Wrong key");
		return;
	}
	else {
		if (strlen($_POST['cid']) > 0)
			if (!write_file("conf/cont.conf", $_POST['cid']))
				echo "Contest id error<br/>";
		$cid = read_fline("./conf/cont.conf");
		if (strlen($_POST['etime']) == 5) {
			if (!write_file("./conf/time.conf", $_POST['etime']))
				echo "End time error<br/>";
		}
		else
			echo "End time not changed<br/>";
		for ($i = 'a'; $i <= 'c'; ++ $i)
			if (!is_file("./data/".$_POST['cid']."/".$i.".cfg"))
				write_cfg($cid, $i, $_POST[$i]);
		if (!is_file("./data/".$_POST['cid']."/.contcfg"))
			write_ccfg($cid);
		include("forms/ccont.php");
		echo "Changed<br/>";
	}
}
else if ($_GET['cmd'] == 'cprob') {
	include("./forms/cprob.php");
}
else if ($_GET['cmd'] == 'cprob_get') {
	if (!check_key($_POST['key'])) {
		header("Location: error.php?word=Wrong key");
		return;
	}
	else {
		$cid = read_fline("./conf/cont.conf");
		for ($i = 'a'; $i <= 'c'; ++ $i)
			if (strlen($_POST[$i]) > 0) {
				$cfln = ("./data/". $cid. "/". $i. ".cfg");
				write_file($cfln, $_POST[$i]);
			}
		include("./forms/cprob.php");
		echo "Changed<br/>";
	}
}
else if ($_GET['cmd'] == 'cdwl') {
	include("forms/cdwl.php");
}
else if ($_GET['cmd'] == 'cdwl_get') {
	if (!check_key($_POST['key'])&&strlen($_POST['dwl'])>0) {
		header("Location: error.php?word=Wrong key");
		return;
	}
	else {
		if (strlen($_POST['dwl'])>0) {
			write_file($_POST['filename'], $_POST['dwl']);
			include("forms/cdwl.php");
			echo "Changed<br/>";
		}
		else  {
			include("forms/cdwl.php");
			echo "Opened<br/>";
		}
	}
}
else if ($_GET['cmd'] == 'rcmd') {
	include("forms/rcmd.php");
}
else if ($_GET['cmd'] == 'rcmd_get') {
	if (!check_key($_POST['key'])) {
		header("Location: error.php?word=Wrong key");
		return;
	}
	else {
		$pf = fopen(".runrequire", "w");
		fprintf($pf, "%s\n", ($_POST['cmdline']."\n"));
		fclose($pf);
		include("forms/rcmd.php");
		echo "Command: ". $_POST['cmdline']. "<br/>";
		echo "Run<br/>";
	}
}
?>
</div>
<?php
include("oj-footer.php");
?>
</body>
</html>