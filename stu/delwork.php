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
	$now_date = date("Y.m.d");
	$now_time2 = date("H:i:s",time()+(8*60*60));

	require_once("../Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	

	$sql = "SELECT * FROM works WHERE w_id='".$w_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);

	//建立回收桶紀錄
	$sql_dw = "INSERT INTO delworks(del_id, w_name, w_desc, s_id, m_id, w_date, del_date)";
	$sql_dw .= " VALUES('".$row["w_id"]."', '".$row["w_name"]."', '".$row["w_desc"]."', '".$row["s_id"]."', '".$row["m_id"]."','".$row["w_date"]."','".$now_date." ".$now_time2."')";
	$result_dw = mysql_query($sql_dw,$pa);
	if(!$result_dw)die("執行SQL命令失敗_dw");


	//刪除記錄
	$sql = "DELETE FROM works WHERE w_id='".$w_id."'"; 
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");

	/*
	//刪除檔案
	unlink($w_desc[$wnum]); 
	*/

	//以s_id, m_id，從works查詢是否還有檔案
	$sql = "SELECT w_id FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗4");
	//if沒有檔案, 狀態改為未繳交
	if(mysql_num_rows($result)==0){
		$sql = "UPDATE progress2stu SET p_uploaded='0' WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";		
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗5");
		echo "<script language='javascript'>";
		echo "  alert('已刪除!請重新繳交作品');";
		echo "parent.location.href='stu.php';";
		echo "</script>";
	}
	else{
		echo "<script language='javascript'>";
		echo "  alert('已刪除!');";
		echo "document.location.href='show.php?mid=".$m_id."';";
		echo "</script>";
	}
}
else{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}

?>
</body>
</html>