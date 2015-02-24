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
說明文字

</body>
</html>