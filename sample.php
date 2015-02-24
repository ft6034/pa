<?php
$m_id = $_GET["mid"];

require_once("./Connections/pasql.php");
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT m_name,m_desc,m_spath FROM mission WHERE m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗");

$row = mysql_fetch_assoc($result);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style2.css" rel="stylesheet" type="text/css">
<title>Scratch範例展示</title>
</head>

<body>
<br><br>
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

	<!-- 標題區 -->
	<table class="outtable">
	<tr>
		<td class="title"><?php echo $row["m_name"];?></td>
		<td class="function">&nbsp;</td>
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
	
	<table class="outtable">
	<tr>  	
	<!-- 內容區 -->
	<td width="390">
			<div class="container" align="center">
			 <!-- Scratch project START-->
			  <applet id="ProjectApplet"
			    style="display:block"
			    code="ScratchApplet" codebase="./"
			    archive="ScratchApplet.jar" height="387" width="482">
			   <param name="project" value="<?php echo $row["m_spath"];?>">
			 </applet>
			 <!-- Scratch project END-->
		  </div>
		</td>
		<td width="390">
			<p><font color="#CE2FCA" size="4"><?php echo $row["m_desc"];?></font></p>
      </td>
	</tr>
	</table>
	
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