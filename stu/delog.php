<?php
if (empty($_COOKIE['s_id'])){
	echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';
}
else{

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

function goLite4(FRM4,BTN4)
{
   window.document.forms[FRM4].elements[BTN4].style.color = "#FFFFFF";
   window.document.forms[FRM4].elements[BTN4].style.backgroundColor = "#8F19FF";
   window.document.forms[FRM4].elements[BTN4].style.borderColor = "#FA8000";
}

function goDim4(FRM4,BTN4)
{
   window.document.forms[FRM4].elements[BTN4].style.color = "#8F19FF";
   window.document.forms[FRM4].elements[BTN4].style.backgroundColor = "transparent";
   window.document.forms[FRM4].elements[BTN4].style.borderColor = "#8F19FF";
}
</script>

<title>回收桶</title>
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
		<td align="right"><input type="button" value="回首頁" onClick="self.parent.location='stu.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 "></td>
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
<table width="0" cellpadding="5" class="outtable">
<tr>
    	<td>

<?php
	//取出已刪除檔案的紀錄
	require_once("../Connections/pasql.php");
	//開啟資料庫
	$db_selected = mysql_select_db($database_pa, $pa);
	if(!$db_selected)die("無法開啟資料庫");	

	$sql = "SELECT * FROM delworks WHERE s_id='".$_COOKIE['s_id']."' ORDER BY del_date DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗");
	//$row["w_desc"];
	echo '<div align="center">';
	echo '	<table width="0" cellpadding="5" class="outtable">';
	echo '		<tr><th height="20" width="110">作品名稱</th><th>上傳時間</th><th>刪除時間</th><td>　</td><td>　</td></tr>';
	while ($row = mysql_fetch_assoc($result)){
		echo '	<tr><td align="center">'.$row["w_name"].'</td><td align="center">'.$row["w_date"].'</td><td align="center">'.$row["del_date"].'</td>';
		//echo "		<td align='center'><input type=\"button\" value=\"瀏覽\" onClick=\"self.parent.location='showdel.php?id=".$row["del_id"]."&s=view'\" style=\"font-size: 12 pt; border-style: ridge; border-width:3 \"></td>";
		echo "<td align='center'><a href=\"showdel.php?id=".$row["del_id"]."&s=view\" >";
		echo '<input 
							type="button"
							name="groovybtn21-'.$row["del_id"].'"
							class="groovybutton-purple"
							value="瀏覽"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a></td>';
		
		echo "<td align='center'><a href=\"showdel.php?id=".$row["del_id"]."&s=recover\" rel=\"shadowbox\">";
		/*
		echo '<input 
							type="button"
							name="groovybtn22-'.$row["del_id"].'"
							class="groovybutton-green"
							value="復原"
							title=""
							onMouseOver="goLite3(this.form.name,this.name)"
							onMouseOut="goDim3(this.form.name,this.name)" ></a>';
		*/
		echo '</td>	</tr>';
	}
	echo '	</table>';

}
?>



			</p>
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