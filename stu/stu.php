<?php
session_start();
?>
<html>
<head>
<?php
if (empty($_COOKIE['s_id']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}
//開啟資料庫
require_once("../Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
//取目前的學年學期
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");
$now_time2 = date("H:i:s");
	
//以s_id，從stu,class取得 s_name, c_id, c_class
$sql = "SELECT stu.s_name, stu.c_id, class.c_class FROM stu, class WHERE stu.s_id='".$_COOKIE['s_id']."' AND stu.c_id=class.c_id";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="../style2.css" rel="stylesheet" type="text/css">

<script language="javascript">

function goLite(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCE8CC";
}

function goDim(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCDDCC";
}

</script>

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
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "transparent";
   window.document.forms[FRM].elements[BTN].style.borderColor = "#FF0000";
}

function goLite2(FRM2,BTN2)
{
   window.document.forms[FRM2].elements[BTN2].style.color = "#FFFFFF";
   window.document.forms[FRM2].elements[BTN2].style.backgroundColor = "#0000FF";
   window.document.forms[FRM2].elements[BTN2].style.borderColor = "#FA8000";
}

function goDim2(FRM2,BTN2)
{
   window.document.forms[FRM2].elements[BTN2].style.color = "#0000FF";
   window.document.forms[FRM2].elements[BTN2].style.backgroundColor = "transparent";
   window.document.forms[FRM2].elements[BTN2].style.borderColor = "#0000FF";
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

function goLite0(FRM0,BTN0)
{
   window.document.forms[FRM0].elements[BTN0].style.color = "#CC5533";
   window.document.forms[FRM0].elements[BTN0].style.borderTopColor = "#666666";
   window.document.forms[FRM0].elements[BTN0].style.borderBottomColor = "#666666";
}

function goDim0(FRM0,BTN0)
{
   window.document.forms[FRM0].elements[BTN0].style.color = "#777777";
   window.document.forms[FRM0].elements[BTN0].style.borderTopColor = "#AAAAAA";
   window.document.forms[FRM0].elements[BTN0].style.borderBottomColor = "#AAAAAA";
}

</script>

<!--shadowbox-->

<!--<link rel="shortcut icon" href="http://www.ftstour.com.tw/FTSMVC/favicon.ico" type="image/x-icon" />-->

<script src="../sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="../sb303/shadowbox.css" />

<script type="text/javascript" src="../sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->

<title>學生首頁<?php echo $now_date." ".$now_time2;?></title>
</head>
<body bgcolor="white">

<center>
<table border="0" cellpadding="0" cellspacing="0">

<tr>
	<td background="../img/bg-lt.png" width="60" height="60"></td>
	<td background="../img/bg-top.png" width="800" height="60">
	</td>
	<td background="../img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 -->
	<table class="outtable">
<?php
if(isset($_SESSION["t_id"])){
	echo '
	<tr>
		<td colspan="2" class="menu">
		[<a href="../switchstu.php">切換學生</a>] [<a href="../logoutstu.php">切換回教師身份</a>]
		</td>
	</tr>
	';
}
?>
	
	<tr>
		<td colspan="2" class="header">網路同儕互評系統</td>
	</tr>
	<tr>
		<td class="title"><font size="4">
<?php 
	echo $row["c_class"]." ".$row["s_name"]." ";
			//取未讀訊息
			$sql = "SELECT ms_id FROM messages WHERE receiver='".$_COOKIE["s_id"]."' AND ms_read='0'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗1");
			$m_nums = mysql_num_rows($result);
			if($m_nums!=0){$m_nums = "(<font color='red'>".$m_nums."</font>)";}else{$m_nums="";}
?>
		<font size="2">[<a href="smessage.php"> 訊息<?php echo $m_nums;?> </a>] [<a href="../index.php"> 首頁 </a>] [<a href="../../scratch/" target="_blank">全班作品一覽</a>] [<a href="delog.php" rel="shadowbox"> 回收桶 </a>] [<a href="chpass.php" rel="shadowbox"> 修改密碼 </a>] [<a href="../logout.php"> 登出 </a>]</font>
		</td>
		<td width="200" align="right">
<?php
			//如果是教師模擬學生，就出現年級選單
			if(substr($row["c_class"],0,2)=="t_"){
				//取m_grade（指定顯示的年級）
				$sql_grade = "SELECT Distinct m_grade FROM mission WHERE t_id='".$_SESSION["t_id"]."' AND syear='".$syear."' ORDER BY m_grade DESC";
				$result_grade = mysql_query($sql_grade,$pa);
				if(!$result_grade)die("執行SQL命令失敗");
				while($row_grade = mysql_fetch_assoc($result_grade)){
					if(isset($_GET["grade"])&&$_GET["grade"]==$row_grade["m_grade"]){
						echo "<b>";
					}
					echo "<a href='stu.php?grade=".$row_grade["m_grade"]."'>".$row_grade["m_grade"]."年級</a>　";
					if(isset($_GET["grade"])&&$_GET["grade"]==$row_grade["m_grade"]){
						echo "</b>";
					}
				}
			}
			else{
				
			}
			
?>
		</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">
	
	<!-- 內容區 -->
	<?php


	?>
	
<div align="center">
      <form action="assign.php" method="post" enctype="multipart/form-data" name="groovyform">
        <table width="0" cellpadding="5" class="outtable2">
			<tr><th height="20" width="110">任務名稱</th><th>任務說明</th><th>作品進度</th><th width="80">互評與自評</th></tr>
<?php		
	//如果是教師模擬學生，且指定年級時，依年級單獨顯示
	if(isset($_GET["grade"])){
		$sql_m = "SELECT m2c.m_id,mission.m_order FROM m2c,mission WHERE m2c.c_id='".$row["c_id"]."' AND mission.m_id=m2c.m_id AND mission.syear=".$syear." AND mission.m_grade=".$_GET["grade"]." ORDER BY mission.m_order DESC";
	}
	else{
		$sql_m = "SELECT m2c.m_id,mission.m_order FROM m2c,mission WHERE m2c.c_id='".$row["c_id"]."' AND mission.m_id=m2c.m_id AND mission.syear=".$syear." ORDER BY mission.m_order DESC";
	}
	//以c_id，從m2c取得 m_id
	
	$result_m = mysql_query($sql_m,$pa);
	if(!$result_m)die("執行SQL命令失敗_m");
	while($row_m = mysql_fetch_assoc($result_m)){
	
		//以m_id，從mission取得 m_name, m_desc
		$sql_m2 = "SELECT m_name, m_desc, m_status,m_spath FROM mission WHERE m_id = '".$row_m["m_id"]."'";
		$result_m2 = mysql_query($sql_m2,$pa);
		if(!$result_m2)die("執行SQL命令失敗_m2");
		if(mysql_num_rows($result_m2)!=0) {
			while ($row_m2 = mysql_fetch_assoc($result_m2)){				
				
				echo "<tr><td align=\"center\" class='td-solid'><p><br><font size=\"3pt\"><b>".$row_m2["m_name"]."</b></font></p>";
				
				//判斷檔案類型
				if (substr($row_m2["m_spath"],-3) == ".sb"){
					echo "<a href=\"../sample.php?mid=".$row_m["m_id"]."\" rel=\"shadowbox\">";
				}
				elseif (substr($row_m2["m_spath"],-3) == "xls"){
					echo "<a href=\"../".$row_m2["m_spath"]."\"  target='_blank'>";
				}
				elseif (substr($row_m2["m_spath"],-4) == "docx"){
					echo "<a href=\"../".$row_m2["m_spath"]."\" target='_blank'>";
				}
				elseif (substr($row_m2["m_spath"],-4) == ".mp4"||substr($row_m2["m_spath"],-4) == "webm"||substr($row_m2["m_spath"],-4) == ".ogg"){
					echo "<a href=\"../video.php?mid=".$row_m["m_id"]."\" rel=\"shadowbox\">";
				}
				else {
					echo "<a href=\"../".$row_m2["m_spath"]."\" rel=\"shadowbox\">";
				}
				
				echo "
				<input
					type=\"button\" 
					name=\"groovybtn0-".$row_m["m_id"]."\" 
					class=\"groovybutton-0\" 
					value=\"範例\" 
					title=\"\" 
					onMouseOver=\"goLite0(this.form.name,this.name)\" 
					onMouseOut=\"goDim0(this.form.name,this.name)\" >	</a>				
				</td>
				<td class='td-solid'><p align=left><font size=\"3pt\" color=\"#89174F\">".$row_m2["m_desc"]."</font></p></td><td class='td-solid'>";	
				
				echo '<b>';
				//判斷作品繳交狀況
				$w_status = 0;			
				$sql_wsta = "SELECT t_status,w_status,w_id,sa_status FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$row_m["m_id"]."' ORDER BY w_id DESC";
				$result_wsta = mysql_query($sql_wsta,$pa);
				if(!$result_wsta)die("執行SQL命令失敗_wsta");
				$row_wsta = mysql_fetch_assoc($result_wsta);
				$w_status = $row_wsta["w_status"];
				$t_status = $row_wsta["t_status"];
				$sa_status = $row_wsta["sa_status"];				


				
				
				//檢查任務進度
				//以m_id, s_id，從progress2stu取得任務進度 p_uploaded, p_pa, p_sa
				$sql_p2s = "SELECT p_uploaded, p_pa, p_sa FROM progress2stu WHERE s_id='".$_COOKIE['s_id']."' AND m_id ='".$row_m["m_id"]."'";
				$result_p2s = mysql_query($sql_p2s,$pa);
				if(!$result_p2s)die("執行SQL命令失敗_p2s");
				$row_p2s = mysql_fetch_assoc($result_p2s);
				//繳交 $row_p2s["p_uploaded"]
				//互評 $row_p2s["p_pa"]
				//自評 $row_p2s["p_sa"]
			
				//尚未繳交作品
				$m_id = $row_m["m_id"];
				if($row_p2s["p_uploaded"]==0){
					//作品狀態(尚未繳交)
					echo '<p><a href="upload.php?mid='.$row_m["m_id"].'" rel="shadowbox"><font color="red"><img src="../img/X.png" width="12" height="12" alt="尚未繳交" > 尚未繳交</font></p>';
					
				//echo '</b><br>';
					//echo "<input type=\"button\" value=\"繳交作品\" onClick=\"parent.location='upload.php?mid=".$m_id."'\">
					echo "<a href=\"upload.php?mid=".$m_id."\" rel=\"shadowbox\">";
					echo '<input
							type="button"
							name="groovybtn1-'.$m_id.'"
							class="groovybutton-red"
							value="繳交作品"
							title=""
							onMouseOver="goLite(this.form.name,this.name)"
							onMouseOut="goDim(this.form.name,this.name)" ></a><br>
					';
				}
				//已繳交作品(非 被退件&製作中)
				elseif($w_status!="3"&&$w_status!="1"){
					if($t_status=="2"){
						echo '<p align="left"><img src="../img/O.png" width="12" height="12" alt="已評審" > <a href="show.php?mid='.$m_id.'&cid='.$row["c_id"].'" rel="shadowbox">已評審</a></p>';
						
						//檢查是否已有互評項目,有的話就顯示
						$sql_t = "SELECT txt_id,m_id,txt_directions,txt_order FROM text WHERE m_id='".$m_id."' ORDER BY txt_order";
						$result_t = mysql_query($sql_t,$pa);
						if(!$result_t)die("執行SQL命令失敗_t");
						while($row_t = mysql_fetch_assoc($result_t)){
							//取出教師文字回饋
							$sql_txtrid = "SELECT txt_reply FROM texttr WHERE pg_sid='".$_COOKIE['s_id']."' AND txt_id='".$row_t["txt_id"]."'";
							$result_txtrid = mysql_query($sql_txtrid,$pa);
							if(!$result_txtrid)die("執行SQL命令失敗_txtrid");
							$txtreply = "";
							while($row_txtrid = mysql_fetch_assoc($result_txtrid)){
								$txtreply = $row_txtrid ["txt_reply"];
								echo "<p align='left'><font color='black'><b>".$txtreply."</b></font></p>";
							}
						}
					}
					else{
						echo '<p><img src="../img/OB.png" width="12" height="12" alt="完成繳交" > <a href="show.php?mid='.$m_id.'&cid='.$row["c_id"].'" rel="shadowbox">完成繳交</a></p>';
					}
					
					/*
					//前往瀏覽作品頁
					echo "<a href=\"show.php?mid=".$m_id."&cid=".$row["c_id"]."\" rel=\"shadowbox\">";
					echo '<input
							type="button"
							name="groovybtn2-'.$m_id.'"
							class="groovybutton-purple"
							value="瀏覽作品"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a><br>
							';
					*/
					
					//判斷是否已繳交修正後作品
					$sql_rew = "SELECT * FROM rework WHERE m_id='".$row_m["m_id"]."' AND s_id='".$_COOKIE['s_id']."'";
					$result_rew = mysql_query($sql_rew,$pa);
					if(!$result_rew)die("執行SQL命令失敗_rew");
					$row_rew = mysql_fetch_assoc($result_rew);
					if(mysql_num_rows($result_rew)!=0){
						if($row_rew["w_status"]==3){
							echo '<p><font color="red"><img src="../img/XB.png" width="12" height="12" alt="被退件" ><a href="showre.php?mid='.$m_id.'" rel="shadowbox"> 修正後作品被退件</font></a></p>';
						}
						elseif($row_rew["t_status"]==2){
							echo '<p align="left"><img src="../img/O.png" width="12" height="12" alt="修正作品OK" ><a href="showre.php?mid='.$m_id.'" rel="shadowbox"> 修正作品OK</a></p>';
						}
						else{
							echo '<p align="left"><img src="../img/OB.png" width="12" height="12" alt="已繳交修正後作品" ><a href="showre.php?mid='.$m_id.'" rel="shadowbox"> 已繳交修正後作品</a></p>';
						}
					}
					
					/*
					//開放自評-> 開放繳交修正作品
					// 判斷是否開放互評
					$sql_m2c = "SELECT m2c_status FROM m2c WHERE m_id='".$row_m["m_id"]."' AND c_id='".$row["c_id"]."'";
					$result_m2c = mysql_query($sql_m2c,$pa);
					if(!$result_m2c)die("執行SQL命令失敗_m2c1");
					$row_m2c = mysql_fetch_assoc($result_m2c);
					if(@$row_m2c["m2c_status"]>2&&$w_status=="2"){
						$sql_rew = "SELECT * FROM rework WHERE m_id='".$row_m["m_id"]."' AND s_id='".$_COOKIE['s_id']."'";
						$result_rew = mysql_query($sql_rew,$pa);
						if(!$result_rew)die("執行SQL命令失敗_rew");
						$row_rew = mysql_fetch_assoc($result_rew);
						if(mysql_num_rows($result_rew)==0){
							
							echo "<a href=\"upload_f.php?mid=".$m_id."\" rel=\"shadowbox\">";
							echo '<input
							type="button"
							name="groovybtn4-'.$m_id.'"
							class="groovybutton-blue"
							value="修正作品"
							title=""
							onMouseOver="goLite2(this.form.name,this.name)"
							onMouseOut="goDim2(this.form.name,this.name)" ></a>
							';
							
						}
						else{
							echo "<a href=\"showre.php?mid=".$m_id."\" rel=\"shadowbox\">";
							echo '<input
								type="button"
								name="groovybtn4-'.$m_id.'-re"
								class="groovybutton-blue"
								value="瀏覽修正後作品"
								title=""
								onMouseOver="goLite2(this.form.name,this.name)"
								onMouseOut="goDim2(this.form.name,this.name)" ></a><br>
								';
						}
					}
					*/

					
				}
				
				
				

				//作品狀態(2已完成)
				if($w_status=="2"){
					echo "</td><td class='td-solid'>";
					//echo '完成繳交<br>';
					
					// 判斷是否開放互評
					$sql_m2c = "SELECT m2c_status FROM m2c WHERE m_id='".$row_m["m_id"]."' AND c_id='".$row["c_id"]."'";
					$result_m2c = mysql_query($sql_m2c,$pa);
					if(!$result_m2c)die("執行SQL命令失敗_m2c");
					$row_m2c = mysql_fetch_assoc($result_m2c);
					if($row_m2c["m2c_status"]=="2"||$row_m2c["m2c_status"]=="3"){
						//判斷是否已完成互評
						$pg_fin="";
						$sql_pg = "SELECT pg_pas FROM pg WHERE s_id='".$_COOKIE['s_id']."' AND m_id ='".$row_m["m_id"]."'";
						$result_pg = mysql_query($sql_pg,$pa);
						if(!$result_pg)die("執行SQL命令失敗_pg");
						$row_pg = mysql_fetch_assoc($result_pg);
						$all_pg = explode("-",$row_pg["pg_pas"]);
						for($i=0;$i<count($all_pg);$i++){
							if($all_pg[$i]=="0"||$all_pg[$i]=="1"){
								$pg_fin="unfin";
							}						
						}
						//進入自評&完成
						if($row_m2c["m2c_status"]=="3"&&$pg_fin!="unfin"){
							
						}
						else{
							//完成
							if($pg_fin!="unfin"){
								echo '<p><img src="../img/O.png" width="12" height="12" alt="已完成互評" > 已完成互評</p>';
							}
							//未完成
							else{
								echo "<p><a href='pa.php?mid=".$row_m["m_id"]."' rel='shadowbox'><font color='red'><img src='../img/X.png' width='12' height='12' alt='尚未互評' > 尚未互評</font></a></p>";
							}
							/*echo '<input
								type="button"
								name="groovybtn3-'.$row_m["m_id"].'"
								class="groovybutton-green"
								value="進行互評"
								title=""
								onMouseOver="goLite3(this.form.name,this.name)"
								onMouseOut="goDim3(this.form.name,this.name)" 
								onClick="parent.location=\'pa.php?mid='.$row_m["m_id"].'\'"></br>';
							*/
						}
					}
					
					//判斷是否開放自評(截止互評)
					if($row_m2c["m2c_status"]=="3"){
						//判斷是否已完成自評
						if($sa_status=="2"){
							echo '<p><img src="../img/O.png" width="12" height="12" alt="已完成自評" > 完成自評</p>';
						}
						else{
							echo "<p><a href='sa.php?mid=".$row_m["m_id"]."' rel='shadowbox'><font color='red'><img src='../img/X.png' width='12' height='12' alt='尚未自評' > 尚未自評</font></a></p>";
						}
						/*echo '<input
							type="button"
							name="groovybtn3-'.$row_m["m_id"].'"
							class="groovybutton-green"
							value="進行自評"
							title=""
							onMouseOver="goLite3(this.form.name,this.name)"
							onMouseOut="goDim3(this.form.name,this.name)" 
							onClick="parent.location=\'sa.php?mid='.$row_m["m_id"].'\'"></br>';
						*/
					}

					
					if($row_m2c["m2c_status"]=="4"){
						echo "<p>任務結束</p>";
					}
							
				}
				//作品狀態(製作中)
				else if($w_status=="1"){
					echo '<p><a href="show.php?mid='.$row_m["m_id"].'&cid='.$row["c_id"].'" rel="shadowbox"><img src="../img/B.png" width="12" height="12" alt="製作中" > <font color="red">製作中</font></p>';
					
					
					echo "<a href=\"upload.php?mid=".$row_m["m_id"]."\" rel=\"shadowbox\">";
					echo '<input
						type="button"
						name="groovybtn4-'.$row_m["m_id"].'"
						class="groovybutton-blue"
						value="重交作品"
						title=""
						onMouseOver="goLite2(this.form.name,this.name)"
						onMouseOut="goDim2(this.form.name,this.name)" ></a>
						';
				}
				//作品狀態(3被退件)
				else if($w_status=="3"){
					echo '<p><a href="show.php?mid='.$row_m["m_id"].'&cid='.$row["c_id"].'" rel="shadowbox"><font color="red"><img src="../img/XB.png" width="12" height="12" alt="被退件" > 被退件</font></p>';
					
					
					echo "<a href=\"upload.php?mid=".$row_m["m_id"]."\" rel=\"shadowbox\">";
					echo '<input
						type="button"
						name="groovybtn4-'.$row_m["m_id"].'"
						class="groovybutton-blue"
						value="重交作品"
						title=""
						onMouseOver="goLite2(this.form.name,this.name)"
						onMouseOut="goDim2(this.form.name,this.name)" ></a>
						';
				}
				
				
				
				echo "</b>";
				echo "</td></tr>";
			}
			
		}
	}
echo "<tr><td>　</td></tr></table>";
?>
	

	
	
	
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