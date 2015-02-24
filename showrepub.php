<?php
$m_id = $_GET["mid"];
$s_id = $_GET["sid"];
if(isset($_GET["wnum"])){
	$wnum = $_GET["wnum"];
}
else{
	$wnum = 0;
}

require_once("./Connections/pasql.php");
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT * FROM rework WHERE s_id='".$s_id."' AND m_id='".$m_id."' ORDER BY rew_id DESC";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");

$maxi = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)){
	$w_id = $row["rew_id"];
	$pop_point = $row["pop_point"];
	$w_date = $row["rew_date"];
	$w_name[] = $row["rew_name"];
	$w_desc[] = $row["rew_desc"];
	$w_datea[] = $row["rew_date"];
	$time[] = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	
	if($w_datea[$wnum]==$row["rew_date"]){
		$showtime = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	}
}

//增加點擊數
//判斷是否登入，設定s_id
if (empty($_COOKIE['s_id'])){
	$pop_sid = $_SERVER["REMOTE_ADDR"];
}
else{
	$pop_sid = $_COOKIE['s_id'];
}

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y-m-d");
$now_time = date("H:i:s");
//判斷是否已存在點擊記錄
$sql = "SELECT * FROM popularity WHERE s_id='".$pop_sid."' AND w_id='".$w_id."' ORDER BY pop_date DESC";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗_pop");
$row = mysql_fetch_assoc($result);
if(mysql_num_rows($result)==0){
	//新增點擊記錄
	$sql_insert = "INSERT INTO popularity (s_id, w_id, pop_date, w_type) VALUES ('".$pop_sid."', '".$w_id."', '".$now_date." ".$now_time."', '1')";
	$result_insert = mysql_query($sql_insert,$pa);
	if(!$result_insert)die("執行SQL命令失敗_insert");
	
	//作品點擊數+1
	$pop_point += 1;
	$sql_update = "UPDATE rework SET pop_point='".$pop_point."' WHERE rew_id ='".$w_id."'";
	$result_update = mysql_query($sql_update,$pa);
	if(!$result_update)die("執行SQL命令失敗_update");
}
else{
	//判斷是否今天已經有點擊記錄 yyyy-mm-dd
	if(substr($row["pop_date"], 0, 10)!=$now_date){
		//新增點擊記錄
		$sql_insert = "INSERT INTO popularity (s_id, w_id, pop_date, w_type) VALUES ('".$pop_sid."', '".$w_id."', '".$now_date." ".$now_time."', '1')";
		$result_insert = mysql_query($sql_insert,$pa);
		if(!$result_insert)die("執行SQL命令失敗_insert2");
		
		//作品點擊數+1
		$pop_point += 1;
		$sql_update = "UPDATE rework SET pop_point='".$pop_point."' WHERE rew_id ='".$w_id."'";
		$result_update = mysql_query($sql_update,$pa);
		if(!$result_update)die("執行SQL命令失敗_update2");
	}
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style2.css" rel="stylesheet" type="text/css">
<title>Scratch作品展示</title>
</head>

<body>
<br>

			<div class="container" align="center">
		<?php
		if (substr($w_desc[$wnum],-3) == ".sb"){
			echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="./stu/'.$w_desc[$wnum].'">
					</applet>
					<!-- Scratch project END-->
				'; //trim 可刪除指定字元
		}
		else if (substr($w_desc[$wnum],-3) == "jpg"||substr($w_desc[$wnum],-3) == "png"||substr($w_desc[$wnum],-3) == "bmp"||substr($w_desc[$wnum],-3) == "gif"){
			echo "<a href=./stu/".$w_desc[$wnum]." rel=\"shadowbox\" target=\"_top\"><img src=./stu/".$w_desc[$wnum]." height=\"387\"></a>";
		}
		
		else {
			echo "<p align=\"center\"><a href=./stu".$w_desc[$wnum]."> [ <font color='green'>下載作品▼</font> ] </a></p>";
		}
		?>
		  </div>
<?php
			if (substr($w_desc[$wnum],-3) == "xls"){
				echo "<div align=\"center\"><a href=./stu/".$w_desc[$wnum].">下載作品</a></div>";
			}
?>
</body>
</html>