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


if(isset($_GET["grade"])){
	$m_grade = $_GET["grade"];
}
else{
	//取m_grade（指定顯示的年級）
	$sql = "SELECT Distinct m_grade FROM mission WHERE t_id='".$_SESSION["t_id"]."' AND syear='".$syear."' ORDER BY m_grade DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗");
	$row = mysql_fetch_assoc($result);
	$m_grade = $row["m_grade"];
}

$_SESSION["m_grade"] = $m_grade;

?>
<html>
<head>
<title>任務狀態總覽</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="style2.css" rel="stylesheet" type="text/css">
</head>
<body>
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
		[<a href="index.php">回首頁</a>]
		[<a href="logout.php">登出系統</a>]
		</td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title"><?php echo $m_grade;?>年級進度總覽</td>
		<td class="function">
		<?php
			//取m_grade（指定顯示的年級）
			$sql = "SELECT Distinct m_grade FROM mission WHERE t_id='".$_SESSION["t_id"]."' AND syear='".$syear."' ORDER BY m_grade DESC";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗");
			while($row = mysql_fetch_assoc($result)){
				echo "<a href='progressall.php?grade=".$row["m_grade"]."'>".$row["m_grade"]."年級</a>　";
			}
		?>
		</td>
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
        <table width="0" cellpadding="5" class="outtable">
<?php
		echo "<tr><td></td><td></td><td colspan='3'>(<b><font color='red'>未繳交</font>/<font color='#FA00F7'>未評審</font>/總人數</b>)</td></tr>";
		echo "<tr><th>任務名稱</th><th>班級名稱</th><th>繳交</th><th>互評</th><th>自評</th><th>狀態</th></tr>";
		
		//以t_id，從mission取任務清單，m_id, m_name
		$sql = "SELECT m_id,m_name,m_order FROM mission WHERE t_id='".$_SESSION["t_id"]."' AND syear='".$syear."' AND m_grade='".$m_grade."' ORDER BY m_order DESC";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗");
		while($row = mysql_fetch_assoc($result)){
			//以m_id，從m2c取班級序號c_id
			$sql_c = "SELECT c_id FROM m2c WHERE m_id='".$row["m_id"]."' ORDER BY c_id";
			$result_c = mysql_query($sql_c,$pa);
			if(!$result_c)die("執行SQL命令失敗_c");

			$r=0;
			while($row_c = mysql_fetch_assoc($result_c)){
				echo "<tr>";
				if(mysql_num_rows($result_c)==1){
					echo "<td class='td-solid'>".$row["m_name"]."</td>";
				}
				else{
					if($r==0){
						echo "<td class='td-solid' rowspan='".mysql_num_rows($result_c)."'>".$row["m_name"]."</td>";
					}
					$r+=1;
					if($r==(mysql_num_rows($result_c))){
						$r=0;
					}
				}
				//取出班級名稱
				$sql_cn = "SELECT c_class FROM class WHERE c_id='".$row_c["c_id"]."'";
				$result_cn = mysql_query($sql_cn,$pa);
				if(!$result_cn)die("執行SQL命令失敗_cn");
				$row_cn = mysql_fetch_assoc($result_cn);
				echo "<td class='td-solid'>".$row_cn["c_class"]."</br><font color='#";
				
				//取出任務狀態
				$sql_m2c = "SELECT m2c_status FROM m2c WHERE c_id='".$row_c["c_id"]."' AND m_id='".$row["m_id"]."'";
				$result_m2c = mysql_query($sql_m2c,$pa);
				if(!$result_m2c)die("執行SQL命令失敗_m2c");
				$row_m2c = mysql_fetch_assoc($result_m2c);
				switch ($row_m2c["m2c_status"]){
					case "1":
					echo "bf4040'>(開放繳交";
					break;

					case "2":
					echo "7fbf40'>(開放互評";
					break;

					case "3":
					echo "407f00'>(開放自評";
					break;
					
					case "4":
					echo "7f7f7f'>(任務終止";
					break;

					default:
					echo "DC0300'>(尚未設定";
				}
				echo ")</font></td>";
				
				//以c_id，從stu取s_id
				$sql_si = "SELECT s_id FROM stu WHERE c_id='".$row_c["c_id"]."'";
				$result_si = mysql_query($sql_si,$pa);
				if(!$result_si)die("執行SQL命令失敗_si");
				$num_all = 0;
				$num_uploaded = 0;
				$num_pa = 0;
				$num_sa = 0;
				$num_unta = 0;
				while($row_si = mysql_fetch_assoc($result_si)){
				
					//以m_id,s_id，從progress2stu取p_uploaded,p_pa,p_sa 進度數字、學生數(資料數)
					//$sql_p2s = "SELECT p_uploaded,p_pa,p_sa FROM progress2stu WHERE m_id='".$row["m_id"]."' AND s_id='".$row_si["s_id"]."'";
					$sql_p2s = "SELECT s_id FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$row["m_id"]."' AND w_status='2'";
					$result_p2s = mysql_query($sql_p2s,$pa);
					if(!$result_p2s)die("執行SQL命令失敗_p2s");
					$num_uploaded += mysql_num_rows($result_p2s); //已繳交人數
					
					$pg_fin = "";
					//判斷是否已完成互評
					$sql_pg = "SELECT pg_pas FROM pg WHERE s_id='".$row_si["s_id"]."' AND m_id ='".$row["m_id"]."'";
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
						$num_pa_ox = 1;
					}
					else{
						$num_pa_ox = 0;
					}
					$num_pa += $num_pa_ox; //已互評人數
					
					//判斷是否已完成自評、教師是否尚未評審
					$sql_wsta = "SELECT sa_status,t_status FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$row["m_id"]."' AND w_status='2'";
					$result_wsta = mysql_query($sql_wsta,$pa);
					if(!$result_wsta)die("執行SQL命令失敗_wsta");
					$row_wsta = mysql_fetch_assoc($result_wsta);
					$sa_status = $row_wsta["sa_status"];
					$t_status = $row_wsta["t_status"];
					if($sa_status=="2"){
						$num_sa_ox = 1;
					}
					else{
						$num_sa_ox = 0;
					}
					$num_sa += $num_sa_ox; //已自評人數
					if(mysql_num_rows($result_wsta)!=0){
						if($t_status!="2"){
							$num_unta_ox = 1;
						}
						else{
							$num_unta_ox = 0;
						}
					}
					else{
							$num_unta_ox = 0;
					}
					$num_unta += $num_unta_ox; //教師未評審人數
					$num_all += 1;
				}
				$sql_p2s = "SELECT s_id FROM works WHERE s_id='".$row_si["s_id"]."' AND m_id='".$row["m_id"]."' AND w_status='2'";
				$result_p2s = mysql_query($sql_p2s,$pa);
				if(!$result_p2s)die("執行SQL命令失敗_p2s");
				$row_p2s = mysql_fetch_assoc($result_p2s);
				$num_uploaded += $row_p2s["p_uploaded"]; //已繳交人數

				//如果沒有全班交齊，顯示紅色粗體
				if($num_all - $num_uploaded != 0){
					$uploaded = "<font color='red'><b>".($num_all - $num_uploaded)."</b></font>";
				}
				else{
					$uploaded = 0;
				}
				
				//如果有教師未評審，顯示紅色粗體
				if($num_unta==0){
					$unta = $num_unta;
				}
				else{
					$unta = "<font color='#FA00F7'><b>".$num_unta."</b></font>";
				}
				
				//如果沒有全班完成互評，顯示紅色粗體
				if($num_all - $num_pa != 0){
					$panum = "<font color='red'><b>".($num_all - $num_pa)."</b></font>";
				}
				else{
					$panum = 0;
				}
				
				//如果沒有全班完成自評，顯示紅色粗體
				if($num_all - $num_sa != 0){
					$sanum = "<font color='red'><b>".($num_all - $num_sa)."</b></font>";
				}
				else{
					$sanum = 0;
				}
				
				echo "<td class='td-solid'>".$uploaded."/".$unta."/".$num_all."</td>";
				echo "<td class='td-solid'>".$panum."/".$num_all."</td>";
				echo "<td class='td-solid'>".$sanum."/".$num_all."</td>";
				echo "<td class='td-solid'><input type=\"button\" value=\"瀏覽&評分\" onClick=\"parent.location='pclass.php?cid=".$row_c["c_id"]."&mid=".$row["m_id"]."&cname=".$row_cn["c_class"]."&mname=".$row["m_name"]."&grade=".$m_grade."'\">";
				echo "<input type=\"button\" value=\"開放繳交\" onClick=\"parent.location='m2cstatus.php?cid=".$row_c["c_id"]."&mid=".$row["m_id"]."&status=1&cname=".$row_cn["c_class"]."&mname=".$row["m_name"]."&grade=".$m_grade."'\">";
				echo "<input type=\"button\" value=\"開放互評\" onClick=\"parent.location='m2cstatus.php?cid=".$row_c["c_id"]."&mid=".$row["m_id"]."&status=2&cname=".$row_cn["c_class"]."&mname=".$row["m_name"]."&grade=".$m_grade."'\">";
				echo "<input type=\"button\" value=\"開放自評\" onClick=\"parent.location='m2cstatus.php?cid=".$row_c["c_id"]."&mid=".$row["m_id"]."&status=3&cname=".$row_cn["c_class"]."&mname=".$row["m_name"]."&grade=".$m_grade."'\">";
				echo "<input type=\"button\" value=\"凍結任務\" onClick=\"parent.location='m2cstatus.php?cid=".$row_c["c_id"]."&mid=".$row["m_id"]."&status=4&cname=".$row_cn["c_class"]."&mname=".$row["m_name"]."&grade=".$m_grade."'\">";
				echo "</td></tr>";
			}
		}
		
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