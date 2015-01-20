<html>
<head>
<title>OJ7 - F.A.Q.</title>
</head>
<body>
<?php
include('oj-header.php');
?>
<table width="800px" align='center'>

<tr><td><pre>
<font color='red'>What is OJ7?
<font color='blue'>OJ7 is an online judge system by OIers from Grade 2015, 2016 and 2017.
<hr/></pre></td></tr>

<tr><td><pre>
<font color='red'>How does OJ7 work?
<font color='blue'>The server of OJ7 locates in Room 404. OIers maintain it essentially.
<hr/></pre></td></tr>

<tr><td><pre>
<font color='red'>What is the judging environment?
<font color='blue'>Intel(R) Xeon(R) CPU X3440  2.53GHz
4GB RAM
Xubuntu 14.04 LTS
gcc 4.8.2
fpc 2.6.2
<hr/></pre></td></tr>

<tr><td><pre>
<font color='red'>Compile command:
<font color='blue'>for c++: g++ %s.cpp
for c: gcc %s.c
for pascal: fpc %s.pas
<hr/></pre></td></tr>

<tr><td><pre>
<font color='red'>Config file format of OJ7
<font color='blue'>a/b/c.cfg
[problem name]
[user input file name]
[user output file name]
[std input file format(%d stands for test case id)]
[std output file format(%d stands for test case id)]
[first test case id] [last test case id]
[time limit(ms)] [memory limit(ms)]
(spj [spj cmd])
(ansonly)
<hr/></pre></td></tr>

<tr><td><pre>
<font color='red'>Special judge format of OJ7
<font color='blue'>Command: spj arg1 arg2 .. arg6
arg1: Standrad input
arg2: Player output
arg3: Standrad output
arg4: Full score
arg5: (Necessary) Player score
arg6: (Not necessary) Extra info
(This is the same as the one of Lemon)
<hr/></pre></td></tr>

</table>
<?php
include('oj-footer.php');
?>
</body>
</html>
