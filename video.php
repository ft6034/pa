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
<body>
<p align="center">
<table>
<tr>
	<td>
	任務名稱：<?php echo $row["m_name"];?>
	</td>
</tr>
<tr>
	<td align="center">
	<video controls>
	  <source src="<?php echo $row["m_spath"];?>" type="video/<?php echo str_replace('.','',strrchr($row["m_spath"], ".")); ?>">
	  Your browser does not support the video tag.
	</video>
	</td>
</tr>
<tr>
	<td>
	<?php echo $row["m_desc"];?>
	</td>
</tr>
</table>
</p>

</body>
</html>