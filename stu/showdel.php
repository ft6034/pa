<?php
if (empty($_COOKIE['s_id'])){
	echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';
}
else{

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../style.css" rel="stylesheet" type="text/css">
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

function goLite3(FRM3,BTN3)
{
   window.document.forms[FRM3].elements[BTN3].style.color = "#FFFFFF";
   window.document.forms[FRM3].elements[BTN3].style.backgroundColor = "#466900";
   window.document.forms[FRM3].elements[BTN3].style.borderColor = "#FA8000";
}

function goDim3(FRM3,BTN3)
{
   window.document.forms[FRM3].elements[BTN3].style.color = "#466900";
   window.document.forms[FRM3].elements[BTN3].style.backgroundColor = "transparent";
   window.document.forms[FRM3].elements[BTN3].style.borderColor = "#466900";
}
</script>

<title>Scratch作品展示</title>
</head>

<body>
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
		<td class="title">檔案回收桶</td>
		<td align="right"><input type="button" value="回上一頁" onClick="self.location='delog.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 "></td>
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
    	<td><table><tr><td>

<?php
	if(isset($_GET["id"])){$del_id = $_GET["id"];}
	if(isset($_GET["s"])){$status = $_GET["s"];}
	

	require_once("../Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	

	$sql = "SELECT * FROM delworks WHERE del_id='".$del_id ."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);
	
	if($status=="view"){

			//判斷檔案類型，展示作品
			if (substr($row["w_desc"],-3) == ".sb"){
				echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="'.$row["w_desc"].'">
					</applet>
					<!-- Scratch project END-->
				';
				echo "<p align=\"center\"><a href=".$row["w_desc"]."> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
			elseif (substr($row["w_desc"],-3) == "jpg"||substr($row["w_desc"],-3) == "png"||substr($row["w_desc"],-3) == "bmp"||substr($row["w_desc"],-3) == "gif"){
				echo "<a href=".$row["w_desc"]." rel=\"shadowbox\" target=\"_top\"><img src=".$row["w_desc"]." height=\"387\"></a>";
				echo "<p align=\"center\"><a href=".$row["w_desc"]."> [ <font color='green'>下載作品▼</font> ] </a></p>";
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
				echo "<p align=\"center\"><a href=".$row["w_desc"]."> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
		  
		echo "</td>";
		echo '<td align="left">';
		echo '	<p>上傳時間：'.$row["w_date"].'</p>';
		echo '	<p>刪除時間：'.$row["del_date"].'</p><br><br>';
		/*
		echo "<input type=\"button\" value=\"復原\" onClick=\"self.parent.location='showdel.php?id=".$row["del_id"]."&s=recover'\" style=\"font-size: 12 pt; border-style: ridge; border-width:3 \">";
		*/
	}
	elseif($status=="recover"){
		echo "復原";
	}
	

//echo "<input type=\"button\" value=\"回首頁\" onClick=\"self.parent.location='stu.php'\" style=\"font-size: 12 pt; border-style: ridge; border-width:3 \">";


}
?>



			</td></tr></table>
      </td>
	</tr>
</table>
</form>
	
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