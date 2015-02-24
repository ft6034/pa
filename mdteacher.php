<?php
session_start();

if(!isset($_SESSION["admin_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");

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

//如果有GET t_id就顯示該教師個人資料
//如果有POST t_id就更新教師個人資料
//如果都沒有，就顯示教師清單
$status = 0;
if(isset($_GET["tid"])){
	//取教師個人資料
	$status = '1';
	$sql = "SELECT * FROM teacher WHERE t_id='".$_GET["tid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");
	$num_rows2 = mysql_num_rows($result);
	$row = mysql_fetch_assoc($result);
		$t_id = $row["t_id"];
		$t_name = $row["t_name"];
		$t_pass = $row["t_pass"];
}

else if(isset($_POST["t_id"])){
	//更新教師個人資料
	$status = '2';
		$sql = "UPDATE teacher SET t_name='".$_POST["t_name"]."', t_pass='".$_POST["t_pass"]."' WHERE t_id='".$_POST["t_id"]."'";		
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗3");
		
		//檢查是否有取消任課的班級$_POST['cancel_c']
		if(isset($_POST['cancel_c'])) {
			foreach($_POST['cancel_c'] as $key => $value) {
				//列印出被選取的c_id
				$sql = "DELETE FROM c2t WHERE c_id='".$value."' AND t_id='".$_POST["t_id"]."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗7");
			}
		}
		//檢查是否有加入任課的班級$_POST['add_c']
		if(isset($_POST['add_c'])) {
			foreach($_POST['add_c'] as $key => $value) {
				//列印出被選取的c_id
				$sql = "INSERT INTO c2t(c_id, t_id)";
				$sql .= " VALUES('".$value."', '".$_POST["t_id"]."')";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗8");
			}
		}	
		echo "<script language='javascript'>";
		echo "  alert('教師資料修改成功！');";
		echo "document.location.href='mdteacher.php';";
		echo "</script>";
}
else{
	//取教師列表
	$status = '3';
}?>

<html>
<head>
<title>修改教師資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">
<!--shadowbox-->

<!--<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />-->

<script src="./sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="./sb303/shadowbox.css" />

<script type="text/javascript" src="./sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->
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
	  
    </td>
	</tr>
	<tr>
		<td colspan="2" class="header2">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td colspan="2" class="title">
		修改教師資料
		<font size="2">		
		[<a href="chpass.php" rel="shadowbox"> 修改密碼 </a>] 
		[<a href="logout.php"> 登出 </a>]</font>
		</td>
		
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
	
    <form action="mdteacher.php" method="post" enctype="multipart/form-data">
        <table width="0">
<?php
	if($status=='3')	{
		echo "<tr><td>教師姓名</td><td>帳號</td><td></td></tr>";
		//取教師清單
		$sql = "SELECT * FROM teacher";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗4");
		//取教師數量，來調整空白列的數量
		$num_rows4 = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td>".$row["t_name"]."</td>";
			echo "<td>".$row["t_id"]."</td>";
			//echo "<td>".$row["t_pass"]."</td>";
			echo "<td><input type=\"button\" value=\"修改\" onClick=\"self.location='mdteacher.php?tid=".$row["t_id"]."'\"></td>";
			echo "</tr>";
		}
		echo "<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>";
	}
	if($status=='1')	{
		
		echo '
			<input type="hidden" name="t_id" value="'.$t_id.'">
			<tr>
				<td>教師姓名：</td><td><input type="text" name="t_name" value="'.$t_name.'"></td>
			</tr>
			<tr>
				<td>教師帳號：</td><td>'.$t_id.'(不可修改)</td>
			</tr>
			<tr>
				<td>教師密碼：</td><td><input type="password" name="t_pass" value="'.$t_pass.'"></td>
			</tr>
			<tr>
				<td>取消已任課班級：</td>
				<td><br>
		';
			//取該學期學年裡的班級
			$sql = "SELECT c_id,c_class FROM class WHERE syear='".$syear."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗5");

			while($row = mysql_fetch_assoc($result)){
				$sql_1 = "SELECT c2t_id FROM c2t WHERE t_id ='".$t_id."' AND c_id='".$row["c_id"]."'";
				$result_1 = mysql_query($sql_1,$pa);
				if(!$result_1)die("執行SQL命令失敗6");
				//已存在任課班級記錄
				if(mysql_num_rows($result_1)!=0){
					echo '<input type="checkbox" name="cancel_c[]" value="'.$row["c_id"].'">'.$row["c_class"];
				}
				//不存在任課記錄
				else{
					$c_ids[] = $row["c_id"];
					$c_classes[] = $row["c_class"];
				}
			}

		echo '
				<br><br></td>
			</tr>
			<tr>
				<td>加入未任課班級：</td>
				<td>
		';
			//取未任課班級
			$i = 0;
			foreach($c_ids as $Value){
				echo '<input type="checkbox" name="add_c[]" value="'.$Value.'">'.$c_classes[$i];
				$i++;
			}
		echo '
				<br></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td></td><td><input type="submit" name="Submit" value="確定修改"></td>
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
        <hr>
        
        <p align="center">
			<input type="button" name="button" value="回首頁" onClick="self.parent.location='./admin.php'">
        </p>
    </form>
    
	
	
	<!-- 內容區 end-->
	
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-lb.png" width="60" height="60"></td>
	<td background="./img/bg-bottom.png" height="60"></td>
	<td background="./img/bg-rb.png" width="60" height="60"></td>
</tr>

</table>
</center>
</body>
</html>