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
<meta http-equiv="Refresh" CONTENT="10">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style2.css" rel="stylesheet" type="text/css">
<!--shadowbox-->

<!--<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />-->

<script src="./sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="./sb303/shadowbox.css" />

<script type="text/javascript" src="./sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->

<style>
#css_table {
      display:table;
  }
.css_tr {
      display: table-row;
  }
.css_td {
      display: table-cell;
  }
</style>

</head>
<body bgcolor="white">
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
		<td height="4" colspan="2" class="title">訊息</td>
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
<?php
if(isset($_GET["msid"])){
	$sql = "SELECT * FROM messages WHERE ms_id='".$_GET["msid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗m".$sql);
	$row = mysql_fetch_assoc($result);
	switch($row["category"]){
		case "t1":
			$category = "學生完成作品";
		break;
		
		case "t2":
			$category = "學生完成互評";
		break;
		
		case "t3":
			$category = "學生完成自評";
		break;
		
		case "t4":
			$category = "學生提出申訴";
		break;
		
		case "t5":
			$category = "學生重交作品";
		break;

	}
	
		echo "<tr>";
		echo "<th>類別</th><td>";
		echo "[".$category."]";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>寄件者</th><td>";
		echo $row["sender"];
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>寄件時間</th><td>";
		echo $row["ms_date"];
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>內容</th><td>";
		echo $row["contents"];
		echo "</td>";
		echo "</tr>";
		$sql = "UPDATE messages SET ms_read='1' WHERE ms_id='".$_GET["msid"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗m".$sql);
}
else{
	echo"	<tr>
				<th> 狀態 </th><th> 類別 </th><th>寄件者</th><th>寄件時間</th><th>處理進度</th>
			</tr>";

	$sql = "SELECT * FROM messages WHERE receiver='".$_SESSION["t_id"]."' ORDER BY ms_id DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗m".$sql);
	while($row = mysql_fetch_assoc($result)){
		echo "<tr>";
		echo "<td align='center'>";
		if($row["ms_read"]=="0"){
			echo "<font color='red'>new!</font>";
		}
		else{
			echo "已讀";
		}
		switch($row["category"]){
			case "t1":
				$category = "學生完成作品";
			break;
		
			case "t2":
				$category = "學生完成互評";
			break;
		
			case "t3":
				$category = "學生完成自評";
			break;
		
			case "t4":
				$category = "學生提出申訴";
			break;
		
			case "t5":
				$category = "學生重交作品";
			break;
		}
		echo "</td>";
		echo "<td align='center'>";
		echo $category;
		echo "</td>";
		echo "<td>";
		echo $row["sender"];
		echo "</td>";
		echo "<td>";
		echo $row["ms_date"];
		echo "</td>";
		echo "<td>";
		if($row["mresult"]==""){
			echo "<a href='message.php?msid=".$row["ms_id"]."'><input type='button' value='觀看'></a>";
		}
		else{
			echo $row["mresult"];
		}
		echo "</td>";
		echo "</tr>";
	}
}
?>
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
	<form name="groovyform">
		<input type="button" value="回上一頁" onClick="javascript:history.back(1)" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
		<input type="button" value="回首頁" onClick="self.parent.location='index.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
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