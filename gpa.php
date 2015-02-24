<?php
session_start();

$s_id ="";
if(isset($_COOKIE["s_id"])) {
	$s_id = $_COOKIE["s_id"];
}

date_default_timezone_set('Asia/Taipei');
$now_time = date("Y年m月d日 H:i");

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>優秀評審</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="sb303/shadowbox.css" />
<script type="text/javascript" src="sb303/shadowbox.js"></script>
<script type="text/javascript">

    Shadowbox.init();

</script>

</head>

<body bgcolor="white">
<center>
<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td background="./img/bg-lt.png" width="60" height="60"></td>
	<td background="./img/bg-top.png" width="800" height="60"></td>
	<td background="./img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 -->
	
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	<!-- 內容區 start-->
<?php	
	require_once("./Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	
	
	//優秀評審
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#BE0200; background-color:#FF7573'>";
	echo "　優秀評審　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE0200; background-color:#FFCECD'><th>好評數</th><th>姓名</th></tr>";
	$sql = "SELECT s_name, pa_point FROM stu WHERE pa_point>0 ORDER BY pa_point DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗");
	while($row = mysql_fetch_assoc($result)){
		echo "<tr align='center' style='font-size:15;'><td>".$row["pa_point"]."</td><td>".$row["s_name"]."</td></tr>";
	}

	echo "</table>";

	
?>	
	<!-- 內容區 end-->
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-lb.png" width="60" height="60"></td>
	<td background="./img/bg-bottom.png" height="60"></td>
	<td background="./img/bg-rb.png" width="60" height="60"></td>
</tr>

</table>
</center>
</body>
</html>
