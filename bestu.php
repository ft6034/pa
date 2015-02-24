<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

if(isset($_POST["s_id"])){
	setcookie("s_id",$_POST["s_id"]);
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
<title>模擬學生頁</title>
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
		<td class="title">模擬學生</td>
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
	<table class="outtable">
    <tr>
    <td colspan="2" align="center" valign="middle">
      
        <table width="0">
			<tr>
				<th>請選擇要模擬的學生帳號</th>
			</tr>
			<tr>
				<form action="bestu.php" method="post" enctype="multipart/form-data">
<?php
		echo "<td class='td-solid'>
				<select name='s_id' size='1'>";
		$sql = "SELECT s_id,s_name FROM stu WHERE s_id LIKE 't_".$_SESSION["t_id"]."%' ORDER BY s_classnums";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗1");
		while($row = mysql_fetch_assoc($result)){
			echo '<option value="'.$row["s_id"].'">'.$row["s_name"];
		}
		echo "</td>";
		echo "<td>
				<input type=\"submit\" value=\"確定\">
			</td>";

?>
				</form>
			</tr>
			<tr>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
        <hr>
		</td>
	</tr>
	</table>
	
	
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