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

if(isset($_GET["newm"]) && $_GET["newm"]=="y"){
	$newm = "y";
}
else{
	$newm = "n";
}

//如果有GET m_id就顯示該任務的資料
//如果有POST m_id就更新任務資料
//如果都沒有，就顯示任務清單
$status = 0;
if(isset($_GET["mid"])){
	if(isset($_POST["scanum"])||isset($_POST["txtnum"])){
		$status = '3'; //有m_id, scanum, txtnum
	}
	else{
		$status = '2'; //只有m_id
	}
	//取任務資料
	$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND m_id='".$_GET["mid"]."' AND t_id='".$_SESSION["t_id"]."' ORDER BY m_order";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_assoc($result);
		$m_id = $row["m_id"];
		$m_name = $row["m_name"];
		$m_desc = $row["m_desc"];
		$m_date = $row["m_date"];
		$m_start = $row["m_start"];
		$m_stop = $row["m_stop"];
		//$syear = $row["m_syear"];
		$m_grade = $row["m_grade"];
		$m_order = $row["m_order"];
	if(isset($_POST["m_id"])){
		$status = '4'; //有表單回傳
		for ($i=0;$i<$_POST["scanum"];$i++){ //新增單選題
			$sql = "INSERT INTO scale (m_id,sca_directions,sca_n,sca_order) VALUES('".$_POST["m_id"]."','".$_POST["sca_directions".$i]."','".$_POST["sca_rank"]."','".$i."')";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗sca".$i);
		
		}
		for ($i=0;$i<$_POST["txtnum"];$i++){ //新增文字回饋題
			$sql = "INSERT INTO text (m_id,txt_directions,txt_order) VALUES('".$_POST["m_id"]."','".$_POST["txt_directions".$i]."','".$i."')";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗txt".$i);
		}
		
		if(!$result)die("執行SQL命令失敗");
		else{
			echo "<script language='javascript'>";
			echo "  alert('新增互評項目成功！');";

			if($newm=="y"){
				echo "document.location.href='scalenset.php?newm=y&mid=".$_POST["m_id"]."';";
			}
			else{
				echo "document.location.href='scalenset.php?mid=".$_POST["m_id"]."';";
			}
			
			echo "</script>";
		}
		
	}
}

else{
	$status = '1'; //沒有m_id
}
?>
<html>
<head>
<title>設定評審項目</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style2.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td background="./img/bg-lt.png" width="60" height="60"></td>
	<td background="./img/bg-top.png" width="800" height="60">
	</td>
	<td background="./img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 start-->
	<table class="outtable">
	<tr>
		<td colspan="2" class="menu">
		[<a href="index.php">回首頁</a>]
		[<a href="logout.php">登出系統</a>]
		</td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">設定評審項目</td>
		<td class="function">&nbsp;</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	<!-- 標題區 end-->
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	
	
	<!-- 內容區 start-->
<?php
	if($newm=="y"){
		$newm_get = "&newm=y";
	}
	else{
		$newm_get = "";
	}
?>
        <form action="termset.php?mid=<?php echo$_GET["mid"].$newm_get;?>" method="post" enctype="multipart/form-data">
        <table width="900">
<?php
	if($status=='1')	{
		echo "<tr align='center'><th>任務編號</th><th width='100'>任務名稱</th><th>任務說明</th><th>適用年級</th><th>學年學期</th><td></td></tr>";
		//取任務清單
		$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND t_id='".$_SESSION["t_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		//取任務數量，來調整空白列的數量
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td class='td-solid'>".$row["m_order"]."</td>";
			//echo "<td>".$row["m_id"]."</td>";
			echo "<td class='td-solid'>".$row["m_name"]."</td>";
			echo "<td class='td-solid'>".$row["m_desc"]."</td>";
			//echo "<td>".$row["m_date"]."</td>";
			//echo "<td>".$row["m_start"]."</td>";
			//echo "<td>".$row["m_stop"]."</td>";
			//echo "<td>".$row["m_status"]."</td>";
			echo "<td class='td-solid'>".$row["m_grade"]."</td>";
			echo "<td class='td-solid'>".$row["syear"]."</td>";
			echo "<td class='td-solid'><input type=\"button\" value=\"設定\" onClick=\"self.location='termset.php?mid=".$row["m_id"].$newm_get."'\"></td>";
			echo "</tr>";
		}
	}
	if($status=='2')	{
		//檢查是否已有互評項目,有的話就顯示現有資料,多新增/修改按鈕, 沒的話就顯示請設定互評項目數量
		$sql_s = "SELECT m_id,sca_directions,sca_n,sca_order FROM scale WHERE m_id='".$_GET["mid"]."' ORDER BY sca_order";
		$result_sn = mysql_query($sql_s,$pa);
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		$row_sn = mysql_fetch_assoc($result_sn);
		
		$sql_t = "SELECT m_id,txt_directions,txt_order FROM text WHERE m_id='".$_GET["mid"]."' ORDER BY txt_order";
		$result_t = mysql_query($sql_t,$pa);
		if(!$result_t)die("執行SQL命令失敗_t");
		
		
		if(mysql_num_rows($result_s)!=0 || mysql_num_rows($result_t)!=0){ //已有資料
			
			echo '<tr><th>單選題</th></tr>';
			echo '<tr>
						<td>評分等第數：</td><td><select name="sca_rank" size="1">';
			if($row_sn["sca_n"]=="6") {$selected = "selected";}else{$selected = "";}
			echo						'<option value="6" '.$selected.'>6';
			if($row_sn["sca_n"]=="5") {$selected = "selected";}else{$selected = "";}
			echo						'<option value="5" '.$selected.'>5';
			if($row_sn["sca_n"]=="4") {$selected = "selected";}else{$selected = "";}
			echo						'<option value="4" '.$selected.'>4';
			if($row_sn["sca_n"]=="3") {$selected = "selected";}else{$selected = "";}
			echo						'<option value="3" '.$selected.'>3';
			if($row_sn["sca_n"]=="2") {$selected = "selected";}else{$selected = "";}
			echo						'<option value="2" '.$selected.'>2';
			$selected = "";
			echo '						</select>個選項</td>
					</tr>';

			//for($i=0;$i<mysql_num_rows($result_s);$i++){
			$i=0;
			while($row_s = mysql_fetch_assoc($result_s)){
				echo "<tr><td> 題目".($i+1)."</td><td><textarea cols=60 rows=1 name='sca_directions".$i."'>".$row_s["sca_directions"]."</textarea></td><td>排序<input type='text' name=sca_order".$i." value='".$row_s["sca_order"]."'></td></tr>";
				$i++;
			}
		
			echo '<tr><td>　</td></tr>';
			echo '<tr><th>文字回饋題</th></tr>';
			//for($i=0;$i<mysql_num_rows($result_t);$i++){
			$i=0;
			while($row_t = mysql_fetch_assoc($result_t)){
				echo "<tr><td> 題目".($i+1)."</td><td><textarea cols=60 rows=1 name='sca_directions".$i."'>".$row_t["txt_directions"]."</textarea></td><td>排序<input type='text' name=sca_order".$i." value='".$row_t["txt_order"]."'></td></tr>";
				$i++;
			}

		}
		else{	
			echo '<tr><th>尚未建立資料，請設定互評項目數量：</th></tr>';
			echo '<tr>
					<td>單選題：</td><td><select name="scanum" size="1" selected="3">';
			for($i=1;$i<11;$i++){
				echo					'<option value="'.$i.'">'.$i;
			}	
			echo '
										</select></td>
				</tr>';
			echo '<tr>
					<td>文字回饋題：</td><td><select name="txtnum" size="1" selected="3">';
			for($i=1;$i<11;$i++){
				echo					'<option value="'.$i.'">'.$i;
			}	
			echo '
										</select></td>
				</tr>
				<tr>
					<td></td><td><input type="submit" name="Submit" value="確定"></td>
				</tr>';
		}
		
	}
	if($status=='3')	{
		$sql = "SELECT * FROM scale WHERE m_id='".$_GET["mid"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗3");
		//取任務數量，來調整空白列的數量
		$num_rows = mysql_num_rows($result);
		$row = mysql_fetch_assoc($result);

		echo '<tr><th>單選題</th></tr>';
		echo '		<tr>
						<td>評分等第數：</td><td><select name="sca_rank" size="1">
										<option value="6">6
										<option value="5" selected>5
										<option value="4">4
										<option value="3">3
										<option value="2">2
										</select>個選項</td>
					</tr>';		
		for($i=0;$i<$_POST["scanum"];$i++){
			echo '	<tr>
						<td> 題目'.($i+1).'：</td><td><textarea cols=60 rows=1 name="sca_directions'.$i.'">'.$row["sca_directions"].'</textarea></td>
					</tr>';
		}

		echo '		
			<input type="hidden" name="m_id" value="'.$m_id.'">
			<input type="hidden" name="scanum" value="'.$_POST["scanum"].'">
			<input type="hidden" name="txtnum" value="'.$_POST["txtnum"].'">
			';
		
		echo '<tr><td>　</td></tr>';
		echo '<tr><th>文字回饋題</th></tr>';
		for($i=0;$i<$_POST["txtnum"];$i++){
				echo '	<tr>
						<td> 題目'.($i+1).'：</td><td><textarea cols=60 rows=1 name="txt_directions'.$i.'">'.$row["sca_directions"].'</textarea></td>
					</tr>';
		}
		echo '<tr><td>　</td></tr>';
		echo '<tr><td>
					<input type="button" name="button" value="設定其他任務" onClick="self.location=\'termset.php\'">
				</td><td align="right">
					<input type="submit" name="Submit" value="確定新增">
				</td></tr>';
	}
?>
        </table>
        <hr>
        
        <p align="center">
			<input type="button" name="button" value="回上一頁" onClick="window.history.back();">
        </p>
      </form>

	<!-- 內容區 end-->
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-lb.png" width="60" height="60"></td>
	<td background="./img/bg-bottom.png" height="60">
	</td>
	<td background="./img/bg-rb.png" width="60" height="60"></td>
</tr>

</table>
</center>

</body>
</html>