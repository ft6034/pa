<?php
session_start();
//僅限校內登入
/*
if(substr ($_SERVER[REMOTE_ADDR], 0, 8)!="192.168."){
	echo "<script language='javascript'>";
	echo "  alert('僅開放校內登入!');";
	echo "  history.back();";
	echo "</script>";
}
*/
$s_id ="";
if(isset($_COOKIE["s_id"])) {
	$s_id = $_COOKIE["s_id"];
}

$t_id ="";
if(isset($_SESSION["t_id"])) {
	$t_id = $_SESSION["t_id"];
}

if($s_id!=="") {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./stu/stu.php">';
}
elseif(isset($_SESSION["t_id"]) && $s_id=="") {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./teacher.php">';
}
elseif(isset($_SESSION["admin_id"]) && $s_id=="" && $t_id=="") {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./admin.php">';
}
else{
		date_default_timezone_set('Asia/Taipei');
		$now_time = date("Y年m月d日 H:i");

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>首頁-來自：<?php echo $_SERVER["REMOTE_ADDR"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="sb303/shadowbox.css" />
<script type="text/javascript" src="sb303/shadowbox.js"></script>
<script type="text/javascript">

    Shadowbox.init();

</script>

</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td background="./img/bg-lt.png" width="60" height="60"></td>
	<td background="./img/bg-top.png" width="800" height="60"></td>
	<td background="./img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 -->
	<table class="outtable">
	<tr>
		<td colspan="2" class="header2">[網路同儕互評系統]</td>
	</tr>
	<tr>
	<form name="login_form" id="login_form" method="post" action="checkpwd.php" target="_top">
		<td colspan="2" align="right">
		
		
			
			<font color="#005DBE" size="3">帳號：</font>
			<input name="s_id" type="text" id="s_id_id" maxlength="6" placeholder="請輸入學號（６個字） ..." autofocus>　
		
			<font color="#005DBE" size="3">密碼：</font>
			<input name="s_pass" type="password" id="s_pass" maxlength="20" placeholder="請輸入密碼...">
		
			<input type="submit" name="Submit" value="登入"><input type="reset" name="reset" value="清除">
			
		</td>
	</form>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	<!-- 內容區 start-->
<?php	
	require_once("./Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	
	
	echo "<table>";
	echo "<tr align='center'>";
	
	echo "<td valign='Top'>";
	//優秀評審
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#BE0200; background-color:#FF7573'>";
	echo "　優秀評審　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE0200; background-color:#FFCECD'><th>好評數</th><th>姓名</th></tr>";
	$sql = "SELECT s_name, pa_point FROM stu WHERE pa_point>0 ORDER BY pa_point DESC limit 20";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗");
	while($row = mysql_fetch_assoc($result)){
		echo "<tr align='center' style='font-size:15;'><td>".$row["pa_point"]."</td><td>".$row["s_name"]."</td></tr>";
	}
	echo "<tr><td colspan='2' align='right'><a href='gpa.php' rel='shadowbox' style='font-size:15;color:#BE0200;'>...more</a></td></tr>";
	echo "</table>";
	echo "</td>";
	
	echo "<td>　";
	echo "</td>";
	echo "<td valign='Top'>";
	//人氣作品(原作品)
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#A0009E; background-color:#FF91FE'>";
	echo "　人氣作品(原作品)　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE00BC; background-color:#FFCDFE'><th>作品連結</th><th>作者</th></tr>";
	$sql_pop = "SELECT stu.s_id, stu.s_name, works.m_id, works.pop_point FROM works,stu WHERE works.s_id=stu.s_id AND works.pop_point>0 ORDER BY pop_point DESC limit 20";
	$result_pop = mysql_query($sql_pop,$pa);
	if(!$result_pop)die("執行SQL命令失敗_pop1");
	while($row_pop = mysql_fetch_assoc($result_pop)){
		$sql_mname = "SELECT m_name FROM mission WHERE m_id=".$row_pop["m_id"];
		$result_mname = mysql_query($sql_mname,$pa);
		if(!$result_mname)die("執行SQL命令失敗_mname");
		$row_mname = mysql_fetch_assoc($result_mname);
		echo "<tr align='center' style='font-size:15;'><td>
		<a href='showpub.php?mid=".$row_pop["m_id"]."&sid=".$row_pop["s_id"]."' rel='shadowbox' target='_top'>".$row_mname["m_name"]."</a>
		</td><td>".$row_pop["s_name"]."</td></tr>";
		
	}
	echo "<tr><td colspan='2' align='right'><a href='hotw.php' rel='shadowbox' style='font-size:15;color:#BE00BC;'>...more</a></td></tr>";
	echo "</table>";
	echo "</td>";
	
	echo "<td>　";
	echo "</td>";
	
	echo "<td valign='Top'>";
	//人氣作品(修正後)
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#A0009E; background-color:#FF91FE'>";
	echo "　人氣作品(修正後)　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE00BC; background-color:#FFCDFE'><th>作品連結</th><th>作者</th></tr>";
	$sql_pop = "SELECT stu.s_id, stu.s_name, rework.m_id, rework.pop_point FROM rework,stu WHERE rework.s_id=stu.s_id AND rework.pop_point>0 ORDER BY pop_point DESC limit 20";
	$result_pop = mysql_query($sql_pop,$pa);
	if(!$result_pop)die("執行SQL命令失敗_pop2");
	while($row_pop = mysql_fetch_assoc($result_pop)){
		$sql_mname = "SELECT m_name FROM mission WHERE m_id=".$row_pop["m_id"];
		$result_mname = mysql_query($sql_mname,$pa);
		if(!$result_mname)die("執行SQL命令失敗_mname");
		$row_mname = mysql_fetch_assoc($result_mname);
		echo "<tr align='center' style='font-size:15;'><td>
		<a href='showpub.php?mid=".$row_pop["m_id"]."&sid=".$row_pop["s_id"]."' rel='shadowbox' target='_top'>".$row_mname["m_name"]."</a>
		</td><td>".$row_pop["s_name"]."</td></tr>";
	}
	echo "<tr><td colspan='2' align='right'><a href='hotrew.php' rel='shadowbox' style='font-size:15;color:#BE00BC;'>...more</a></td></tr>";
	echo "</table>";
	echo "</td>";
	
	echo "<td>　";
	echo "</td>";
	
	echo "<td valign='Top'>";
	//進步排行榜
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#BE6100; background-color:#FFAC55'>";
	echo "　進步排行榜　";
	echo "</th>";
	echo "</tr>";
	echo "<tr align='center' style='font-size:10; color:#BE6100; background-color:#FFE7CD'><th>進步幅度</th><th>姓名</th></tr>";
	
	$sql_up = "SELECT MAX(m_id) FROM rework";
	$result_up = mysql_query($sql_up,$pa);
	if(!$result_up)die("執行SQL命令失敗_up");
	$row_up = mysql_fetch_assoc($result_up);
	$max_mid = $row_up["MAX(m_id)"] - 3;
	
	$sql_up = "SELECT stu.s_name,rework.up_point FROM rework,stu WHERE rework.s_id=stu.s_id AND rework.up_point>0 AND rework.m_id>".$max_mid." ORDER BY up_point DESC limit 20";
	$result_up = mysql_query($sql_up,$pa);
	if(!$result_up)die("執行SQL命令失敗_up");
	while($row_up = mysql_fetch_assoc($result_up)){
		echo "<tr align='center' style='font-size:15;'><td>".$row_up["up_point"]."</td><td>".$row_up["s_name"]."</td></tr>";
	}
	echo "<tr><td colspan='2' align='right'><a href='imp.php' rel='shadowbox' style='font-size:15;color:#BE6100;'>...more</a></td></tr>";
	echo "</table>";
	echo "</td>";
	
	echo "<td>　";
	echo "</td>";
	
	echo "<td valign='Top'>";
	//班級作品列表
	echo "<table>";
	echo "<tr align='center'>";
	echo "<th colspan='2' style='font-size:15; color:#9D37FF; background-color:#FFFFFF'>";
	echo "<a href='../scratch'>-> 班級作品列表</a>";
	echo "</th>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	
	echo "</tr>";
	echo "</table>";
	
	/* 所有學生的pa_point為0，自動計算學生的pa_point並更新資料
	$sql = "SELECT txt_rid FROM pareport WHERE par_stat='g'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗a");
	while($row = mysql_fetch_assoc($result)){
		$sql_sid = "SELECT s_id, txt_id FROM textr WHERE txt_rid = ".$row["txt_rid"];
		$result_sid = mysql_query($sql_sid,$pa);
		if(!$result_sid)die("執行SQL命令失敗_sid");
		$row_sid = mysql_fetch_assoc($result_sid);
		
		//echo $row_sid["s_id"]."</br>";
		
		$pa_point = 0;
		$sql_pap = "SELECT pa_point FROM stu WHERE s_id = '".$row_sid["s_id"]."'";
		$result_pap = mysql_query($sql_pap,$pa);
		if(!$result_pap)die("執行SQL命令失敗_pap");
		$row_pap = mysql_fetch_assoc($result_pap);
		$pa_point =  $row_pap["pa_point"];
		$pa_point += 1;
		
		$sql_pap2 = "UPDATE stu SET pa_point = ".$pa_point." WHERE s_id = '".$row_sid["s_id"]."'";
		$result_pap2 = mysql_query($sql_pap2,$pa);
		if(!$result_pap2)die("執行SQL命令失敗_pap2");
		
	}
	*/
	
	
?>	
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
<?php
}
?>