<?php
session_start();


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

if(isset($_GET["cid"]) && isset($_GET["cname"])){
	$c_id = $_GET["cid"];
	$c_name = $_GET["cname"];
	setcookie("c_id",$_GET["cid"]);
	setcookie("c_name",$_GET["cname"]);
}
else{
	$c_id = "";
	$m_id = "";
	$c_name = "";
	$m_name = "";
}
?>
<html>
<head>
<title>作品成績一覽表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">

<script language="javascript">

function goLite(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCE8CC";
}

function goDim(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCDDCC";
}

</script>

</head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFF2CD">

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
		<td colspan="2" class="header">[作品成績一覽表]</td>
	</tr>
	<tr>
		<td class="title">
		<?php	if($c_id!=""){ echo "[".$c_name." 班]";} 
			else if(substr ($_SERVER["REMOTE_ADDR"], 0, 7)=="172.17."){echo '[<a href="index.php" target="_parent">登入系統</a>]';} ?>
		</td>
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
	
    <iframe frameborder="0" src="scorelistin.php" name="inpage" width="900 px" height="520 px" scrolling="yes"></iframe>

	
	<!--<div id="scrollee">
	<object id="object" width="800 px" height="500 px" type="text/html" data=" scorelistin.php "></object>
	</div>-->
	<!--<div id='myMask'><iframe class='myFrame' src='scorelistin.php'></iframe></div>-->
        <hr>
        

			<!-- <input type="button" name="button" value="回上一頁" onClick="window.history.back();"> -->
			<form name="groovyform">
			<input
				type="button"
				name="groovybtn1"
				class="groovybutton-back"
				value="重新選擇班級"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location='scorelist.php'">
			<input
				type="button"
				name="groovybtn2"
				class="groovybutton-back"
				value="回首頁"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location='index.php'">
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