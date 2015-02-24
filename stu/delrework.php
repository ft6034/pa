<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>回收桶</title>
</head>
<body>
<?php
if(isset($_COOKIE['s_id']) && isset($_GET["wid"])){
	echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';

	$m_id = $_GET["mid"];
	$w_id = $_GET["wid"];

	require_once("../Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	

	$sql = "SELECT * FROM rework WHERE rew_id='".$w_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);
	$w_desc = $row["rew_desc"];

	//刪除記錄
	$sql = "DELETE FROM rework WHERE rew_id='".$w_id."'"; 
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");

	//刪除檔案
	unlink($w_desc); 

	echo "<script language='javascript'>";
	echo "  alert('已刪除!請重新繳交修正後作品');";
	echo "parent.location.href='stu.php';";
	echo "</script>";
}
else{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}

?>
</body>
</html>