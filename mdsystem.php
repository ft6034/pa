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

//有學年學期，就更新
//學年學期的預設值由system取得
if(isset($_POST["syear"])){
		$sql = "UPDATE system SET syear='".$_POST["syear"]."' WHERE id='1'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		else{
			//echo $sql;
			
			
			//取教師帳號
			$sql = "SELECT t_id FROM teacher";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗1");
			while($row = mysql_fetch_assoc($result)){
				
				//依教師帳號建立新學年學期的虛擬班級及虛擬學生帳號
				
				//新增虛擬班級
				$sql_c = "INSERT INTO class(c_class, syear)";
				$sql_c .= " VALUES('t_".$row["t_id"]."_c', '".$_POST["syear"]."')";
				$result_c = mysql_query($sql_c,$pa);
		
				//取得c_id，建立班級資料夾
				$sql_cid = "SELECT c_id FROM class WHERE c_class='t_".$row["t_id"]."_c' AND syear='".$_POST["syear"]."'";
				$result_cid = mysql_query($sql_cid,$pa);
				if(!$result_cid)die("執行SQL命令失敗_cid");
				$row_cid = mysql_fetch_assoc($result_cid);
				$c_id = $row_cid["c_id"];
				mkdir("./stu/works/".$c_id."_t_".$row["t_id"]."_c",'0777');
		
				//指派虛擬班級給教師
				$sql_c2t = "INSERT INTO c2t(c_id, t_id)";
				$sql_c2t .= " VALUES('".$c_id."', '".$row["t_id"]."')";
				$result_c2t = mysql_query($sql_c2t,$pa);
				if(!$result_c2t)die("執行SQL命令失敗_c2t");
		
				//更新虛擬學生的班級資料,t-教師id-兩位數字
				for($i=1;$i<7;$i++){
				
					//更新stu的c_id
					$sql_stu = "UPDATE stu SET c_id='".$c_id."' WHERE s_id='t_".$row["t_id"]."_".str_pad($i,2,"0",STR_PAD_LEFT)."'";
					$result_stu = mysql_query($sql_stu,$pa);
					if(!$result_stu)die("執行SQL命令失敗_stu");
					
					//新增s2c的紀錄
					$sql_s2c = "INSERT INTO s2c(syear, s_id, c_id, s_classnums)";
					$sql_s2c .= " VALUES('".$_POST["syear"]."', 't_".$row["t_id"]."_".str_pad($i,2,"0",STR_PAD_LEFT)."', '".$c_id."', '".$i."')";
					$result_s2c = mysql_query($sql_s2c,$pa);
					if(!$result_s2c)die("執行SQL命令失敗_s2c");
				}
				
			}
			
			echo "<script language='javascript'>";
			echo "  alert('成功設定學年學期為".$_POST["syear"]."');";
			echo "document.location.href='admin.php';";
			echo "</script>";
		}
}

?>

<html>
<head>
<title>設定系統參數</title>
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
		設定學年學期
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
	
      
    <form action="mdsystem.php" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>目前的學年學期：</td><td><input type="text" name="syear" value="<?php echo $syear;?>"></td>
			</tr>
			<tr>
				<td>
				其他的學年學期：
				</td>
				<td>
					<table>
						<?php
							$sql = "SELECT DISTINCT syear FROM mission";
							$result = mysql_query($sql,$pa);
							if(!$result)die("執行SQL命令失敗1");
							while($row = mysql_fetch_assoc($result)){
								if($row["syear"]!=$syear){
									echo "<tr><td>".$row["syear"]."</td></tr>";
								}
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
			<input type="submit" name="Submit" value="確定修改">
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