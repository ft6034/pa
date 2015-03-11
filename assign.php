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
	//取任務資料
	$status = '1';
	$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND m_id='".$_GET["mid"]."' AND t_id='".$_SESSION["t_id"]."'";
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
		$m_status = $row["m_status"];
		//$syear = $row["m_syear"];
		$m_grade = $row["m_grade"];
}

else if(isset($_POST["m_id"])){
	//指派或取消班級
	$status = '2';		
		//檢查是否有取消任課的班級$_POST['cancel_c']
		if(isset($_POST['cancel_c'])) {
			foreach($_POST['cancel_c'] as $key => $value) {
				$dc_id = $value;
				//刪除出被選取的c_id
				$sql = "DELETE FROM m2c WHERE c_id='".$dc_id."' AND m_id='".$_POST["m_id"]."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗7");
				//以c_id從stu取出學生
				$sql_stu = "SELECT s_id FROM stu WHERE c_id='".$dc_id."'";
				$result_stu = mysql_query($sql_stu,$pa);
				if(!$result_stu)die("執行SQL命令失敗-d取學生".$sql_stu."");
				while($row_stu = mysql_fetch_assoc($result_stu)){
					//刪除該學生的progress2stu紀錄
					$sql = "DELETE FROM progress2stu WHERE s_id='".$row_stu["s_id"]."' AND m_id='".$_POST["m_id"]."'";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗7");
				}
				
			}
		}
		//檢查是否有加入任課的班級$_POST['add_c']
		if(isset($_POST['add_c'])) {
			foreach($_POST['add_c'] as $key => $value) {
				$ic_id = $value;
				//列印出被選取的c_id，新增m2c紀錄
				$sql = "INSERT INTO m2c(c_id, m_id, m2c_status)";
				$sql .= " VALUES('".$ic_id."', '".$_POST["m_id"]."','1')";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗8");
				//以c_id從stu取出學生
				$sql_stu = "SELECT s_id FROM stu WHERE c_id='".$ic_id."'";
				$result_stu = mysql_query($sql_stu,$pa);
				if(!$result_stu)die("執行SQL命令失敗-i取學生".$sql_stu."");
				while($row_stu = mysql_fetch_assoc($result_stu)){
					//確認progress2stu記錄是否已存在
					$sql_p2s = "SELECT p_id FROM progress2stu WHERE s_id='".$row_stu["s_id"]."' AND m_id='".$_POST["m_id"]."'";
					$result_p2s = mysql_query($sql_p2s,$pa);
					if(!$result_p2s)die("執行SQL命令失敗-確認記錄");
					if(mysql_num_rows($result_p2s)==0){
						//新增該學生的progress2stu紀錄
						$sql_ip2s = "INSERT INTO progress2stu(s_id, m_id)";
						$sql_ip2s .= " VALUES('".$row_stu["s_id"]."', '".$_POST["m_id"]."')";
						$result_ip2s = mysql_query($sql_ip2s,$pa);
						if(!$result_ip2s)die("執行SQL命令失敗-新增p2s紀錄");
					}
				}
			}
		}	
		echo "<script language='javascript'>";
		echo "  alert('指派(或取消)任務成功！');";
		
		if($newm=="y"){
			echo "document.location.href='termset.php?newm=y&mid=".$_POST["m_id"]."';";
		}
		else{
			echo "document.location.href='assign.php';";
		}
		
		echo "</script>";
}
else{
	$status = '3';
}
?>
<html>
<head>
<title>指派任務</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
		<td class="title">指派任務</td>
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
		$newm_get = "?newm=y";
	}
	else{
		$newm_get = "";
	}
?>
      <form action="assign.php<?php echo $newm_get;?>" method="post" enctype="multipart/form-data">
        <table class="outtable2" width='900'>
<?php
	if($status=='3')	{
		echo "<tr><td width='120'>任務名稱</td><td>任務說明</td><td>適用年級</td><td>學年學期</td><td>已指派班級</td><td>&nbsp;</td></tr>";
		//取任務清單
		$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND t_id='".$_SESSION["t_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		//取任務數量，來調整空白列的數量
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td class='td-solid'><p align='left'>".$row["m_name"]."</p></td>";
			echo "<td class='td-solid'><p align='left'>".$row["m_desc"]."</p></td>";
			//echo "<td>".$row["m_date"]."</td>";
			//echo "<td>".$row["m_start"]."</td>";
			//echo "<td>".$row["m_stop"]."</td>";
			//echo "<td>".$row["m_status"]."</td>";
			echo "<td class='td-solid'>".$row["m_grade"]."</td>";
			echo "<td class='td-solid'>".$row["syear"]."</td>";
			echo "<td class='td-solid'>";
			//取已指派班級
			$sql_c = "SELECT class.c_class FROM m2c,class WHERE m2c.m_id='".$row["m_id"]."' AND m2c.c_id=class.c_id";
			$result_c = mysql_query($sql_c,$pa);
			if(!$result_c)die("執行SQL命令失敗-取已指派班級");
			while($row_c = mysql_fetch_assoc($result_c)){
				echo $row_c["c_class"]."&nbsp;";
			}
			echo "&nbsp;</td>";
			echo "<td class='td-solid'><input type=\"button\" value=\"修改\" onClick=\"self.location='assign.php?mid=".$row["m_id"]."'\"></td>";
			echo "</tr>";
		}
	}
	if($status=='1')	{
		echo '
			<input type="hidden" name="m_id" value="'.$m_id.'">
			<tr>
				<td>任務名稱：</td><td>'.$m_name.'</td>
			</tr>
			<tr>
				<td>任務說明：</td><td>'.$m_desc.'</td>
			</tr>
			<tr>
				<td>適用年級：</td><td>'.$m_grade.'</td>
			</tr>
			<tr>
				<td>學年學期：</td><td>'.$syear.'</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>取消已指派班級：</td>
				<td><br>
			';
			//取該學期學年裡的班級
			$sql = "SELECT class.c_id, class.c_class FROM class,c2t WHERE class.c_id=c2t.c_id AND class.syear='".$syear."' AND c2t.t_id='".$_SESSION["t_id"]."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗5");

			while($row = mysql_fetch_assoc($result)){
				$sql_1 = "SELECT m2c_id FROM m2c WHERE m_id ='".$m_id."' AND c_id='".$row["c_id"]."'";
				$result_1 = mysql_query($sql_1,$pa);
				if(!$result_1)die("執行SQL命令失敗6");
				//已存在指派班級記錄
				if(mysql_num_rows($result_1)!=0){
					echo '<input type="checkbox" name="cancel_c[]" value="'.$row["c_id"].'">'.$row["c_class"];
				}
				//不指派任課記錄
				else{
					$c_ids[] = $row["c_id"];
					$c_classes[] = $row["c_class"];
				}
			}

		echo '
				<br><br></td>
			</tr>
			<tr>
				<td>加入未指派班級：</td>
				<td>
		';
			//取未指派班級
			$i = 0;
			foreach($c_ids as $Value){
				echo '<input type="checkbox" name="add_c[]" value="'.$Value.'">'.$c_classes[$i];
				$i++;
			}
		echo '
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td><input type="submit" name="Submit" value="確定修改"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			';
	}
?>
        </table>
	
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