<?php

$now_date = date("Y.m.d");
$now_time = date("H.i.s",time()+(8*60*60));

//學年學期的預設值由system取得
//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];


/*將目前學年學期，學生-班級對照資料存進 s2c 資料庫

//檢查是否已經有存過

//找出某班級中的所有學生s_id,c_id
$sql = "SELECT s_id,c_id FROM stu WHERE c_id='7'"; //修改c_id
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
while($row = mysql_fetch_assoc($result)){
	$s_id = $row["s_id"];
	$c_id = $row["c_id"];
	$sql2 = "INSERT INTO s2c (syear,s_id,c_id) VALUES(".$syear.",".$s_id.",".$c_id.")";
	$result2 = mysql_query($sql2,$pa);
	if(!$result2)die("執行SQL命令失敗2");
}

*/

/*將學生升級到新班級

//找出某班級中的所有學生s_id,c_id
$sql = "UPDATE stu SET c_id='9' WHERE c_id='2'"; //修改c_id
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");

*/

?>