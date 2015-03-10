<?php
$m_id = $_GET["mid"];

require_once("../Connections/pasql.php");
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	

$sql = "SELECT * FROM rework WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' ORDER BY rew_id DESC";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");

$maxi = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)){
	$w_id = $row["rew_id"];
	$w_name = $row["rew_name"];
	$w_desc = $row["rew_desc"];
	$w_date = $row["rew_date"];
	$t_status = $row["t_status"];
	$w_status = $row["w_status"];
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../style2.css" rel="stylesheet" type="text/css">
<!--shadowbox-->

<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />

<script src="../sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="../sb303/shadowbox.css" />

<script type="text/javascript" src="../sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->

<style type="text/css">

input.groovybutton-red-18pt
{
   font-size:18px;
   font-family:Arial,sans-serif;
   font-weight:bold;
   color:#FF0000;
   height:32px;
   background-color:#FFE7CD;
   border-style:double;
   border-color:#FF0000;
   border-width:4px;
}

</style>

<script language="javascript">

function goLite(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.color = "#FFFFFF";
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#FF0000";
   window.document.forms[FRM].elements[BTN].style.borderColor = "#FA8000";
}

function goDim(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.color = "#FF0000";
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#FFE7CD";
   window.document.forms[FRM].elements[BTN].style.borderColor = "#FF0000";
}

</script>

<title>修正後作品</title>
</head>

<body">
<br><br>
<center>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="white">

<tr>
	<td background="../img/bg-lt.png" width="60" height="60"></td>
	<td background="../img/bg-top.png" width="800" height="60">
	</td>
	<td background="../img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 start-->
	<table class="outtable">
	<tr>
		<td class="title"><?php echo $w_name;?>
		</td>
		<td class="function">
		<font color="red">
		<?php if($w_status==3){echo "[被退件]";}?>
			<?php if(isset($_GET["aresult"])){echo $_GET["aresult"];}?>
		</font>
		<input type="button" value="回首頁" onClick="self.parent.location='stu.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
		</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	<!-- 標題區 end-->
	
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">
	
	
	<!-- 內容區 start-->

<form name="groovyform">
<table>
<tr>
    	<td>

<?php
			//判斷檔案類型，展示作品
			if (substr($w_desc,-3) == ".sb"){
				echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="'.$w_desc.'">
					</applet>
					<!-- Scratch project END-->
				';
				echo "<p align=\"center\"><a href=".$w_desc." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
			else if (substr($w_desc,-3) == "jpg"||substr($w_desc,-3) == "png"||substr($w_desc,-3) == "bmp"||substr($w_desc,-3) == "gif"){
				echo "<a href=".$w_desc." rel=\"shadowbox\" target=\"_top\"><img src=".$w_desc." height=\"387\"></a>";
				echo "<p align=\"center\"><a href=".$w_desc." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
			elseif (substr($w_desc[$wnum],-4) == ".mp4"||"webm"||".ogg"){
				echo '
					<video width="600" controls>
					<source src="'.$w_desc[$wnum].'" type="video/'.str_replace('.','',strrchr($w_desc[$wnum], ".")).'">
					Your browser does not support the video tag.
					</video>	
				';
			}
			else {
				echo "<p align=\"center\"><a href=".$w_desc." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
?>
		  
		</td>
		<td align="left">
			<p><font color='blue'><b>修正後作品</b></font></p>
			<p>作者：<?php echo $_COOKIE["s_id"];?></p>
			<p>上傳時間：<?php echo $w_date;?></p><br><br>
			
<?php
//列出修正後作品
$sql_rew = "SELECT * FROM rework WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";
$result_rew = mysql_query($sql_rew,$pa);
if(!$result_rew)die("執行SQL命令失敗_rew");
$row_rew = mysql_fetch_assoc($result_rew);
	
	//判斷是否已凍結任務 (關閉刪除鈕)
	$sql_2 = "SELECT m2c.m2c_status FROM stu,m2c WHERE stu.s_id='".$_COOKIE['s_id']."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
	$result_2 = mysql_query($sql_2,$pa);
	if(!$result_2)die("執行SQL命令失敗2");
	$row_2 = mysql_fetch_assoc($result_2);
	if($row_2["m2c_status"]<4){
		echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delrework.php?wid=".$row_rew["rew_id"]."&mid=".$row_rew["m_id"]."'\"  style=\"font-size: 8 pt; border-style: ridge; border-width:2 \"><br>";
	}
	else{
		
	}

	//echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delwork.php?mid=".$m_id."&wnum=".$i."'\"  data-inline=\"true\" data-icon=\"delete\"><br>"; //實驗中,有X圖示的按鈕
	echo "</p>";


?>



			</p>
      </td>
	</tr>
</table>
</form>
<hr>




	<!-- 內容區 end-->
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-lb.png" width="60" height="60"></td>
	<td background="../img/bg-bottom.png" height="60">
	</td>
	<td background="../img/bg-rb.png" width="60" height="60"></td>
</tr>

</table>
</center>

</body>
</html>