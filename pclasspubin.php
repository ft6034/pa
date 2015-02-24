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
      <form action="pclasspub.php" method="post" enctype="multipart/form-data">
        <table width="0" cellpadding="5">
		<tr>
			<td colspan="99"><font color="#BE005D">
				<img src="./img/OF.png" width="20" height="20" alt="修正後" >修正後　
				<img src="./img/O.png" width="20" height="20" alt="已審核" >已審核　
				<img src="./img/OB.png" width="20" height="20" alt="已送出" >已送出　
				<img src="./img/B.png" width="20" height="20" alt="製作中" >製作中　
				<img src="./img/XB.png" width="20" height="20" alt="被退件" >被退件　
				<img src="./img/X.png" width="20" height="20" alt="未繳交" >未繳交</font>
			</td>
		</tr>
<?php
if($c_id!=""){
		echo "<tr><th>座號</th><th>姓名</th>";

		//判斷是否有任務
		if($c_id!=""){
			
			//取任務數量
			$sql = "SELECT mission.m_id,mission.m_name FROM m2c,mission WHERE mission.m_id=m2c.m_id AND m2c.c_id='".$c_id."' AND mission.syear='".$syear."' ORDER BY mission.m_order";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗m_num");
			$m_num = mysql_num_rows($result);
			
			for($i=0;$i<$m_num;$i++){
				while($row = mysql_fetch_assoc($result)){
					echo "<th><font color='#A00200'>".$row["m_name"]."</font></th>";
					$m_ids[] = $row["m_id"];
				}
			}
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
						if($row_p2s["p_uploaded"]!=0){
							$sql_wsta = "SELECT t_status,w_status FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_ids[$i]."' ORDER BY w_id DESC";
							$result_wsta = mysql_query($sql_wsta,$pa);
							if(!$result_wsta)die("執行SQL命令失敗_wsta");
							$row_wsta = mysql_fetch_assoc($result_wsta);
							if($row_wsta["w_status"]=="2"){
								if($row_wsta["t_status"]=="2"){
									$sql_rewsta = "SELECT t_status,w_status,rew_id FROM rework WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_ids[$i]."' ORDER BY rew_id DESC";
									$result_rewsta = mysql_query($sql_rewsta,$pa);
									if(!$result_rewsta)die("執行SQL命令失敗_rewsta");
									$row_rewsta = mysql_fetch_assoc($result_rewsta);
									if(mysql_num_rows($result_rewsta)==0){
										//判斷是否進入自評階段
										
										
										// 判斷是否開放互評
										$sql_m2c = "SELECT m2c_status FROM m2c WHERE m_id='".$m_ids[$i]."' AND c_id='".$c_id."'";
										$result_m2c = mysql_query($sql_m2c,$pa);
										if(!$result_m2c)die("執行SQL命令失敗_m2c");
										$row_m2c = mysql_fetch_assoc($result_m2c);
										if($row_m2c["m2c_status"]>2){
											echo '<a href="showpub.php?mid='.$m_ids[$i].'&sid='.$row_si["s_id"].'" rel="shadowbox" target="_top">
												<img src="./img/O.png" width="20" height="20" alt="已評審，觀看作品" >
												</a>';
										}
										else{
											echo '<img src="./img/O.png" width="20" height="20" alt="已評審，尚未開放觀看作品" >';
										}
									}
									else{
										echo '<a href="showrepub.php?mid='.$m_ids[$i].'&sid='.$row_si["s_id"].'" rel="shadowbox" target="_top">
										<img src="./img/OF.png" width="20" height="20" alt="已評審，觀看修正後作品" >
										</a>';
									}
								}
								else{
									echo '<img src="./img/OB.png" width="20" height="20" alt="已送出" >';
									//echo '<a href="showpub.php?mid='.$m_ids[$i].'&sid='.$row_si["s_id"].'" rel="shadowbox" target="_top">
								//<img src="./img/OB.png" width="20" height="20" alt="已送出，觀看作品" >
								//</a>';
								}
							}
							elseif($row_wsta["w_status"]=="3"){
								echo '<a href="showpub.php?mid='.$m_ids[$i].'&sid='.$row_si["s_id"].'" rel="shadowbox" target="_top">
								<img src="./img/XB.png" width="20" height="20" alt="被退件" >
								</a>';
							}
							else{
								echo '<img src="./img/B.png" width="20" height="20" alt="製作中" >';
								//echo '<a href="showpub.php?mid='.$m_ids[$i].'&sid='.$row_si["s_id"].'" rel="shadowbox" target="_top">
								//<img src="./img/B.png" width="20" height="20" alt="製作中" >
								//</a>';
							}
						}
						else{
							echo '<img src="./img/X.png" width="20" height="20" alt="尚未繳交" >';
						}
						echo "</td>";
						/*
						echo "<td>";
						if($row_p2s["p_pa"]!=0){
							echo '<a href=""><img src="./img/O.png" width="20" height="20" alt="O" ></a>';
						}
						else{
							echo '<a href=""><img src="./img/X.png" width="20" height="20" alt="X" ></a>';
						}
						echo "</td>";
						echo "<td>";
						if($row_p2s["p_sa"]!=0){
							echo '<a href=""><img src="./img/O.png" width="20" height="20" alt="O" ></a>';
						}
						else{
							echo '<a href=""><img src="./img/X.png" width="20" height="20" alt="X" ></a>';
						}
						echo "</td>";
						*/
						
						@$num_uploaded2[$i] = $row_p2s["p_uploaded"]; //已繳交人數
						@$num_uploaded[$i] += $row_p2s["p_uploaded"]; //已繳交人數
						@$num_pa[$i] += $row_p2s["p_pa"]; //已互評人數
						@$num_sa[$i] += $row_p2s["p_sa"]; //已自評人數
						
					}
					echo "</tr>";
					
					
					$num_all += 1;
				}
		}
		else{
			echo "</tr>";
		}
		
		//從stu取學生座號、姓名
		//以m_id,s_id，從progress2stu取p_uploaded,p_pa,p_sa 進度數字、學生數(資料數)

		echo "<tr>";				
		echo "<td></td>";
		echo "<td></td>";
		for($i=0;$i<$m_num;$i++){

			echo "<td>".$num_uploaded[$i]."/".$num_all."</td>";
			/*
			echo "<td>".$num_pa[$i]."/".$num_all."</td>";
			echo "<td>".$num_sa[$i]."/".$num_all."</td>";
			*/
		}
		echo "</tr>";
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
         parent.location.href="pclasspub.php?cid='.$row["c_id"].'&cname='.$row["c_class"].'";
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