<?php
$m_id = $_GET["mid"];
if (empty($_COOKIE['s_id'])){
	echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';
}
if($wnum==""){
	echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';
}
else{
	$wnum = $_GET[wnum]; //作品序號
}

require_once("../Connections/pasql.php");
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT * FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' ORDER BY w_id DESC";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");

$maxi = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)){
	$w_date = $row["w_date"];
	$w_desc[] = $row["w_desc"];
	$w_datea[] = $row["w_date"];
	$time[] = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	
	if($w_datea[$wnum]==$row["w_date"]){
		$showtime = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	}
}

$sql = "DELETE FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' AND w_date='".$w_datea[$wnum]."'"; //刪除記錄
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗2");

//刪除檔案
unlink($w_desc[$wnum]); 

//以s_id, m_id，從works查詢是否還有檔案
$sql = "SELECT s_id,m_id FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗3");
//if沒有檔案, 狀態改為未繳交
if(mysql_num_rows($result)==0){
	$sql = "UPDATE progress2stu SET p_uploaded='0' WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";		
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗3");
	echo "<script language='javascript'>";
	echo "  alert('已刪除!請重新繳交作品 ".$w_desc[$wnum]."');";
	echo "parent.location.href='stu.php';";
	echo "</script>";
}

echo "<script language='javascript'>";
echo "  alert('已刪除!');";
echo "document.location.href='show.php?mid=".$m_id."';";
echo "</script>";
?>