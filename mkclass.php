<?php
session_start();

if(!isset($_SESSION["admin_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");

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

//有班級名稱，查詢是否已存在，不存在就建立班級
if(isset($_POST["c_class"])){
	$sql = "SELECT c_class FROM class WHERE c_class='".$_POST["c_class"]."' AND syear='".$_POST["syear"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");
	if(mysql_num_rows($result)==0){
		$sql = "INSERT INTO class(c_class, syear)";
		$sql .= " VALUES('".$_POST["c_class"]."', '".$_POST["syear"]."')";
		$result = mysql_query($sql,$pa);
		
		//取得c_id
		$sql_cid = "SELECT c_id FROM class WHERE c_class='".$_POST["c_class"]."' AND syear='".$_POST["syear"]."'";
		$result_cid = mysql_query($sql_cid,$pa);
		if(!$result_cid)die("執行SQL命令失敗_cid");
		$row_cid = mysql_fetch_assoc($result_cid);
		mkdir("./stu/works/".$row_cid["c_id"]."_".$_POST["c_class"],'0777');
		
		if(!$result)die("執行SQL命令失敗3");
		else{			
			echo "<script language='javascript'>";
			echo "  alert('新增".$_POST["syear"]."學期".$_POST["c_class"]."班成功！');";
			echo "document.location.href='mkclass.php';";
			echo "</script>";
		}
	}
	else{			
		echo "<script language='javascript'>";
		echo "  alert('無法新增，該班級已存在');";
		echo "document.location.href='mkclass.php';";
		echo "</script>";
	}
}

?>

<html>
<head>
<title>建立新班級</title>
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
		建立新班級
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
	
      
    <form action="mkclass.php" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>新班級名稱：</td><td><input type="text" name="c_class"> 第一碼為年級 ( 6為六年級, 5為五年級,..., t為測試班)</td>
			</tr>
			<tr>
				<td>學年學期：</td><td><input type="text" name="syear" value="<?php echo $syear;?>"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td valign="top">已存在班級：</td>
				<td>
					<table>
					<?php
						$sql = "SELECT DISTINCT c_class FROM class WHERE syear='".$syear."'";
						$result = mysql_query($sql,$pa);
						if(!$result)die("執行SQL命令失敗_c_class");
						while($row = mysql_fetch_assoc($result)){
							echo "<tr><td>".$row["c_class"]."</td></tr>";
						}
					?>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
        <hr>
        
        <p align="center">
			<input type="submit" name="Submit" value="確定新增">
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