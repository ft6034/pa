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

	$status = '2'; //只有m_id

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
		$m_status = $row["m_status"];
		$m_grade = $row["m_grade"];
		$m_order = $row["m_order"];
	
	//更新評分輔語資料
	if(isset($_POST["m_id"])){
		$status = '3'; //有表單回傳
		
		$sql_s = "SELECT sca_id,sca_n FROM scale WHERE m_id='".$_GET["mid"]."' ORDER BY sca_order";
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		while($row_s = mysql_fetch_assoc($result_s)){
			$sca_word ="";
			for ($i=0;$i<$row_s["sca_n"];$i++){
				if($sca_word ==""){
					$sca_word = $_POST["ra_n-".$row_s["sca_id"]."-".($i+1)];
				}
				else{
					$sca_word = $sca_word."-".$_POST["ra_n-".$row_s["sca_id"]."-".($i+1)];
				}
			}
			$sql = "UPDATE scale SET sca_word='".$sca_word."' WHERE m_id='".$_POST["m_id"]."' AND sca_id='".$row_s["sca_id"]."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗sca_word".$i);
		}
		
		if(!$result)die("執行SQL命令失敗");
		else{
			echo "<script language='javascript'>";
			echo "  alert('設定評分輔語成功！');";
			
			if($newm=="y"){
				echo "document.location.href='helpset.php?newm=y';";
			}
			else{
				echo "document.location.href='index.php';";
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
<title>設定評分輔語</title>
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
		<td class="title">設定評分輔語</td>
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
        <form action="scalenset.php?mid=<?php echo $_GET["mid"].$newm_get;?>" method="post" enctype="multipart/form-data">
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
			echo "<td><input type=\"button\" value=\"設定\" onClick=\"self.location='scalenset.php?mid=".$row["m_id"]."'\"></td>";
			echo "</tr>";
		}
	}
	if($status=='2')	{
		//檢查是否已有互評項目,有的話就顯示現有資料,多新增/修改按鈕, 沒的話就顯示請設定互評項目數量
		$sql_s = "SELECT sca_id,sca_directions,sca_n,sca_order FROM scale WHERE m_id='".$_GET["mid"]."' ORDER BY sca_order";
		$result_sn = mysql_query($sql_s,$pa);
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		$row_sn = mysql_fetch_assoc($result_sn);
		
		
		if(mysql_num_rows($result_s)!=0){ //已存在互評項目
			echo "<tr align='center'><th>題號</th><th>項目內容</th><th>評分輔助詞</th><td></td></tr>";
			$i=0;
			while($row_s = mysql_fetch_assoc($result_s)){
				echo "<tr><td class='td-solid'> 題目".($i+1)."</td><td class='td-solid'>".$row_s["sca_directions"]."</td>";
				echo '<td class="td-solid">';
				
				//取出評分輔助詞
				$sql_ra = "SELECT sca_word FROM scale WHERE m_id='".$_GET["mid"]."' AND sca_id='".$row_s["sca_id"]."'";
				$result_ra = mysql_query($sql_ra,$pa);
				if(!$result_ra)die("執行SQL命令失敗_ra");							
				$row_ra = mysql_fetch_assoc($result_ra);
				$allsca_word = explode("-",$row_ra["sca_word"]);
				for($j=0;$j<$row_s["sca_n"];$j++){
					echo '
					'.($j+1).' <textarea cols=10 rows=1 name="ra_n-'.$row_s["sca_id"]."-".($j+1).'">';
					if(@$allsca_word[$j]!=""){echo $allsca_word[$j];}
					else{
						switch($row_s["sca_n"]){
							case 2:
								switch($j){
									case 0:
										echo "否";
									break;
									
									case 1:
										echo "是";
									break;
								}
							break;
							
							case 3:
								switch($j){
									case 0:
										echo "尚未做到";
									break;
									
									case 1:
										echo "部分做到";
									break;
									
									case 2:
										echo "完全做到";
									break;
								}
							break;
							
							case 4:
								switch($j){
									case 0:
										echo "尚未做到";
									break;
									
									case 1:
										echo "少部分做到";
									break;
									
									case 2:
										echo "大部分做到";
									break;
									
									case 3:
										echo "完全做到";
									break;
								}
							break;
							
							case 5:
								switch($j){
									case 0:
										echo "尚未做到";
									break;
									
									case 1:
										echo "少部分做到";
									break;
									
									case 2:
										echo "部分做到";
									break;
									
									case 3:
										echo "大部分做到";
									break;
									
									case 4:
										echo "完全做到";
									break;
								}
							break;
							
							case 6:
								switch($j){
									case 0:
										echo "尚未做到";
									break;
									
									case 1:
										echo "幾乎尚未做到";
									break;
									
									case 2:
										echo "少部分做到";
									break;
									
									case 3:
										echo "大部分做到";
									break;
									
									case 4:
										echo "幾乎完全做到";
									break;
									
									case 5:
										echo "完全做到";
									break;
								}
							break;
						}
					}
					echo '</textarea>';

					echo "</br>";
				}
				unset($allsca_word);
				echo "<input type='hidden' name='m_id' value='".$m_id."'>";
				echo "</br></td>";
				
				echo "</tr>";
				$i++;
			}
			echo '<tr><td>　</td></tr>';

		}
		else{	
			echo "<script language='javascript'>";
			echo "  alert('尚未設定互評項目，請先設定互評項目！');";
			echo "document.location.href='termset.php';";
			echo "</script>";
		}
		echo "<tr><td></td><td></td><td align='center'><input type='submit' value='儲存'></td></tr>";
	}
	
?>
			
        </table>
        <hr>
        
        <p align="center">
<?php			
	if($status=='2'){
		echo	'<input type="button" name="button" value="回任務選擇頁" onClick="self.parent.location=\'scalenset.php\'">';
	}
	echo	'<input type="button" name="button" value="回首頁" onClick="self.parent.location=\'index.php\'">';
?>
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