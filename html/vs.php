<html>
<head>
<link rel='icon' href='src/ic.png' type='image/x-icon'/>
	<meta http-equiv="Content-Type" content="text/html; charset=unicode" />
<title>OJ7 - View state</title>
</head>
<body>
<?php
include('oj-header.php');
?>

<div align='center'>
<table align='center' width='80%'>
<tr><td>
<?php
$uid = $_GET['uid'];
$cid = $_GET['cid'];
$pid = $_GET['pid'];
echo("Contest id: ". $cid. "<br/>");
echo("User id: ". $uid. "<br/>");

function checkID($x) {
	$len = strlen($x);
	if ($len == 0 || $len > 20)
		return false;
	for ($i = 0; $i < $len; ++ $i)
		if ($x[$i] > 'z' || $x[$i] < '1')
			return false;
	return true;
}

$fln = "./data/". $cid. '/'. $pid. ".cfg";
$ipf = fopen($fln, "r");
$gtmp = fscanf($ipf, "%s");
list($pname) = $gtmp;
echo("Problem: ". $pname. "<br/>");

if (check_stat($cid) >= 2 || ($_SESSION['signedin'] && ($_SESSION['uid'] == $uid || is_admin($_SESSION['uid'])))) {
	$fln="./upload/".$cid."/".$uid."/".$pname;
	if (is_file($fln.".cpp"))
		$fln=$fln.".cpp";
	elseif (is_file($fln.".cc"))
		$fln=$fln.".cc";
	else if (is_file($fln.".c"))
		$fln=$fln.".c";
	else if (is_file($fln.".pas"))
		$fln=$fln.".pas";
	chmod($fln,0444);
	$fls=abs(filesize($fln));
	chmod($fln,0000);
	echo "Code length: ". $fls. " B<br/>";
}
?>
</td></tr>
</table>
<table width='80%'>
<?php
for ($j = 0; $j < 4; ++ $j)
	$gtmp = fscanf($ipf, "%s");
$gval = fscanf($ipf, "%d %d");
list($beg_n, $end_n) = $gval;
$tot_p = $end_n - $beg_n + 1;
$tott = 0;
$maxm = 0;
fclose($ipf);
if (is_dir("./upload/". $cid. "/". $uid. "/.ajtest")) {
	$fln = "./upload/". $cid. "/". $uid. "/.ajtest/". $pid. ".rs";
	if (!is_file($fln)) {
		header("Location: error.php?word=Judgement not finished");
	}
	else {
		$ipf = fopen($fln, "r");
		list($res) = fscanf($ipf, "%s");
		list($tot_sco) = fscanf($ipf, "%d");
		if ($res == 'CE') {
			echo "<tr><td></td><td>Compile error<br/>";
			if (is_file("./upload/". $cid. "/". $uid. "/.ajtest/compile". $pid. ".log")) {
				$fln=("./upload/". $cid. "/". $uid. "/.ajtest/compile". $pid. ".log");
				echo "<pre style='scode'>";
				showcodenl($fln);
				echo "</pre>";
			}
			echo "</td></tr>";
			$tot_sco=0;
		}
		else {
			$cs=1;
			$cols=Array("#eeffee","#eeeeff");
			for ($i = 0; $i < strlen($res); ++ $i) {
				$cs=1-$cs;
				echo "<tr style='background-color:".$cols[$cs]."'><td>Case #". ($beg_n + $i). "</td>";
				list($wd, $rtime, $rmem, $sco) = fscanf($ipf, "%s%d%d%d");
				echo "<td>";
				//echo "test line: ". $res. " ". $tot_sco. "<br/>"; 
				if ($wd[0] == 'A') {
					echo "<font style='color: green'>";
					$tott += $rtime;
					$maxm = max($maxm, $rmem);
				}
				elseif ($wd[0] == 'W')
					echo "<font style='color: red'>";
				elseif ($wd[0] == 'M' || $wd[0] == 'T')
					echo "<font style='color: darkorange'>";
				elseif ($wd[0] == 'R')
					echo "<font style='color: purple'>";
				elseif ($wd[0] == 'F')
					echo "<font style='color: white; background-color:black;'>";
				else
					echo "<font style='color:black'>";
				echo $wd. " </font>";
				echo "Time: <font style='color: blue'>". $rtime. "</font> ms ";
				echo "Memory: <font style='color: blue'>". $rmem. "</font> kb ";
				if ($sco<0)
					$sco=0;
				echo "Score: <font style='color: blue'>". $sco. "</font>";
				if ($wd[0] == 'W') {
					if (is_file("./upload/". $cid. "/". $uid. "/.ajtest/diff". $pid. ($i + $beg_n). ".log")) {
						echo "<pre class='wcode'>Diffrence:\n";
						$d_ipf = fopen(("./upload/". $cid. "/". $uid. "/.ajtest/diff". $pid. ($i + $beg_n). ".log"), "r");
						while (!feof($d_ipf))
							echo htmlspecialchars(fgets($d_ipf));
						fclose($d_ipf);
						echo "</pre>";
					}
				}
				echo "</td></tr>";
			}
		}
		fclose($ipf);
		echo "</table>";
		echo "<div style='width:80%; text-align:left;'>";
		echo "Total score: ".$tot_sco."<br/>";
		echo "Total time: ".$tott." ms<br/>";
		echo "Max memory: ".$maxm." KB<br/>";
		echo "</div>";
	}
}
else {
	$fln = "./upload/". $cid. "/". $uid. "/". "res". $pid. ".rs";
	if (is_file($fln)) {
		$ipf = fopen($fln, "r");
		$gtmp = fscanf($ipf, "%d");
		list($cval) = $gtmp;
		$gtmp = fscanf($ipf, "%d");
		if ($cval != 0) {
			echo("<tr><td>");
			if ($cval == 2)
				echo("No such file");
			else if ($cval == 4)
				echo("Dangerous word");
			else {
				echo("Compile error!<br/>Compiler info:<br/>");
				$cfln = "./upload/". $cid. "/". $uid. "/ajtest/compile". $pid. ".log";
				echo("<pre style='lcode'>");
				if (is_file($cfln)) {
					showcode($cfln);
				}
				else {
					echo "Compile log file not found!";
				}
				echo("</pre>");
			}
		}
		else {
			for ($i = 0; $i < $tot_p; ++ $i) {
				echo("<tr><td>Test case #". ($i + 1). "</td>");
				$gres = fgets($ipf);
				$gtmp = fscanf($ipf, "%d%d");
				list($sco, $rtime) = $gtmp;
				echo("<td>". $gres. "<br/>");
				echo("Time = ". $rtime. " ms<br/>");
				if ($gres[0] == 'W') {
					$num = $i + $beg_n;
					$lpath = "./upload/". $cid. "/". $uid. "/ajtest/diff". $pid. $num. ".log"; 
					$dipf = fopen($lpath, "r");
					echo("Diff info:<pre><br/>");
					for ($j = 0; $j < 50 && !feof($dipf); ++ $j) {
						echo("\t");
						echo(htmlspecialchars(fgets($dipf)));
						echo("<br/>");
					}
					echo("</pre>");
					fclose($dipf);
				}
				echo("</td>");
			}
		}
		fclose($ipf);
	}
	else {
		echo "<div style='color: blue;' >Pending</div>";
	}
}

?>
</table>

<div style='text-align:left;width:80%;'>
<?php
echo "<p>Code</p>";
if (check_stat($cid) >= 2 || ($_SESSION['signedin'] && ($_SESSION['uid'] == $uid || is_admin($_SESSION['uid'])))) {
	printcode($cid,$uid,$pname);
}
?>
</div>

</div>

<?php
include('oj-footer.php');
?>
</body>
</html>
