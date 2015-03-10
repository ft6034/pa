<?php
$m_id = $_GET["mid"];
if(isset($_GET["wnum"])){
	$wnum = $_GET["wnum"];
}
else{
	$wnum = 0;
}

require_once("../Connections/pasql.php");
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	

//設定作品為[已完成]
if (isset($_GET["wsta"]) && $_GET["wsta"]==2){
	$sql = "UPDATE works SET w_status='1' WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗wsta2");
	$sql = "UPDATE works SET w_status='2' WHERE w_id='".$_GET["wid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗wsta2-done");
	echo '<script>parent.location.href="stu.php";</script>';
}

//設定為[製作中]
if (isset($_GET["wsta"]) && $_GET["wsta"]==1){
	$sql = "UPDATE works SET w_status='1' WHERE w_id='".$_GET["wid"]."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗wsta1");
}

$sql = "SELECT * FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' ORDER BY w_id DESC";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");

$maxi = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)){
	$w_date = $row["w_date"];
	$w_id[] = $row["w_id"];
	$w_name[] = $row["w_name"];
	$w_desc[] = $row["w_desc"];
	$w_datea[] = $row["w_date"];
	$w_status[] = $row["w_status"];
	$time[] = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	
	if(isset($w_datea[$wnum])&&$w_datea[$wnum]==$row["w_date"]){
		$showtime = substr($w_date,0,4).".".substr($w_date,5,2).".".substr($w_date,8,2)."-".substr($w_date,11,2).".".substr($w_date,14,2).".".substr($w_date,17,2);
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../style2.css" rel="stylesheet" type="text/css">
<!--shadowbox-->

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

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

input.groovybutton-blue-18pt
{
   font-size:18px;
   font-family:Arial,sans-serif;
   font-weight:bold;
   color:#FA8000;
   height:32px;
   background-color:#FFE7CD;
   border-style:double;
   border-color:#FA8000;
   border-width:4px;
}

</style>

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

<title>Scratch作品展示</title>
</head>

<body">
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
		<td class="title"><?php echo $w_name[$wnum];?>
		</td>
		<td class="function">
		<font color="red"><?php if(isset($_GET["aresult"])){echo $_GET["aresult"];}?></font>
		<input type="button" value="回首頁" onClick="self.parent.location='stu.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
		</td>
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
<table>
<tr>
    	<td>

<?php
			//判斷檔案類型，展示作品
			if (substr($w_desc[$wnum],-3) == ".sb"){
				echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="'.$w_desc[$wnum].'">
					</applet>
					<!-- Scratch project END-->
				';
				echo "<p align=\"center\"><a href=".$w_desc[$wnum]." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
			elseif (substr($w_desc[$wnum],-3) == "jpg"||substr($w_desc[$wnum],-3) == "png"||substr($w_desc[$wnum],-3) == "bmp"||substr($w_desc[$wnum],-3) == "gif"){
				echo "<a href=".$w_desc[$wnum]." rel=\"shadowbox\" target=\"_top\"><img src=".$w_desc[$wnum]." height=\"387\"></a>";
				echo "<p align=\"center\"><a href=".$w_desc[$wnum]." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}
			elseif (substr($w_desc[$wnum],-4) == ".mp4"||"webm"||".ogg"){
				echo '
					<video width="600" controls>
					<source src="'.$w_desc[$wnum].'" type="video/'.str_replace('.','',strrchr($w_desc[$wnum], ".")).'">
					Your browser does not support the video tag.
					</video>	
				';
			}
			else {
				echo "<p align=\"center\"><a href=".$w_desc[$wnum]." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
			}

			$finished = 0;
			//判斷是否以進入互評階段 (關閉刪除鈕)
			$sql_2 = "SELECT m2c.m2c_status FROM stu,m2c WHERE stu.s_id='".$_COOKIE['s_id']."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
			$result_2 = mysql_query($sql_2,$pa);
			if(!$result_2)die("執行SQL命令失敗2");
			$row_2 = mysql_fetch_assoc($result_2);
			if($row_2["m2c_status"]=="1"){ //開放繳交
				if($w_status[$wnum]=="2"){ //作品狀態：已完成
					//設定作品為[製作中]
					echo "<p align=\"center\"><font color='blue'>目前狀態：<b>已完成</b> </font>
					<input type=\"button\" value=\"改為製作中\" onClick=\"self.location='show.php?mid=".$m_id."&wnum=".$wnum."&wid=".$w_id[$wnum]."&wsta=1'\"></p>";
					$finished = 1;
				}
				elseif($w_status[$wnum]=="3"){ //作品狀態：被退件
					echo '<font color="red"><img src="../img/XB.png" width="12" height="12" alt="被退件" > 被退件</font> -> <a href="smessage.php">瀏覽退件原因。</a>';
				}
				else{
					//設定作品為[已完成]
					echo "<p align=\"center\"><font color='blue'>目前狀態：<b>製作中</b> </font> 
					<input 
						type=\"button\" 
						name=\"finished1\" 
						value=\"改為已完成\" 
						class=\"groovybutton-red-18pt\" 
						onMouseOver=\"goLite(this.form.name,this.name)\" 
						onMouseOut=\"goDim(this.form.name,this.name)\" 
						onClick=\"self.location='show.php?mid=".$m_id."&wnum=".$wnum."&wid=".$w_id[$wnum]."&wsta=2'\" >";
					
					echo "<a href=\"upload.php?mid=".$m_id."\" rel=\"shadowbox\">";
					echo '<input
						type="button"
						name="groovybtn4-'.$m_id.'"
						class="groovybutton-blue-18pt"
						value="重交作品"
						title=""
						onMouseOver="goLite2(this.form.name,this.name)"
						onMouseOut="goDim2(this.form.name,this.name)" ></a></p>
						';
				}
			}
			if($row_2["m2c_status"]>1){
				if($w_status[$wnum]!=2){ //作品狀態：非已完成
					if($w_status[$wnum]=="3"){ //作品狀態：被退件
						echo '<font color="red"><img src="../img/XB.png" width="12" height="12" alt="被退件" > 被退件</font> -> <a href="smessage.php">瀏覽退件原因。</a>';
					}
					else{
						//設定作品為[已完成]
						echo "<p align=\"center\"><font color='blue'>目前狀態：<b>製作中</b> </font>
						<input 
							type=\"button\" 
							name=\"finished1\" 
							value=\"改為已完成\" 
							class=\"groovybutton-red-18pt\" 
							onMouseOver=\"goLite(this.form.name,this.name)\" 
							onMouseOut=\"goDim(this.form.name,this.name)\" 
							onClick=\"self.location='show.php?mid=".$m_id."&wnum=".$wnum."&wid=".$w_id[$wnum]."&wsta=2'\" >";
					}
					echo "<a href=\"upload.php?mid=".$m_id."\" rel=\"shadowbox\">";
					echo '<input
						type="button"
						name="groovybtn4-'.$m_id.'"
						class="groovybutton-blue-18pt"
						value="重交作品"
						title=""
						onMouseOver="goLite2(this.form.name,this.name)"
						onMouseOut="goDim2(this.form.name,this.name)" ></a></p>
						';
				}
			}
?>
		  
		</td>
		<td align="left">
<?php
		if($w_status[$wnum]==2){
			echo '<p><b><font color="red">此為正式繳交作品</font></b></p>';
			$finished = 1;
		}
?>
			<p>作者：<?php echo $_COOKIE["s_id"];?></p>
			<p>上傳時間：<?php echo $w_datea[$wnum];?></p><br><br>
			<p>作品列表：<br>
<?php
/*
//列出修正後作品
$sql_rew = "SELECT * FROM rework WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."'";
$result_rew = mysql_query($sql_rew,$pa);
if(!$result_rew)die("執行SQL命令失敗_rew");
$row_rew = mysql_fetch_assoc($result_rew);
if(mysql_num_rows($result_rew)!=0){
	echo "</br>";
	echo "<a href=showre.php?mid=".$row_rew["m_id"].">".$row_rew["rew_date"]."</a> <font color='blue'><b>修正後作品</b></font>";
	echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delrework.php?wid=".$row_rew["rew_id"]."&mid=".$row_rew["m_id"]."'\"  style=\"font-size: 8 pt; border-style: ridge; border-width:2 \"><br>";
}
*/
for($i=0;$i<$maxi;$i++){
	echo "<p>";
	if($i==$wnum){
		echo "<b>".$w_datea[$i]."</b>";
	}
	else{
		echo "<a href=\"show.php?mid=".$m_id."&wnum=".$i."\">".$w_datea[$i]."</a>";
	}
	
	//判斷是否以進入互評階段 (關閉刪除鈕)
	$sql_2 = "SELECT m2c.m2c_status FROM stu,m2c WHERE stu.s_id='".$_COOKIE['s_id']."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
	$result_2 = mysql_query($sql_2,$pa);
	if(!$result_2)die("執行SQL命令失敗2");
	$row_2 = mysql_fetch_assoc($result_2);
	if($row_2["m2c_status"]=="1"){
		echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delwork.php?wid=".$w_id[$i]."&mid=".$m_id."&wnum=".$i."'\"  style=\"font-size: 8 pt; border-style: ridge; border-width:2 \"><br>";
	}
	else{
		if($i!=0){
			echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delwork.php?wid=".$w_id[$i]."&mid=".$m_id."&wnum=".$i."'\"  style=\"font-size: 8 pt; border-style: ridge; border-width:2 \"><br>";
		}
		else{
			echo " <font color='red'><b>正式作品(不可刪除)</b></font>";
			//檢查互評階段是否已完成(進入自評或任務結束)
			if($row_2["m2c_status"]>2){
				echo "<p><a href='paresult.php?wid=".$w_id[$i]."&mid=".$m_id."' rel='shadowbox'>";
				echo '<input 
							type="button"
							name="groovybtn4-'.$w_id[$i].'"
							class="groovybutton-purple"
							value="瀏覽互評結果"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a></p>';
			}
		}
	}
	//echo "　<input type=\"button\" value=\"刪除\" onClick=\"self.location='delwork.php?mid=".$m_id."&wnum=".$i."'\"  data-inline=\"true\" data-icon=\"delete\"><br>"; //實驗中,有X圖示的按鈕
	echo "</p>";
}


//判斷作品繳交狀況
$w_status = 0;			
$sql_wsta = "SELECT t_status,w_status,w_id,sa_status FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' ORDER BY w_id DESC";
$result_wsta = mysql_query($sql_wsta,$pa);
if(!$result_wsta)die("執行SQL命令失敗_wsta");
$row_wsta = mysql_fetch_assoc($result_wsta);
$w_status = $row_wsta["w_status"];
$t_status = $row_wsta["t_status"];
$sa_status = $row_wsta["sa_status"];
/*
//作品狀態(製作中)
if($w_status=="1"){
	echo "<a href=\"upload.php?mid=".$m_id."\" rel=\"shadowbox\">";
	echo '<input
			type="button"
			name="groovybtn4-'.$m_id.'"
			class="groovybutton-blue"
			value="重交作品"
			title=""
			onMouseOver="goLite2(this.form.name,this.name)"
			onMouseOut="goDim2(this.form.name,this.name)" ></a>
			';
}
*/

//開放自評-> 開放繳交修正作品
// 判斷是否開放互評
$sql_m2c = "SELECT m2c_status FROM m2c WHERE m_id='".$m_id."' AND c_id='".$_GET["cid"]."'";
$result_m2c = mysql_query($sql_m2c,$pa);
if(!$result_m2c)die("執行SQL命令失敗_m2c1");
$row_m2c = mysql_fetch_assoc($result_m2c);
if(@$row_m2c["m2c_status"]>2&&$w_status=="2"){
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
?>



			</p>
      </td>
	</tr>
</table>
</form>
<hr>

<?php
//判斷是否已完成
if($finished==1){

if(isset($_GET["cid"])){
//是否進入互評階段
$sql_m2c = "SELECT m2c_status FROM m2c WHERE m_id='".$m_id."' AND c_id='".$_GET["cid"]."'";
$result_m2c = mysql_query($sql_m2c,$pa);
if(!$result_m2c)die("執行SQL命令失敗_m2c");
$row_m2c = mysql_fetch_assoc($result_m2c);
if($row_m2c["m2c_status"]>2){

	echo "<table width='800'>";
	//取出互評評審者s_id
	$sql_pg = "SELECT s_id,pg_member FROM pg WHERE m_id='".$m_id."' AND c_id='".$_GET["cid"]."'";
	$result_pg = mysql_query($sql_pg,$pa);
	if(!$result_pg)die("執行SQL命令失敗_pg");

	while($row_pg = mysql_fetch_assoc($result_pg)){
		$all_pgid = explode("-",$row_pg["pg_member"]);
		for($x=0;$x<count($all_pgid);$x++){
			if($all_pgid[$x] == $_COOKIE['s_id']){
				$all_pgsids[] = $row_pg["s_id"];
			}
		}
	}
	$all_unipgsids = array_unique($all_pgsids); //評審者s_id

		//檢查是否已有互評項目,有的話就顯示
		$sql_s = "SELECT sca_id,m_id,sca_directions,sca_n,sca_order,sca_word FROM scale WHERE m_id='".$m_id."' ORDER BY sca_order";
		$result_sn = mysql_query($sql_s,$pa);
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		$row_sn = mysql_fetch_assoc($result_sn);
		
		$sql_t = "SELECT txt_id,m_id,txt_directions,txt_order FROM text WHERE m_id='".$m_id."' ORDER BY txt_order";
		$result_t = mysql_query($sql_t,$pa);
		if(!$result_t)die("執行SQL命令失敗_t");
		
		//已有互評項目
		if(mysql_num_rows($result_s)!=0 || mysql_num_rows($result_t)!=0){
			
			//考慮刪掉
			echo '		
				<input type="hidden" name="m_id" value="'.$m_id.'">
				<input type="hidden" name="scanum" value="'.mysql_num_rows($result_s).'">
				<input type="hidden" name="txtnum" value="'.mysql_num_rows($result_t).'">
				<input type="hidden" name="pg_sid" value="'.@$alls_id[$s_num].'">
				';
			
			echo '<tr><th>單選題</th></tr>';

			$i=0;
			while($row_s = mysql_fetch_assoc($result_s)){
				echo '<input type="hidden" name="sca_id'.$i.'" value="'.$row_s["sca_id"].'">'; //單選題序號
				echo "<tr>";
				//echo	"<td> ".($i+1)."</td>";
				//echo	"<td>".$row_s["sca_directions"]."</td>";
				echo '<td>
				<div data-role="fieldcontain">
        			<fieldset data-role="controlgroup" data-type="horizontal">
          			<legend><b><font size="3">'.$row_s["sca_directions"].'</font></b></legend>';
				echo '<table><tr><td width="500"><p>同儕評分結果：<font color="green"><b>';
				//取出評分輔助詞
				$allra_word = explode("-",$row_s["sca_word"]);
					
				for($x=0;$x<count($all_unipgsids);$x++){
					$all_unipgsids[$x];
					//取出同儕評分資料
					$sql_scarid = "SELECT sca_reply FROM scaler WHERE s_id='".$all_unipgsids[$x]."' AND m_id='".$m_id."' AND pg_sid='".$_COOKIE['s_id']."' AND sca_id='".$row_s["sca_id"]."'";
					$result_scarid = mysql_query($sql_scarid,$pa);
					if(!$result_scarid)die("執行SQL命令失敗_scarid");
					$row_scarid = mysql_fetch_assoc($result_scarid);
					for($k=0;$k<$row_s["sca_n"];$k++){
						if($row_scarid["sca_reply"]==($k+1)){
							echo $allra_word[$k]."　";
						}
					}
          		}
				echo '</b></font></p></td><td>教師評分結果：<font color="blue"><b>';
				//取出教師評分資料
				$sql_ta = "SELECT sca_reply FROM scaletr WHERE pg_sid='".$_COOKIE['s_id']."' AND sca_id='".$row_s["sca_id"]."'";
				$result_ta = mysql_query($sql_ta,$pa);
				if(!$result_ta)die("執行SQL命令失敗_ta");
				$row_ta = mysql_fetch_assoc($result_ta);
				for($k=0;$k<$row_s["sca_n"];$k++){
					if($row_ta["sca_reply"]==($k+1)){
						echo $allra_word[$k]."　";
					}
				}
				//echo $row_ta["sca_reply"];
				echo '</b></font></td></tr></table>';
				echo '	
        			</fieldset>
				</div></br></td>';
				
				echo	"</tr>";
				$i++;
			}
		
			echo '<tr><td width="100%">　</td></tr>';
			echo '<tr><th>文字回饋</th></tr>';

			$j=0;
			while($row_t = mysql_fetch_assoc($result_t)){
				echo '<input type="hidden" name="txt_id'.$j.'" value="'.$row_t["txt_id"].'">'; //文字題序號
				echo "<tr><td><b><font size='3'>".$row_t["txt_directions"]."</font></b></td></tr>";
				echo '<tr><td>　</td></tr>';
				for($x=0;$x<count($all_unipgsids);$x++){
					//取出同儕文字回饋
					$sql_txtrid = "SELECT txt_rid,txt_reply FROM textr WHERE s_id='".$all_unipgsids[$x]."' AND m_id='".$m_id."' AND pg_sid='".$_COOKIE['s_id']."' AND txt_id='".$row_t["txt_id"]."'";
					$result_txtrid = mysql_query($sql_txtrid,$pa);
					if(!$result_txtrid)die("執行SQL命令失敗_txtrid");
					$txtreply = "";
					$row_txtrid = mysql_fetch_assoc($result_txtrid);
					$txtreply = $row_txtrid ["txt_reply"];
					
					echo "<tr>
							<td><p>";
					
					if($txtreply!=""){
					
						$sql_parstat = "SELECT par_stat FROM pareport WHERE txt_rid='".$row_txtrid ["txt_rid"]."'";
						$result_parstat = mysql_query($sql_parstat,$pa);
						if(!$result_parstat)die("執行SQL命令失敗_parstat");
						$row_parstat = mysql_fetch_assoc($result_parstat);

						if(empty($row_parstat["par_stat"])){
							echo "
							<a href='pareport.php?s=good&sid=".$all_unipgsids[$x]."&pgsid=".$_COOKIE['s_id']."&mid=".$m_id."&txtrid=".$row_txtrid ["txt_rid"]."&reply=".$txtreply."'><input type='button' value='讚！'></a>
							<a href='pareport.php?s=appeal&sid=".$all_unipgsids[$x]."&pgsid=".$_COOKIE['s_id']."&mid=".$m_id."&txtrid=".$row_txtrid ["txt_rid"]."&reply=".$txtreply."'><input type='button' value='申訴'></a>";
						}
						elseif($row_parstat["par_stat"]=="g"){
							echo "[已給好評]";
						}
						elseif($row_parstat["par_stat"]=="b"){
							echo "[已提申訴]";
						}
						echo "<font color='green'><b>　　".$txtreply."</b></font></p></td></tr>";
					}
					else{
						echo "同儕".($j+1)."尚未評審";
					}

					$j++;
				}
				echo '<tr><td>　</td></tr>';
				
					//取出教師文字回饋
					$sql_txtrid = "SELECT txt_reply FROM texttr WHERE pg_sid='".$_COOKIE['s_id']."' AND txt_id='".$row_t["txt_id"]."'";
					$result_txtrid = mysql_query($sql_txtrid,$pa);
					if(!$result_txtrid)die("執行SQL命令失敗_txtrid");
					$txtreply = "";
					while($row_txtrid = mysql_fetch_assoc($result_txtrid)){
						$txtreply = $row_txtrid ["txt_reply"];
					}
					
					echo"<tr><td><p><font color='blue'><b>　　".$txtreply."</b></font></p></td></tr>";

			}
			echo '<tr><td>　';
			echo '</td></tr>';
			echo '<tr><td>　';
			echo '</td></tr>';

		}
	echo "</table>";
}
}
}
?>


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