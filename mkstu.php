<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

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

//新增學生資料
if(isset($_POST["sid"]) && isset($_POST["spass"]) && isset($_POST["sname"]) && isset($_POST["ssex"]) && isset($_POST["cid"]) && isset($_POST["sclassnums"])){
	$sql = "SELECT s_id FROM stu WHERE s_id='".$_POST["sid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");
	if(mysql_num_rows($result)==0){ //該學號無學生資料
		$sql = "INSERT INTO stu(s_id, s_pass, s_name, s_sex, c_id, s_classnums)";
		$sql .= " VALUES('".$_POST["sid"]."', '".$_POST["spass"]."', '".$_POST["sname"]."', '".$_POST["ssex"]."', '".$_POST["cid"]."', '".$_POST["sclassnums"]."')";
		$result = mysql_query($sql,$pa);		
		if(!$result)die("執行SQL命令失敗3");
		else{
			echo "<script language='javascript'>";
			echo "  alert('新增".$_POST["sid"]." ".$_POST["s_name"]."成功！');";
			echo "document.location.href='mkstu.php';";
			echo "</script>";
		}
	}
	else{
		echo "<script language='javascript'>";
		echo "  alert('無法新增，該學號已存在');";
		echo "document.location.href='mkstu.php';";
		echo "</script>";
	}
}
else if(isset($_POST["t_check"])){
	echo "<script language='javascript'>";
	echo "  alert('所有欄位均為必填！');";
	echo "document.location.href='mkstu.php';";
	echo "</script>";
}
?>
<html>
<head>
<title>新增學生</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
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
		<td class="title">新增學生</td>
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
	<form action="mkstu.php" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>學號：</td><td><input type="text" name="sid">　<font size="2">測試帳號為 t_<?php echo $_SESSION["t_id"];?>_01, 02, 03...</td>
			</tr>
			<tr>
				<td>密碼：</td><td><input type="text" name="spass"></td>
			</tr>
			<tr>
				<td>姓名：</td><td><input type="text" name="sname"></td>
			</tr>
			<tr>
				<td>性別：</td><td><select name="ssex" size="1" selected="1">
										<option value="0">女生
										<option value="1">男生
									</select></td>
			</tr>
			<tr>
				<td>班級：</td><td><select name="cid" size="1" selected="1">
<?php
$sql = "SELECT class.c_id,class.c_class FROM class,c2t WHERE class.syear='".$syear."' AND c2t.c_id=class.c_id AND c2t.t_id='".$_SESSION["t_id"]."' ORDER BY class.c_class";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗_class");
while($row = mysql_fetch_assoc($result)){
	echo '<option value="'.$row["c_id"].'">'.$row["c_class"];
}
?>
								</select></td>
			</tr>
			<tr>
				<td>座號：</td><td><input type="text" name="sclassnums"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
        <hr>
        
        <p align="center">
			<input type="hidden" name="t_check" value="1">
			<input type="submit" name="Submit" value="確定新增">
			
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


