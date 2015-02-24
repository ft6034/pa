<?php
session_start();
if(!isset($_SESSION["admin_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
else{
	$_SESSION["t_id"] = "teacher";
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

$_SESSION["ssyear"];

$now_date = date("Y.m.d");
$now_time = date("H.i.s",time()+(8*60*60));

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	

?>
<html>
<head>
<title>模擬教師頁</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td background="./img/bg-lt.png" width="60" height="60"></td>
	<td background="./img/bg-top.png" width="800" height="60">
	</td>
	<td background="./img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 start-->
	<table class="outtable">
	  <tr>
    <td colspan="2" class="menu">
	  [<a href="index.php">首頁</a>]
      [<a href="logout.php">登出系統</a>]
    </td>
	</tr>
	<tr>
		<td colspan="2" class="header2">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">模擬教師</td>
		<td class="function">&nbsp;</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	<!-- 標題區 end-->
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	
	
	<!-- 內容區 start-->
	
	
	<!-- 內容區 end-->
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-lb.png" width="60" height="60"></td>
	<td background="./img/bg-bottom.png" height="60">
	</td>
	<td background="./img/bg-rb.png" width="60" height="60"></td>
</tr>

</table>
</center>
</body>
</html>