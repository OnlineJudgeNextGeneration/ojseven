<?php
$cipf=fopen("./conf/cont.conf","r");
list($ccid)=fscanf($cipf,"%s");
fclose($cipf);
?>
<p class='infot'> Admin menu </p>
<table align='center'>
<tr><td><a href='admin.php?cmd=ccont' class='infot'>Config contest</a></td></tr>
<tr><td><a href='admin.php?cmd=cdwl_get&filename=./data/<?php echo $ccid; ?>/.contcfg' class='infot'>Config contest status</a></td></tr>
<tr><td><a href='admin.php?cmd=cprob' class='infot'>Config problems</a></td></tr>
<tr><td><a href='admin.php?cmd=rcmd' class='infot'>Run a command</a></td></tr>
<tr><td><a href='adminfl.php?cmd=udata' class='infot'>Upload data</a></td></tr>
<tr><td><a href='adminfl.php' class='infot'>Upload file</a></td></tr>
<tr><td><a href='admin.php?cmd=cdwl' class='infot'>Edit a file</a></td></tr>
</table>
