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

$status = 0;
if(isset($_POST["taboo_id"])){
	
	//刪除禁語
	if($_POST["action"]=="del"){
		$status = '2'; 
		$sql = "DELETE FROM taboo WHERE taboo_id='".$_POST["taboo_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗");
		else{
			echo "<script language='javascript'>";
			echo "  alert('刪除成功！');";
			echo "document.location.href='tabooset.php';";
			echo "</script>";
		}
	}
}
elseif(isset($_POST["action"]) && $_POST["action"]=="add"){
	//新增禁語
	$status = '5'; 
	for($i=0;$i<$_POST["n"];$i++){
		$sql = "INSERT INTO taboo (taboo_word) VALUES ('".$_POST["taboo_word".$i]."') ";
		$result = mysql_query($sql,$pa);
	}
	if(!$result)die("執行SQL命令失敗");
	else{
		echo "<script language='javascript'>";
		echo "  alert('新增成功！');";
		echo "document.location.href='tabooset.php';";
		echo "</script>";
	}
}

else{
	if(isset($_GET["action"]) && $_GET["action"]=="form"){
		$status = '3'; //選擇新增禁語數量
	}
	elseif(isset($_POST["n"])){
		$status = '4'; //輸入新增禁語內容
	}
	else{
		$status = '1';
	}
}
?>
<html>
<head>
<title>設定禁語</title>
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
		<td class="title">設定禁語</td>
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
        <form action="tabooset.php<?php echo $newm_get;?>" method="post" enctype="multipart/form-data">
        <table width="900">
<?php
	if($status=='1')	{
		echo "<tr align='center'><th>禁語</th><td></td></tr>";
		//取任務清單
		$sql = "SELECT taboo_id,taboo_word FROM taboo";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		//取任務數量，來調整空白列的數量
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)){
			echo "<form action='tabooset.php' method='post' enctype='multipart/form-data'><tr>";
			echo "<td class='td-solid'>".$row["taboo_word"]."</td>";
			echo "<td>
					<input type='hidden' name='taboo_id' value='".$row["taboo_id"]."'>
					<input type='hidden' name='action' value='del'>
					<input type='submit' value='刪除'>					
				</td>";
			echo "</tr></form>";
		}
		echo "<td class='td-solid'>
				<input type=\"button\" value=\"新增禁語\" onClick=\"self.location='tabooset.php?action=form'\">
				</td>";
		echo "</tr>";
	}
	if($status=='3')	{
		echo "<tr align='center'><th>請選擇新增禁語數量</th></tr>";
		echo "<tr>";
		echo "<td class='td-solid'>
				<select name='n' size='1'>";
		for($i=1;$i<11;$i++){
			echo '<option value="'.$i.'">'.$i;
		}
		echo "</td>";
		echo "<td>
				<input type=\"submit\" value=\"確定\">
			</td>";
		echo "</tr>";
	}
	if($status=='4')	{
		echo "<tr><th>禁語</th></tr>";
		for($i=0;$i<$_POST["n"];$i++){
			echo "<tr><td align='center'><input type='text' name='taboo_word".$i."' id='taboo_word".$i."'' placeholder='請輸入禁語...'></td></tr>";
		}
		echo "<tr>";
		echo "<td align='center'>
				<input type='hidden' name='action' value='add'>
				<input type='hidden' name='n' value='".$_POST["n"]."'>
				<input type=\"submit\" value=\"確定\">
			</td>";
		echo "</tr>";		
	}
?>
        </table>
        <hr>
        
        <p align="center">
<?php			
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