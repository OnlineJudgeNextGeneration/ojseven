<form action='su.php?cmd=recv' method='post' enctype='multipart/form-data'>
<table align='center' width='800px' border='0'>
<tr>
<td><label>Username </label></td>
<td><input type='text' name='uid' id='uid' size='50px'/></td>
</tr>

<tr>
<td><label>Real name <br/>(Pinyin in lowercase letter)</label></td>
<td><input type='text' name='uname' id='uname' size='50px'/></td>
</tr>

<tr>
<td><label>Password</label></td>
<td><input type='password' name='passwd'/></td>
</tr>

<tr>
<td><label>Repeat password</label></td>
<td><input type='password' name='reppasswd'/></td>
</tr>

<tr>
<td><label>Graduate year <br/></label></td>
<td><input type='text' name='grade' id='grade' size='50px'/></td>
</tr>
</table>
<input type='submit' value='Submit'/>
</form>
