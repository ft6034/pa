<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}

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

if(isset($_GET["cid"]) && isset($_GET["mid"]) && isset($_GET["cname"]) && isset($_GET["mname"])){
	$c_id = $_GET["cid"];
	$m_id = $_GET["mid"];
	$c_name = $_GET["cname"];
	$m_name = $_GET["mname"];
}
else{
	$c_id = "";
	$m_id = "";
	$c_name = "";
	$m_name = "";
}
//$m_grade = $_GET["grade"];
$m_grade = $_SESSION["m_grade"];



?>
<html>
<head>
<title>班級進度一覽</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style.css" rel="stylesheet" type="text/css">
<!--shadowbox-->

<!--<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />-->

<script src="sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="sb303/shadowbox.css" />

<script type="text/javascript" src="sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->
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
		<td colspan="2" class="menu">
		[<a href="progressall.php?grade=<?php echo $m_grade;?>">回進度總覽</a>]
		[<a href="index.php">回首頁</a>]
		[<a href="logout.php">登出系統</a>]
		</td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title"><?php echo "[".$m_name."]";?> <?php echo $c_name;?>班</td>
		<td class="function">&nbsp;</td>
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
	
	<form action="pclass.php" method="post" enctype="multipart/form-data">
        <table width="0" cellpadding="5">
		<tr>
			<td colspan="99"><font color="#BE005D">
				<img src="./img/O.png" width="20" height="20" alt="已審核" >已審核　
				<img src="./img/OB.png" width="20" height="20" alt="已送出" >已送出　
				<img src="./img/B.png" width="20" height="20" alt="製作中" >製作中　
				<img src="./img/XB.png" width="20" height="20" alt="被退件" >被退件　
				<img src="./img/X.png" width="20" height="20" alt="未繳交" >未繳交</font>
			</td>
		</tr>
<?php
		echo "<tr><th>座號</th><th>姓名</th><th>繳交</th><td>教師評分</td><th>互評</th><td>評人</td><td>被評</td><th>自評</th><th>修正後作品</th></tr>";
		
		//從stu取學生座號、姓名
		//以m_id,s_id，從progress2stu取p_uploaded,p_pa,p_sa 進度數字、學生數(資料數)
				
				//以c_id，從stu取s_id
				$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
				$result_si = mysql_query($sql_si,$pa);
				if(!$result_si)die("執行SQL命令失敗_si");
				$num_all = 0;
				$num_uploaded = 0;
				$num_pa = 0;
				$num_sa = 0;
				while($row_si = mysql_fetch_assoc($result_si)){
				
					//以m_id,s_id，從progress2stu取p_uploaded,p_pa,p_sa 進度數字、學生數(資料數)
					$sql_p2s = "SELECT p_uploaded,p_pa,p_sa FROM progress2stu WHERE m_id='".$m_id."' AND s_id='".$row_si["s_id"]."'";
					$result_p2s = mysql_query($sql_p2s,$pa);
					if(!$result_p2s)die("執行SQL命令失敗_p2s");
					$row_p2s = mysql_fetch_assoc($result_p2s);
					echo "<tr align='center'>";
					echo "<td>".$row_si["s_classnums"]."</td>";
					echo "<td>".$row_si["s_name"]."</td>";
					echo "<td>";
						//是否繳交
						if($row_p2s["p_uploaded"]!=0){
							$sql_wsta = "SELECT t_status,w_status,w_id FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_id."' ORDER BY w_id DESC";
							$result_wsta = mysql_query($sql_wsta,$pa);
							if(!$result_wsta)die("執行SQL命令失敗_wsta");
							$row_wsta = mysql_fetch_assoc($result_wsta);
							if($row_wsta["w_status"]=="2"){
								if($row_wsta["t_status"]=="2"){
									echo '<a href="showwork.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/O.png" width="20" height="20" alt="已評審，觀看作品" ></a>';
									
									//取sca_id
									$sql_s = "SELECT sca_id FROM scale WHERE m_id='".$m_id."'";
									$result_s = mysql_query($sql_s,$pa);
									if(!$result_s)die("執行SQL命令失敗_s");
									while($row_s = mysql_fetch_assoc($result_s)){
										$sql_scarid = "SELECT sca_reply FROM scaletr WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$row_si["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
										$result_scarid = mysql_query($sql_scarid,$pa);
										if(!$result_scarid)die("執行SQL命令失敗_scarid");
										while($row_scarid = mysql_fetch_assoc($result_scarid)){
											$avg_ta[] = $row_scarid["sca_reply"];
										}
									}
									if(count($avg_ta)!=0){
										//平均自評分數
										echo "<td>".round(array_sum($avg_ta)/count($avg_ta),1)."</td>";
										unset($avg_ta);
									}
									
									
								}
								else{
									echo '<a href="showwork.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/OB.png" width="20" height="20" alt="已送出，觀看作品" ></a><td></td>';
								}
							}
							elseif($row_wsta["w_status"]=="3"){
								echo '<a href="showwork.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/XB.png" width="20" height="20" alt="被退件" ></a><td></td>';
							}
							else{
								echo '<a href="showwork.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/B.png" width="20" height="20" alt="製作中" ></a><td></td>';
							}
						}
						else{
							echo '<a href=""><img src="./img/X.png" width="20" height="20" alt="尚未繳交" ></a><td></td>';
						}
					echo "</td>";
					echo "<td>";
						//是否互評
						//判斷是否已完成互評
						$pg_fin="";
						$sql_pg = "SELECT pg_pas FROM pg WHERE s_id='".$row_si["s_id"]."' AND m_id ='".$m_id."'";
						$result_pg = mysql_query($sql_pg,$pa);
						if(!$result_pg)die("執行SQL命令失敗_pg");
						$row_pg = mysql_fetch_assoc($result_pg);
						$all_pg = explode("-",$row_pg["pg_pas"]);
						for($i=0;$i<count($all_pg);$i++){
							if($all_pg[$i]=="0"||$all_pg[$i]=="1"){
								$pg_fin="unfin";
							}						
						}
						if($pg_fin!="unfin"){
							echo '<a href="showpa.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
							<img src="./img/OB.png" width="20" height="20" alt="已送出" >
							</a>';
							$num_pa_ox = 1;
						}
						else{
							echo '<a href="showpa.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
							<img src="./img/X.png" width="20" height="20" alt="尚未完成" >
							</a>';
							$num_pa_ox = 0;
						}
					//取sca_id
					$avg_pg[] = "";
					$avg_s[] = "";
					$sql_s = "SELECT sca_id FROM scale WHERE m_id='".$m_id."'";
					$result_s = mysql_query($sql_s,$pa);
					if(!$result_s)die("執行SQL命令失敗_s");
					while($row_s = mysql_fetch_assoc($result_s)){
						$sql_scarid = "SELECT sca_reply FROM scaler WHERE s_id='".$row_si["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
						$result_scarid = mysql_query($sql_scarid,$pa);
						if(!$result_scarid)die("執行SQL命令失敗_scarid");
						while($row_scarid = mysql_fetch_assoc($result_scarid)){
							$avg_s[] = $row_scarid["sca_reply"];
						}
						$sql_scarid = "SELECT sca_reply FROM scaler WHERE pg_sid='".$row_si["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
						$result_scarid = mysql_query($sql_scarid,$pa);
						if(!$result_scarid)die("執行SQL命令失敗_scarid");
						while($row_scarid = mysql_fetch_assoc($result_scarid)){
							$avg_pg[] = $row_scarid["sca_reply"];
						}
					}
					echo "</td><td>";
					if(count($avg_s)!=0){
						//平均評他人分數
						echo round(array_sum($avg_s)/count($avg_s),1);
						unset($avg_s);
					}
					echo "</td>";
					
					echo "<td>";
					if(count($avg_pg)!=0){
						//平均被評分數
						echo round(array_sum($avg_pg)/count($avg_pg,1));
						unset($avg_pg);
					}

					echo "</td>";
					echo "<td>";
						//是否自評
						//判斷是否已完成互評
						$sql_wsta = "SELECT sa_status FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_id."' AND w_status='2'";
						$result_wsta = mysql_query($sql_wsta,$pa);
						if(!$result_wsta)die("執行SQL命令失敗_wsta");
						$row_wsta = mysql_fetch_assoc($result_wsta);
						$sa_status = $row_wsta["sa_status"];
						if($sa_status=="2"){
							echo '<a href="showsa.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
							<img src="./img/OB.png" width="20" height="20" alt="已送出" >
							</a>';
							$num_sa_ox = 1;
							
							//取sca_id
							$sql_s = "SELECT sca_id FROM scale WHERE m_id='".$m_id."'";
							$result_s = mysql_query($sql_s,$pa);
							if(!$result_s)die("執行SQL命令失敗_s");
							while($row_s = mysql_fetch_assoc($result_s)){
								$sql_scarid = "SELECT sca_reply FROM scaler WHERE s_id='".$row_si["s_id"]."' AND pg_sid='".$row_si["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
								$result_scarid = mysql_query($sql_scarid,$pa);
								if(!$result_scarid)die("執行SQL命令失敗_scarid");
								while($row_scarid = mysql_fetch_assoc($result_scarid)){
									$avg_sa[] = $row_scarid["sca_reply"];
								}
							}
							if(count($avg_sa)!=0){
								//平均自評分數
								echo round(array_sum($avg_sa)/count($avg_sa),1);
								unset($avg_sa);
							}
						}
						elseif($sa_status=="1"){
							echo '<a href="showsa.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
							<img src="./img/B.png" width="20" height="20" alt="已儲存，尚未送出" >
							</a>';
							$num_sa_ox = 1;
						}
						else{
							echo '<a href="showsa.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
							<img src="./img/X.png" width="20" height="20" alt="尚未繳交" ></a>';
							$num_sa_ox = 0;
						}
					echo "</td>";
					echo "<td>";
					//修正後作品
					$sql_wsta = "SELECT t_status,w_status,rew_id FROM rework WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_id."' ORDER BY rew_id DESC";
					$result_wsta = mysql_query($sql_wsta,$pa);
					if(!$result_wsta)die("執行SQL命令失敗_wsta");
					$row_wsta = mysql_fetch_assoc($result_wsta);
					if(mysql_num_rows($result_wsta)==0){
						echo "無";
					}
					else{
						if($row_wsta["w_status"]==3){
							echo '<a href="showrework.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/XB.png" width="20" height="20" alt="被退件" ></a>';
						}
						elseif($row_wsta["t_status"]==2){
							echo '<a href="showrework.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/O.png" width="20" height="20" alt="已評審，觀看作品" ></a>';
						}
						else{
							echo '<a href="showrework.php?mid='.$m_id.'&sid='.$row_si["s_id"].'&grade='.$m_grade.'" rel="shadowbox" target="_top">
								<img src="./img/OB.png" width="20" height="20" alt="已送出，觀看作品" ></a>';
						}
						
					}
							
					echo "</td>";
					echo "</tr>";
					$num_uploaded2 = $row_p2s["p_uploaded"]; //已繳交人數
					$num_uploaded += $row_p2s["p_uploaded"]; //已繳交人數
					$num_pa += $num_pa_ox; //已互評人數
					$num_sa += $num_sa_ox; //已自評人數
					$num_all += 1;
				}
				echo "<tr>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td>".$num_uploaded."/".$num_all."</td><td></td>";
				echo "<td>".$num_pa."/".$num_all."</td><td></td><td></td>";
				echo "<td>".$num_sa."/".$num_all."</td>";
				echo "</tr>";


		
?>
        </table>

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