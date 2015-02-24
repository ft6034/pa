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
<title>進步排行榜</title>
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
	
	//進步排行榜
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#BE6100; background-color:#FFAC55'>";
	echo "　進步排行榜　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE6100; background-color:#FFE7CD'><th>進步幅度</th><th>姓名</th></tr>";
	
	$sql_up = "SELECT MAX(m_id) FROM rework";
	$result_up = mysql_query($sql_up,$pa);
	if(!$result_up)die("執行SQL命令失敗_up");
	$row_up = mysql_fetch_assoc($result_up);
	$max_mid = $row_up["MAX(m_id)"] - 3;
	
	$sql_up = "SELECT stu.s_name,rework.up_point FROM rework,stu WHERE rework.s_id=stu.s_id AND rework.up_point>0 AND rework.m_id>".$max_mid." ORDER BY up_point DESC";
	$result_up = mysql_query($sql_up,$pa);
	if(!$result_up)die("執行SQL命令失敗_up");
	while($row_up = mysql_fetch_assoc($result_up)){
		echo "<tr align='center' style='font-size:15;'><td>".$row_up["up_point"]."</td><td>".$row_up["s_name"]."</td></tr>";
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

