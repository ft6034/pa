<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
else{
	date_default_timezone_set('Asia/Taipei');
	$now_date = date("Y.m.d");
	$now_time = date("H.i.s");
	$now_time2 = date("H:i:s");
	
	//建立資料連接
	require_once('./Connections/pasql.php');
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");

	//處理退件
	if(isset($_POST["aresult"]) && $_POST["aresult"]!=""){
		$sql = "UPDATE works SET w_status='3' WHERE s_id='".$_POST["pg_sid"]."' AND m_id='".$_POST["m_id"]."' AND w_status='2'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_退件");
		
		//寄信給被退件者
		$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$_SESSION["t_id"]."', '".$_POST["pg_sid"]."', '".$_POST["aresult"]."</br>　<a href=show.php?mid=".$_POST["m_id"]."&aresult=".$_POST["aresult"]."> ->修正作品<- </a>','".$now_date." ".$now_time2."', 's2')"; //s2為作品受到退件
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗m".$sql);
		
		echo "<script language='javascript'>";
		echo "  alert('完成退件!');";
		echo "self.location.href='showwork.php?mid=".$_POST["m_id"]."&sid=".$_POST["next_sid"]."';";
		echo "</script>";
	}
	else{
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php

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
					if( isset($_GET["fin"]) && $_GET["fin"]=="done" && mb_strlen($_POST["txt".$j], 'utf-8')<10 ){
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
				$sql = "SELECT * FROM scaletr WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND sca_id='".$sca_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗1");
			
				$sca = $_POST["sca".$i];
				if(mysql_num_rows($result)!=0){	//已經有紀錄-> 更新
					$sql = "UPDATE scaletr SET sca_reply='".$sca."' WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND sca_id='".$sca_id."'";
				}
				else{ //沒記錄-> 新增
					$sca = $_POST["sca".$i];
					$sql = "INSERT INTO scaletr (t_id,pg_sid,sca_id,sca_reply) VALUES('".$_SESSION["t_id"]."','".$_POST["pg_sid"]."','".$sca_id."','".$sca."')";
				}
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗2");
				
				$pg_pas = ""; //製作互評狀態字串
				if(empty($_GET["fin"])){ //紀錄於 pg , 互評狀態為儲存
					$pg_pas = "1";
				}
				else{ //紀錄於 pg , 互評狀態為送出
					$pg_pas = "2";
				}
				
				$sql = "UPDATE works SET t_status='".$pg_pas."' WHERE w_id='".$_POST["w_id"]."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗3");
			}

			//檢查文字題是否已經有紀錄
			for($i=0;$i<$_POST["txtnum"];$i++){
				$_POST["txt_id".$i];	//文字題序號
				$_POST["txt".$i];	//文字題回答內容
				
				$txt_id = $_POST["txt_id".$i];
				$sql = "SELECT * FROM texttr WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND txt_id='".$txt_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗txt1");
			
				$txt = $_POST["txt".$i];
				if(mysql_num_rows($result)!=0){	//已經有紀錄-> 更新
					$sql = "UPDATE texttr SET txt_reply='".$txt."' WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$_POST["pg_sid"]."' AND txt_id='".$txt_id."'";
				}
				else{ //沒記錄-> 新增
					$txt = $_POST["txt".$i];
					$sql = "INSERT INTO texttr (t_id,pg_sid,txt_id,txt_reply) VALUES('".$_SESSION["t_id"]."','".$_POST["pg_sid"]."','".$txt_id."','".$txt."')";
				}
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗txt2");
				
				//如果有save，就新增評語範例
				if(isset($_GET["sample"]) && $_GET["sample"]=="gsave"){
					$sql = "INSERT INTO textrs (m_id,txt_sample,owner) VALUES('general','".$txt."','".$_SESSION["t_id"]."')"; //通用範例
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗gsave");
				}
				if(isset($_GET["sample"]) && $_GET["sample"]=="msave"){
					$sql = "INSERT INTO textrs (m_id,txt_sample,owner) VALUES('".$_POST["m_id"]."','".$txt."','".$_SESSION["t_id"]."')"; //通用範例
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗msave");
				}
			}
			
			
			if(isset($_GET["fin"]) && $_GET["fin"]=="done"){
				echo "<script language='javascript'>";
				//echo "  alert('完成評審!');";
				echo "self.location.href='showwork.php?mid=".$_POST["m_id"]."&sid=".$_POST["next_sid"]."';";
				echo "</script>";
			}
			else{
				echo "<script language='javascript'>";
				if(isset($_GET["sample"]) && ($_GET["sample"]=="gsave" || $_GET["sample"]=="msave")){
					echo "  alert('新增評語範例成功!');";
				}
				else{
					echo "  alert('暫時儲存成功!');";
				}
				echo "self.location.href='showwork.php?mid=".$_POST["m_id"]."&sid=".$_POST["pg_sid"]."';";
				echo "</script>";
			}
			
		}
	}
}
?>
</body>
</html>