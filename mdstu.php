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

//有班級名稱，查詢是否已存在，不存在就建立班級
if(isset($_POST["sid"]) && isset($_POST["spass"]) && isset($_POST["sname"]) && isset($_POST["ssex"]) && isset($_POST["cid"]) && isset($_POST["sclassnums"])){
	$sql = "SELECT s_id FROM stu WHERE s_id='".$_POST["sid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗2");
	if(mysql_num_rows($result)!=0){
		$sql = "UPDATE stu SET s_pass='".$_POST["spass"]."', s_name='".$_POST["sname"]."', s_sex='".$_POST["ssex"]."', c_id='".$_POST["cid"]."', s_classnums='".$_POST["sclassnums"]."' WHERE s_id='".$_POST["sid"]."'";
		$result = mysql_query($sql,$pa);		
		if(!$result)die("執行SQL命令失敗3");
		else{			
			echo "<script language='javascript'>";
			echo "  alert('修改".$_POST["sid"]." ".$_POST["s_name"]."資料成功！');";
			echo "document.location.href='mdstu.php';";
			echo "</script>";
		}
	}
	else{			
		echo "<script language='javascript'>";
		echo "  alert('該學號不存在');";
		echo "document.location.href='mdstu.php';";
		echo "</script>";
	}
}
else if(isset($_POST["t_check"])){
	echo "<script language='javascript'>";
	echo "  alert('所有欄位均為必填！');";
	echo "document.location.href='mdstu.php';";
	echo "</script>";
}
?>
<html>
<head>
<title>修改學生資料</title>
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
		<td class="title">修改學生資料</td>
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
	<form action="mdstu.php" method="post" enctype="multipart/form-data">
<?php
$p_query = "";
$g_query = "";
if(isset($_POST["query"])){
	$p_query = $_POST["query"];
}
if(isset($_GET["query"])){
	$g_query = $_GET["query"];
}
if($p_query=="" && $g_query!="2"){
	echo '
	<input type="hidden" name="query" value="1">
	<table width="0">
			<tr>
				<th>依學號搜尋</th>
			</tr>
			<tr>
				<td>請輸入學號：</td><td><input type="text" name="sid" value="">　<font size="2">測試帳號為 t_'.$_SESSION["t_id"].'_01, 02, 03...</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>依姓名搜尋</th>
			</tr>
			<tr>
				<td>請輸入姓名：</td><td><input type="text" name="sname" value="">　<input type="submit" name="Submit" value="確定"></td>
			</tr>';
	echo '
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>';
}
elseif($p_query=="1"){
	if($_POST["sid"]!=""){
		$sql = "SELECT stu.s_id,stu.s_name,stu.s_classnums,class.c_id,class.c_class FROM stu,class WHERE stu.s_id='".$_POST["sid"]."' AND stu.c_id=class.c_id";
	}
	elseif($_POST["sname"]!=""){
		$sql = "SELECT stu.s_id,stu.s_name,stu.s_classnums,class.c_id,class.c_class FROM stu,class WHERE stu.s_name LIKE'%".$_POST["sname"]."%' AND stu.c_id=class.c_id";
	}
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗_stu");
	echo '
		<table>
				<tr>
					<th>學號</th><th>姓名</th><th>班級</th><th>座號</th><td></td>
				</tr>';
	while($row = mysql_fetch_assoc($result)){
		$sql_c2t = "SELECT c_id FROM c2t WHERE t_id='".$_SESSION["t_id"]."' AND c_id='".$row["c_id"]."'";
		$result_c2t = mysql_query($sql_c2t,$pa);
		if(!$result_c2t)die("執行SQL命令失敗_c2t");
		if(mysql_num_rows($result_c2t)!=0){
			echo '
				<tr align="center">
					<td>'.$row["s_id"].'</td><td>'.$row["s_name"].'</td><td>'.$row["c_class"].'</td><td>'.$row["s_classnums"].'</td><td><a href="mdstu.php?query=2&sid='.$row["s_id"].'">修改</a></td>
				</tr>';
		}
	}
	echo '</table>';
}
elseif($g_query=="2"){
	$sql = "SELECT * FROM stu,class WHERE stu.s_id='".$_GET["sid"]."' AND stu.c_id=class.c_id";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗_stu");
	$row = mysql_fetch_assoc($result);
	$c_id = $row["c_id"];
	$selected1 = "";
	$selected0 = "";
	if($row["s_sex"]=="1"){
		$selected1="selected";
	}
	else{
		$selected0="selected";
	}
	echo '
	<table width="0">
			<tr>
				<th colspan="2">學生個人資料</th>
			</tr>
			<tr>
				<td>學號：</td><td><input type="text" name="sid" value="'.$row["s_id"].'">　<font size="2">測試帳號為 t_'.$_SESSION["t_id"].'-01, 02, 03...</td>
			</tr>
			<tr>
				<td>密碼：</td><td><input type="text" name="spass" value="'.$row["s_pass"].'"></td>
			</tr>
			<tr>
				<td>姓名：</td><td><input type="text" name="sname" value="'.$row["s_name"].'"></td>
			</tr>
			<tr>
				<td>性別：</td><td><select name="ssex" size="1">
										<option value="0" '.$selected0.'>女生
										<option value="1" '.$selected1.'>男生
									</select></td>
			</tr>
			<tr>
				<td>班級：</td><td><select name="cid" size="1">';
	
	$s_classnums = $row["s_classnums"];
	$sql = "SELECT class.c_id,class.c_class FROM class,c2t WHERE class.syear='".$syear."' AND c2t.c_id=class.c_id AND c2t.t_id='".$_SESSION["t_id"]."' ORDER BY class.c_class";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗_class");
	while($row = mysql_fetch_assoc($result)){
		if($row["c_id"]==$c_id){
			$selected = "selected";
		}
		else{
			$selected = "";
		}
		echo '<option value="'.$row["c_id"].'" '.$selected.'>'.$row["c_class"];
	}
	echo '
								</select></td>
			</tr>
			<tr>
				<td>座號：</td><td><input type="text" name="sclassnums" value="'.$s_classnums.'"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
		<input type="hidden" name="t_check" value="1">
		<input type="submit" value="確定">';
}
?>
        <hr>
        
        <p align="center">
			
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


