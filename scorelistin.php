<?php
session_start();

$now_date = date("Y.m.d");
$now_time = date("H.i.s",time()+(8*60*60));

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
//取目前的學年學期
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

if(isset($_COOKIE["c_id"]) && isset($_COOKIE["c_name"])){
	$c_id = $_COOKIE["c_id"];
	$c_name = $_COOKIE["c_name"];
}
else{
	$c_id = "";
	$m_id = "";
	$c_name = "";
	$m_name = "";
}


setcookie("c_id","");
setcookie("m_id","");
setcookie("c_name","");
setcookie("m_name","");

?>
<html>
<head>
<title>班級進度一覽</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--shadowbox-->

<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />

<script src="sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="sb303/shadowbox2.css" />

<script type="text/javascript" src="sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->

</head>
<body bgcolor="#FFF2CD">
<div align="center">
      <form action="scorelist.php" method="post" enctype="multipart/form-data">
        <table width="0" cellpadding="5">

<?php
if($c_id!=""){
		echo "<tr><th>座號</th><th>姓名</th>";

		//判斷是否有任務
		if($c_id!=""){
			
			//取任務數量
			$sql = "SELECT mission.m_id, mission.m_name, mission.m_proportion FROM m2c,mission WHERE mission.m_id=m2c.m_id AND m2c.c_id='".$c_id."' AND mission.syear='".$syear."' ORDER BY mission.m_order";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗m_num");
			$m_num = mysql_num_rows($result);
			
			for($i=0;$i<$m_num;$i++){
				while($row = mysql_fetch_assoc($result)){
					echo "<th><font color='#A00200'>".$row["m_name"]." (x".$row["m_proportion"].")</font></th>";
					$m_ids[] = $row["m_id"];
					$m_proportions[] = $row["m_proportion"];
				}
			}
			echo "<th>原始總分</th>";
			echo "<th>進步加分</th>";
			echo "<th>總分</th>";
			echo "</tr>";
			
			//以c_id，從stu取s_id
				$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
				$result_si = mysql_query($sql_si,$pa);
				if(!$result_si)die("執行SQL命令失敗_si");
				$num_all = 0;
				$num_uploaded[] = 0;
				$num_pa[] = 0;
				$num_sa[] = 0;
				while($row_si = mysql_fetch_assoc($result_si)){
				
					for($i=0;$i<$m_num;$i++){
						//以m_id,s_id，從progress2stu取p_uploaded,p_pa,p_sa 進度數字、學生數(資料數)
						$sql_p2s = "SELECT p_uploaded,p_pa,p_sa FROM progress2stu WHERE m_id='".$m_ids[$i]."' AND s_id='".$row_si["s_id"]."'";
						$result_p2s = mysql_query($sql_p2s,$pa);
						if(!$result_p2s)die("執行SQL命令失敗_p2s");
						$row_p2s = mysql_fetch_assoc($result_p2s);
						
						if($i==0){
							echo "<tr>";
							echo "<td>".$row_si["s_classnums"]."</td>";
							if( isset($_SESSION["t_id"]) || isset($_SESSION["admin_id"]) || isset($_COOKIE["s_id"]) ) {
								echo "<td><b>".$row_si["s_name"]."</b></td>";
							}
							else{
								echo "<td><b>".mb_substr($row_si["s_name"], 0, 1, 'UTF-8')."○".mb_substr($row_si["s_name"], 2, 1, 'UTF-8')."</b></td>";
							}
						}
						
						echo "<td>";
						
							//取sca_id
							$sql_s = "SELECT sca_id FROM scale WHERE m_id='".$m_ids[$i]."'";
							$result_s = mysql_query($sql_s,$pa);
							if(!$result_s)die("執行SQL命令失敗_s");
							while($row_s = mysql_fetch_assoc($result_s)){
							$sql_scarid = "SELECT sca_reply FROM scaletr WHERE pg_sid='".$row_si["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
							$result_scarid = mysql_query($sql_scarid,$pa);
							if(!$result_scarid)die("執行SQL命令失敗_scarid");
							while($row_scarid = mysql_fetch_assoc($result_scarid)){
								$avg_ta[] = $row_scarid["sca_reply"]*$m_proportions[$i];
							}
							if(mysql_num_rows($result_scarid)==0){
								$avg_ta[] = 0;
								}
							}
							if(count($avg_ta)!=0){
								//平均分數
								echo round(array_sum($avg_ta)/count($avg_ta),1);
								$scores[] = round(array_sum($avg_ta)/count($avg_ta),1);
								unset($avg_ta);
								//取得進步分數
								$sql_up = "SELECT up_point FROM rework WHERE m_id='".$m_ids[$i]."' AND s_id='".$row_si["s_id"]."'";
								$result_up = mysql_query($sql_up,$pa);
								if(!$result_up)die("執行SQL命令失敗_up");
								$row_up = mysql_fetch_assoc($result_up);
								$up_points[] = $row_up["up_point"]*$m_proportions[$i];
							}
									
						echo "</td>";
					}
					
					echo "<td>";
					echo round(array_sum($scores)/array_sum($m_proportions),1);
					echo "</td>";
					
					echo "<td>";
					echo round((array_sum($up_points)/array_sum($m_proportions))/$m_num,1);
					echo "</td>";
					
					echo "<td><b>";
					echo round(array_sum($scores)/array_sum($m_proportions),1) + round((array_sum($up_points)/array_sum($m_proportions))/$m_num,1);
					echo "</td></b>";
					
					unset($scores);					
					unset($up_points);

					echo "</tr>";
					
					
					$num_all += 1;
				}
			unset($m_proportions);
		}
		else{
			echo "</tr>";
		}

}
else{
?>
        </table>

      </form>
<?php
	$sql = "SELECT c_id,c_class FROM class WHERE syear='".$syear."' ORDER BY c_class";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗_取班級");
	
			
//選擇班級
echo '
<script language="Javascript">
function playmidi()
{
   switch(midiform.mysel.selectedIndex)
   {
';
$j=1;
while($row = mysql_fetch_assoc($result)){
	echo '
      case '.$j.':
         parent.location.href="scorelist.php?cid='.$row["c_id"].'&cname='.$row["c_class"].'";
         break;
	';
	$c_classes[] = $row["c_class"];
	$j++;
}


echo '		 

   }
}
</script>

<form name=midiform>
<font size="5"><b>請選擇班級：</b></font><select name=mysel onchange="playmidi()">
<option>請選擇班級
';

for($k=0;$k<$j-1;$k++){
	//不顯示測試班
	if( isset($_SESSION["t_id"]) || isset($_SESSION["admin_id"]) ) {
		echo '
		<option>'.$c_classes[$k].'
		';
	}
	else{
		if(substr($c_classes[$k],0,1)!="t"){
			echo '
			<option>'.$c_classes[$k].'
			';
		}
	}
}

echo '
</select>
</form>
';

}
?>

</div>
</body>
</html>