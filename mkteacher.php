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

$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

if(isset($_POST["t_name"]) && isset($_POST["t_id"]) && isset($_POST["t_pass"])){

	//查詢帳號是否重複
	$sql = "SELECT t_name FROM teacher WHERE t_id='".$_POST["t_id"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	if(mysql_num_rows($result)==0){
		//新增帳號
		$sql = "INSERT INTO teacher(t_name, t_id, t_pass)";
		$sql .= " VALUES('".$_POST["t_name"]."', '".$_POST["t_id"]."', '".$_POST["t_pass"]."')";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗3");
		else{
			echo "<script language='javascript'>";
			echo "  alert('新增".$_POST["t_name"]."的".$_POST["t_id"]."帳號成功！');";
			echo "document.location.href='mkteacher.php';";
			echo "</script>";
		}
		//新增虛擬班級
		$sql = "INSERT INTO class(c_class, syear)";
		$sql .= " VALUES('t_".$_POST["t_id"]."_c', '".$_POST["syear"]."')";
		$result = mysql_query($sql,$pa);
		
		//取得c_id，建立班級資料夾
		$sql_cid = "SELECT c_id FROM class WHERE c_class='t_".$_POST["t_id"]."_c' AND syear='".$_POST["syear"]."'";
		$result_cid = mysql_query($sql_cid,$pa);
		if(!$result_cid)die("執行SQL命令失敗_cid");
		$row_cid = mysql_fetch_assoc($result_cid);
		$c_id = $row_cid["c_id"];
		mkdir("./stu/works/".$c_id."_t_".$_POST["t_id"]."_c",'0777');
		
		//指派班級給教師
		$sql = "INSERT INTO c2t(c_id, t_id)";
		$sql .= " VALUES('".$c_id."', '".$_POST["t_id"]."')";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_c2t");
		/*else{
			echo "<script language='javascript'>";
			echo "  alert('新增".$_POST["syear"]."學期".$_POST["c_class"]."班成功！');";
			echo "document.location.href='mkclass.php';";
			echo "</script>";
		}*/

		//新增虛擬學生,t-教師id-兩位數字
		for($i=1;$i<7;$i++){
			$sql = "INSERT INTO stu(s_id, s_name, s_sex, c_id, s_classnums)";
			$sql .= " VALUES('t_".$_POST["t_id"]."_".str_pad($i,2,"0",STR_PAD_LEFT)."', '測試生".str_pad($i,2,"0",STR_PAD_LEFT)."號', '".($i%2)."', '".$c_id."', '".$i."')";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗_stu");
		}
		if(!$result)die("執行SQL命令失敗");
		/*else{			
			echo "<script language='javascript'>";
			echo "  alert('新增".$_POST["syear"]."學期".$_POST["c_class"]."班成功！');";
			echo "document.location.href='mkclass.php';";
			echo "</script>";
		}*/

	}
	else{			
		echo "<script language='javascript'>";
		echo "  alert('此帳號已存在，請設定別的帳號名稱！');";
		echo "document.location.href='mkteacher.php';";
		echo "</script>";
	}
}
else if(isset($_POST["t_check"])){			
	echo "<script language='javascript'>";
	echo "  alert('所有欄位均為必填！');";
	echo "document.location.href='mkteacher.php';";
	echo "</script>";
}
?>

<html>
<head>
<title>新增教師</title>
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
		新增教師
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
	
    <form action="mkteacher.php" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>教師姓名：</td><td><input type="text" name="t_name"></td>
			</tr>
			<tr>
				<td>教師帳號：</td><td><input type="text" name="t_id"></td>
			</tr>
			<tr>
				<td>教師密碼：</td><td><input type="password" name="t_pass"></td>
			</tr>
			<tr>
				<td>學年學期：</td><td><input type="text" name="syear" value="<?php echo $syear;?>"></td>
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
        
        <p align="center">
			<input type="hidden" name="t_check" value="1">
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