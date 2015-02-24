<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
if (empty($_COOKIE['s_id'])||$_POST["pg_sid"]=="")	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}
else{
	$_POST["m_id"];		//任務序號
	$_POST["pg_sid"];	//被評者學號
	$_POST["scanum"];	//單選題數量
	$_POST["txtnum"];	//文字題數量

	for($i=0;$i<$_POST["scanum"];$i++){
		$_POST["sca_id".$i];	//單選題序號
		$_POST["sca".$i];	//單選題回答內容
	}

	for($j=0;$j<$_POST["txtnum"];$j++){
		$_POST["txt_id".$j];	//文字題序號
		$_POST["txt".$j];	//文字題回答內容
	}

//建立資料連接
require_once('../Connections/pasql.php');
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");
	
//檢查文字是否含有禁語
$sql_taboo = "SELECT taboo_word FROM taboo";
$result_taboo = mysql_query($sql_taboo,$pa);
if(!$result_taboo)die("執行SQL命令失敗_taboo");
$taboo = "";
while($row_taboo = mysql_fetch_assoc($result_taboo)){
	for($j=0;$j<$_POST["txtnum"];$j++){
		//文字題回答內容
		if (strpos ($_POST["txt".$j], $row_taboo["taboo_word"])){
			echo "<script language='javascript'>";
			echo "  alert('含有不雅用語，請修改後再送出!');";
			echo "parent.location.href='sa.php?mid=".$_POST["m_id"]."';";
			echo "</script>";
		}
		else{
			//檢查文字題的字數
			if( $_GET["fin"]=="done" && mb_strlen($_POST["txt".$j], 'utf-8')<10 ){
				echo "<script language='javascript'>";
				echo "  alert('字數未達10字，請補充內容後再送出！');";
				//echo "parent.location.href='pa.php?mid=".$_POST["m_id"]."';";
				echo "history.back();";
				echo "</script>";
			}
			else{
				$taboo = "no";
			}
		}
	}
}
if($taboo == "no"){
	
	//檢查單選題是否已經有紀錄
	for($i=0;$i<$_POST["scanum"];$i++){
		$_POST["sca_id".$i];	//單選題序號
		$_POST["sca".$i];	//單選題回答內容
		
		$sca_id = $_POST["sca_id".$i];
		$sql = "SELECT * FROM scaler WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$_POST["m_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND sca_id='".$sca_id."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗1");
	
		$sca = $_POST["sca".$i];
		if(mysql_num_rows($result)!=0){	//已經有紀錄-> 更新
			$sql = "UPDATE scaler SET sca_reply='".$sca."' WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$_POST["m_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND sca_id='".$sca_id."'";
		}
		else{ //沒記錄-> 新增
			$sca = $_POST["sca".$i];
			$sql = "INSERT INTO scaler (s_id,m_id,pg_sid,sca_id,sca_reply) VALUES('".$_COOKIE['s_id']."','".$_POST["m_id"]."','".$_POST["pg_sid"]."','".$sca_id."','".$sca."')";
		}
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		
		
		$sql_pg = "SELECT pg_member,pg_pas FROM pg WHERE m_id='".@$m_id."' AND s_id='".$_COOKIE['s_id']."'";
		$result_pg = mysql_query($sql_pg,$pa);
		if(!$result_pg)die("執行SQL命令失敗_pg");
		$row_pg = mysql_fetch_assoc($result_pg);
		$alls_id = explode("-",$row_pg["pg_member"]);
		$alls_pas = explode("-",$row_pg["pg_pas"]);
		
		$pg_pas = ""; //製作互評狀態字串
		if($_GET["fin"]!="done"){ //紀錄於 pg , 互評狀態為儲存
			$pg_pas = "1";
		}
		else{ //紀錄於 pg , 互評狀態為送出
			$pg_pas = "2";
		}
		
		$sql = "UPDATE works SET sa_status='".$pg_pas."' WHERE w_id='".$_POST["w_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗3");
	}

	//檢查文字題是否已經有紀錄
	for($i=0;$i<$_POST["txtnum"];$i++){
		$_POST["txt_id".$i];	//文字題序號
		$_POST["txt".$i];	//文字題回答內容
		
		$txt_id = $_POST["txt_id".$i];
		$sql = "SELECT * FROM textr WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$_POST["m_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND txt_id='".$txt_id."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗txt1");
	
		$txt = $_POST["txt".$i];
		if(mysql_num_rows($result)!=0){	//已經有紀錄-> 更新
			$sql = "UPDATE textr SET txt_reply='".$txt."' WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$_POST["m_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND txt_id='".$txt_id."'";
		}
		else{ //沒記錄-> 新增
			$txt = $_POST["txt".$i];
			$sql = "INSERT INTO textr (s_id,m_id,pg_sid,txt_id,txt_reply) VALUES('".$_COOKIE['s_id']."','".$_POST["m_id"]."','".$_POST["pg_sid"]."','".$txt_id."','".$txt."')";
		}
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗txt2");
		
	}


	
	echo "<script language='javascript'>";
	echo "  alert('新增/更新互評成功!');";
	echo "parent.location.href='sa.php?mid=".$_POST["m_id"]."';";
	echo "</script>";
}
}
?>
</body>
</html>