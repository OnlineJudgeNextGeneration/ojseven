function inarr(key, arr) {
	for (var i = 0; arr[i] != undefined; ++ i)
		if (key == arr[i])
			return true;
	return false;
}

function listall() {
	var fbeg=fliterbeg.value;
	var fend=fliterend.value;
	var speit = document.getElementById('specify').value.split(',');
	if (speit[0]. length == 8)
		fend = '00000000';
	var srat=(fbeg=='00000000'&&fend=='99999999');
	var text="";
	var col=new Array();
	col[0]='#eeffee';
	col[1]='#eeeeff';
	text=text+"<table width='100%' border='0' align='center'>";
	text=text+"<tr style='text-align:center;background-color:#3f3fff;color:white;'><td>Rank</td><td width='50%'>User</td>";
	if (srat) {
		var coi=0;
		text=text+"<td>Rounds</td><td>Rating</td>";
		for (var i=0,j=0;i<tot_u;++i) {
			if (ul[i].uname=='no_name' && !showgs)
				continue;
			text=text+"<tr style='text-align:center;background-color:"+col[(coi++)%2]+"'>";
			text=text+"<td>"+(i+1)+"</td>";
			if (ul[i].uname=='no_name')
				text=text+"<td><a href='cnt.php?uid="+ul[i].uid+"&fbeg="+fbeg+"&fend="+fend+"'>"+ul[i].uid+" (<font style='color:red'>Guest</font>)</a></td>";
			else if (ul[i].uname==cur_uid)
				text=text+"<td><a href='cnt.php?uid="+ul[i].uid+"&fbeg="+fbeg+"&fend="+fend+"' style='color:green;'>"+ul[i].uid+"</a></td>";
			else
				text=text+"<td><a href='cnt.php?uid="+ul[i].uid+"&fbeg="+fbeg+"&fend="+fend+"'>"+ul[i].uid+"</a></td>";
			text=text+"<td>"+ul[i].tot_c+"</td>";
			text=text+"<td>"+ul[i].rating+"</td>";
		}
	}
	else {
		var coi=0;
		for (var i=0;i<tot_c;++i)
			if ((cid[i]>=fbeg && cid[i]<=fend)|| inarr(cid[i], speit))
				text=text+"<td><a style='color:yellow' href='uc.php?cid="+cid[i]+"'>"+cid[i]+"</a></td>";
		text=text+"<td>Total</td></tr>";
		var tsco=new Array(),cod=new Array();
		var n=0;
		for (var i=0;i<tot_u;++i) {
			if (ul[i].uname=='no_name' && !showgs)
				continue;
			var allz=1;
			tsco[i]=0;
			for (var j=0;j<tot_c;++j) {
				if ((cid[j]>=fbeg && cid[j]<=fend)|| inarr(cid[j], speit))
					if (ul[i].csco[j]>-1) {
						tsco[i]+=ul[i].csco[j];
						allz=0;
					}
			}
			if (allz==1)
				continue;
			cod[n]=i;
			n++;
		}
		for (var i=0;i<n;++i)
			for (var j=i+1;j<n;++j)
				if (tsco[cod[i]]<tsco[cod[j]]) {
					var h=cod[i];
					cod[i]=cod[j];
					cod[j]=h;
				}
		var lsrk = 0;
		for (var ti=0;ti<n;++ti) {
			var i=cod[ti];
			var rk = ti + 1;
			if (ti > 0 && tsco[i] == tsco[cod[ti - 1]])
				rk = lsrk;
			lsrk = rk;
			text=text+"<tr style='text-align:center; background-color:"+col[(coi++)%2]+"'><td>"+(rk)+"</td><td><a href='cnt.php?uid="+ul[i].uid+"&fbeg="+fbeg+"&fend="+fend+"'>"+ul[i].uid;
			if (ul[i].uname=='no_name')
				text=text+"(<font style='color:red'>"+ul[i].uname+"</font>)";
			else
				text=text+"(<font style='color:green'>"+ul[i].uname+"</font>)";
			text=text+"</a></td>";
			for (var j=0;j<tot_c;++j)
				if ((cid[j]>=fbeg && cid[j]<=fend)|| inarr(cid[j], speit))
					if (ul[i].csco[j]>-1)
						text=text+"<td>"+ul[i].csco[j]+" ("+ul[i].crk[j]+")</td>";
					else
						text=text+"<td>---</td>";
			text=text+"<td>"+tsco[i]+"</td></tr>";
		}
	}
	text=text+"</table>";
	document.getElementById("showplace").innerHTML=text;
}

function fliterchg() {
	var fbeg=fliterbeg.value;
	var fend=fliterend.value;
	var text="cnt.php?fbeg="+fbeg+"&fend="+fend;
	window.location.href=text;
	listall();
}

listall();
