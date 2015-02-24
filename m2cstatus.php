<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

$now_date = date("Y.m.d");
$now_time = date("H.i.s",time()+(8*60*60));

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
//取目前的學年學期
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

$c_id = $_GET["cid"];
$m_id = $_GET["mid"];
$c_name = $_GET["cname"];
$m_name = $_GET["mname"];
$status = $_GET["status"];
//$m_grade = $_GET["grade"];
$m_grade = $_SESSION["m_grade"];

?>
<html>
<head>
<title>進度修改</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
//更新目前任務的狀態
$sql = "UPDATE m2c SET m2c_status='".$_GET["status"]."' WHERE m_id='".$_GET["mid"]."' AND c_id='".$_GET["cid"]."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗2");
else{
	echo "<script language='javascript'>";
	//echo "  alert('".$_GET["mname"]."-".$_GET["cname"]."的狀態設定為".$_GET["status"]."！');";
	echo "document.location.href='progressall.php?grade=".$m_grade."';";
	echo "</script>";
}
?>
</body>
</html>