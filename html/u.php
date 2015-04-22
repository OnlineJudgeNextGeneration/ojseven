<html>
<head>
<link rel='icon' href='src/ic.png' type='image/x-icon'/>
<title>OJ7 - Upload file</title>
</head>
<?php
include('oj-header.php');
?>
<body>
<?php
if (!$_SESSION['signedin']) {
	header("Location: error.php?word=Please sign in first");
	return;
}
?>

<center>
<div style='width:80%;'>
<?php
$corr=($_GET['cmd']=='correction');
$cid=0;
if ($corr) {
	$cid=$_GET['cid'];
}
else {
	$pf = fopen("conf/cont.conf","r");
	list($cid)=fscanf($pf,"%s");
	fclose($pf);
}
if (check_stat($cid) == 2)
	$corr = 1;
?>

<form action='uf.php<?php if ($corr) echo "?cmd=correction&cid=".$cid;?>' method='post' enctype='multipart/form-data'>
<table width='100%' border='0'>
<?php
function gettl() {
	$pf = fopen("conf/time.conf", "r");
	list($hh, $mm) = fscanf($pf, "%d:%d");
	fclose($pf);
	$tl = $hh * 10000 + $mm * 100;
	return $tl;
}
function check_time() {
	$tc = date("his");
	//echo $tc;
	if (date("a") == "pm" && $tc < 120000)
		$tc += 120000;
	//echo $tc;
	$c_ipf = fopen("conf/cont.conf", "r");
	$cid = fread($c_ipf, 8);
	fclose($c_ipf);
	if (check_stat($cid) != 1)
		return false;
	if ($tc <= gettl())
		return true;
	else
		return false;
}

if (!$corr && !check_time()) {
	header("Location: error.php?word=Out of submit time");
	return;
}
echo("<tr height='30px'>");
echo("<td width='200px'>Contest id</td>\n");

if ($corr)
	echo("<td>". $cid. "(Correction)</td></tr>\n");
else
	echo("<td>". $cid. "</td></tr>\n");
?>

<?php
echo("<tr height='30px'>");
echo("<td width='200px'>Submit time limit</td>\n");
$ttl = gettl();
$wx = 'am';
if ($ttl >= 120000) {
	$wx = 'pm';
	if ($ttl >= 130000)
		$ttl -= 120000;
}
printf("<td>%02d:%02d:00 %s</td></tr>", $ttl / 10000, $ttl % 10000 / 100, $wx);
?>
</table>

<table width='100%' align='center'>
<tr height='30px'>
<td width='200px'><label for 'name'> Username </td>
</font></label></td>
<td><?php echo getuid(); ?></td>
</td>
</tr>
<?php
$ccfg = readccfg("../data/".$cid."/.contcfg");
for ($i=1;$i<=$ccfg['totprob'];++$i) {
	echo("<tr height='30px'><td><label for='f". $i. "'>");
	echo("Code ". $i. "</label>");
	echo('</td><td>');
	echo("<input type='file' name='f". $i."' id='f". $i. "' />");
	echo("</td></tr>");
	echo("\n");
}
?>
</table>
<input type='submit' name='submit' value='Submit' align='center'/>
</form>
</div>
</center>

<?php
include('oj-footer.php');
?>
</body>
</html>
