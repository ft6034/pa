<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
//取教師姓名
$sql = "SELECT t_name FROM teacher WHERE t_id='".$_SESSION["t_id"]."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);

?>
<html>
<head>
<title>教師頁</title>
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
	  [<a href="bestu.php">模擬學生</a>]

<?php
if(isset($_SESSION["admin_id"])){
	echo ' [<a href="./logouteacher.php">切換回管理員身份</a>]	';
}
?>

    </td>
	</tr>
	<tr>
		<td colspan="2" class="header2">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td colspan="2" class="title">
		教師頁-
		<?php 
			echo $row["t_name"];
			if(isset($_COOKIE["s_id"])) {
				echo $_COOKIE["s_id"];
			}
			//取未讀訊息
			$sql = "SELECT ms_id FROM messages WHERE receiver='".$_SESSION["t_id"]."' AND ms_read='0'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗1");
			$m_nums = mysql_num_rows($result);
			if($m_nums!=0){$m_nums = "(<font color='red'>".$m_nums."</font>)";}else{$m_nums="";}
		?>
		<font size="2">[<a href="message.php" target="_blank"> 訊息<?php echo $m_nums;?> </a>] [<a href="../../scratch/" target="_blank">全班作品一覽</a>] [<a href="chpass.php" rel="shadowbox"> 修改密碼 </a>] [<a href="logout.php"> 登出 </a>]</font>
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
	<table class="outtable">
    <tr>
    <td colspan="2" align="center" valign="middle">
      
        <table width="0">
			<tr>
				<td>
					<input type="button" value="進度總覽" onClick="self.location='progressall.php'">
					<input type="button" value="建立任務" onClick="self.location='mkmission.php'">
					<input type="button" value="指派任務" onClick="self.location='assign.php'">
					<input type="button" value="修改任務" onClick="self.location='mdmission.php'">
				</td>
			</tr>
			<tr>
				<td>
					<input type="button" value="互評項目" onClick="self.location='termset.php'">
					<input type="button" value="評分輔語" onClick="self.location='scalenset.php'">
					<input type="button" value="回饋輔語" onClick="self.location='helpset.php'">
					<input type="button" value="禁用語詞" onClick="self.location='tabooset.php'">
				</td>
			</tr>
			<tr>
				<td>
					<input type="button" value="隨機互評" onClick="self.location='rpg.php'">
					<input type="button" value="成績一覽" onClick="self.location='scorelist.php'">
					<input type="button" value="新增學生" onClick="self.location='mkstu.php'">
					<input type="button" value="修改學生" onClick="self.location='mdstu.php'">
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
        <hr>
		</td>
	</tr>
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