<html>
<head>
<?php
if(isset($_GET["mid"])){
	$m_id = $_GET["mid"]; //任務序號
}
else{
	$m_id = "";
}
if(isset($_GET["snum"])){
	$s_num = $_GET["snum"]; //評審者流水號
}
else{
	$s_num = "";
}


//建立資料連接
require_once('../Connections/pasql.php');
	
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");

$sql = "SELECT stu.s_name, stu.c_id, m2c.m2c_status FROM stu, m2c WHERE stu.s_id='".$_COOKIE['s_id']."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$c_id = $row["c_id"];

//判斷是否登入
if (empty($_COOKIE['s_id']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}
//判斷是否進入互評階段
elseif($row["m2c_status"]>1){


?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style2.css" rel="stylesheet" type="text/css">

<script language="javascript">
function switchpage(pagename){
	document.presult.action=pagename;
	document.presult.submit();
}


function goLite(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCE8CC";
}

function goDim(FRM,BTN)
{
   window.document.forms[FRM].elements[BTN].style.backgroundColor = "#CCDDCC";
}


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

<!--
<link rel="stylesheet" href="../jquery.mobile-1.0.min.css" />
<script src="../js/jquery-1.7.1.min.js"></script>
<script src="../js/jquery.mobile-1.0.min.js"></script>
-->

<!--shadowbox-->

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script src="../sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="../sb303/shadowbox.css" />

<script type="text/javascript" src="../sb303/shadowbox.js"></script>

<script type="text/javascript">

    Shadowbox.init();

</script>

<!--shadowbox-->

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
	
	
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">
	
	<!-- 內容區 -->
	<?php
	//取出單選sca_id
	$sql_s = "SELECT sca_id FROM scale WHERE m_id='".$m_id."' ORDER BY sca_order";
	$result_s = mysql_query($sql_s,$pa);
	if(!$result_s)die("執行SQL命令失敗_s");
	$row_s = mysql_fetch_assoc($result_s);
	
	//取出評審者
	//以sca_id,pg_sid，從scaler查詢評審者學號，製成 $alls_id[]
	$sql_pg = "SELECT DISTINCT(s_id) FROM scaler WHERE pg_sid='".$_COOKIE["s_id"]."' AND sca_id='".$row_s["sca_id"]."'";
	$result_pg = mysql_query($sql_pg,$pa);
	if(!$result_pg)die("執行SQL命令失敗_pg");
	while($row_pg = mysql_fetch_assoc($result_pg)){
		$alls_id[] = $row_pg["s_id"];
	}

	echo "<p>我要看 ";
	for($k=0;$k<count($alls_id);$k++){	
		echo '<a href="paresult.php?mid='.$m_id.'&snum='.$k.'">';
		
		echo '
			<input 
				type=button 
				name="work'.$k.'" 
				value="第'.($k+1).'份回饋" 
				title="" 
				class="groovybutton-green" 
				onMouseOver="goLite3(this.form.name,this.name)" 
				onMouseOut="goDim3(this.form.name,this.name)" >';
		echo '</a> ';
	}	
echo "<hr><p><b><font size='3' color='green'>　　　　　　　　　　　　【";	
if($s_num==""){
	//echo "平均數據";
	$s_num="0";
	echo "第".($s_num+1)."份回饋";
	
}
else{
	//echo "show".$alls_id[$s_num]."的回饋<br>";
	echo "第".($s_num+1)."份回饋";
}
echo '】　</font></b>
						<a href=\'paresult.php?mid='.$m_id.'\'>
						<input 
							type="button"
							name="groovybtn-g'.$m_id.'"
							
							value="好評"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a>
						
						<a href=\'paresult.php?mid='.$m_id.'\'>
						<input 
							type="button"
							name="groovybtn-n'.$m_id.'"
							
							value="普評"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a>
						
						<a href=\'paresult.php?mid='.$m_id.'\'>
						<input 
							type="button"
							name="groovybtn-b'.$m_id.'"
							
							value="申訴"
							title=""
							onMouseOver="goLite4(this.form.name,this.name)"
							onMouseOut="goDim4(this.form.name,this.name)" ></a>

</p>';
	?>

<div align="center">
	<form action="pasave.php" method="post" enctype="multipart/form-data" name="presult">
	
	<?php
	$sql = "SELECT * FROM works WHERE s_id='".$_COOKIE['s_id']."' AND m_id='".$m_id."' AND w_status='2' ORDER BY w_id DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);
	if(mysql_num_rows($result)!=0){ //有此作品
		echo '<table>';
		
		
		//檢查是否已有互評項目,有的話就顯示
		$sql_s = "SELECT sca_id,m_id,sca_directions,sca_n,sca_order,sca_word FROM scale WHERE m_id='".$m_id."' ORDER BY sca_order";
		$result_sn = mysql_query($sql_s,$pa);
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		$row_sn = mysql_fetch_assoc($result_sn);
		
		$sql_t = "SELECT txt_id,m_id,txt_directions,txt_order FROM text WHERE m_id='".$m_id."' ORDER BY txt_order";
		$result_t = mysql_query($sql_t,$pa);
		if(!$result_t)die("執行SQL命令失敗_t");
		
		if(mysql_num_rows($result_s)!=0 || mysql_num_rows($result_t)!=0){ //已有互評項目
			
			echo '		
				<input type="hidden" name="m_id" value="'.$m_id.'">
				<input type="hidden" name="scanum" value="'.mysql_num_rows($result_s).'">
				<input type="hidden" name="txtnum" value="'.mysql_num_rows($result_t).'">
				<input type="hidden" name="pg_sid" value="'.$alls_id[$s_num].'">
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
				echo '<p>';
				//取出評分輔助詞
				$allra_word = explode("-",$row_s["sca_word"]);
					
				for($k=0;$k<$row_s["sca_n"];$k++){
					
					//檢查是否已經填寫過。
					$sql_scarid = "SELECT sca_reply FROM scaler WHERE s_id='".$alls_id[$s_num]."' AND m_id='".$m_id."' AND pg_sid='".$_COOKIE['s_id']."' AND sca_id='".$row_s["sca_id"]."'";
					$result_scarid = mysql_query($sql_scarid,$pa);
					if(!$result_scarid)die("執行SQL命令失敗_scarid");
					$row_scarid = mysql_fetch_assoc($result_scarid);
					/*
					if(mysql_num_rows($result_scarid)!=0){	//若已填寫-> 自動填入資料
						
					}
					*/
					$checked ="";
					if($row_scarid["sca_reply"]==($k+1)){
						$checked = 'checked="checked"';
					}

					echo '
					<input type="radio" name="sca'.$i.'" id="sca'.$i.$k.'" value="'.($k+1).'" '.$checked.'/>
          			<label for="sca'.$i.$k.'"><font size="2">'.$allra_word[$k].'</font></label>
					';
					$checked ="";
          		}
				echo '</p>';
				echo '	
        			</fieldset>
				</div></br></td>';
				
				echo	"</tr>";
				$i++;
			}
		
			echo '<tr><td width="100%">　</td></tr>';
			echo '<tr><th>文字回饋題</th></tr>';

			$j=0;
			while($row_t = mysql_fetch_assoc($result_t)){
				//檢查是否已經填寫過。
				$sql_txtrid = "SELECT txt_reply FROM textr WHERE s_id='".$alls_id[$s_num]."' AND m_id='".$m_id."' AND pg_sid='".$_COOKIE['s_id']."' AND txt_id='".$row_t["txt_id"]."'";
				$result_txtrid = mysql_query($sql_txtrid,$pa);
				if(!$result_txtrid)die("執行SQL命令失敗_txtrid");
				$txtreply = "";
				while($row_txtrid = mysql_fetch_assoc($result_txtrid)){
					$txtreply = $row_txtrid ["txt_reply"];
				}
					
				echo '<input type="hidden" name="txt_id'.$j.'" value="'.$row_t["txt_id"].'">'; //文字題序號
				echo "<tr>";
				//echo "<td> ".($i+$j+1)."</td>";
				echo "<td><b><font size='3'>".$row_t["txt_directions"]."</font></b></td></tr>
						<tr><td><textarea cols=80 rows=3 name='txt".$j."'>".$txtreply."</textarea></td></tr>";
				
				$j++;
			}
			echo '<tr><td>';
			echo '</td></tr>';
			echo '<tr><td width="800" align="center"><p>';
		
			
		if (substr($row["w_desc"],-3) == ".sb"){
			echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="'.$row["w_desc"].'">
					</applet>
					<!-- Scratch project END-->
				'; //trim 可刪除指定字元
		}
		else if (substr($row["w_desc"],-3) == "jpg"||substr($row["w_desc"],-3) == "png"||substr($row["w_desc"],-3) == "bmp"||substr($row["w_desc"],-3) == "gif"){
			echo "<a href=".$row["w_desc"]." rel=\"shadowbox\" target=\"_top\"><img src=".$row["w_desc"]." height=\"387\"></a>";
		}
		
		else {
			echo "<p align=\"center\"><a href=".$row["w_desc"]."> [ <font color='green'>下載作品▼</font> ] </a></p>";
		}

		
		echo '</p></td></tr>';
		echo '<tr><td>　</td></tr>';
			echo '</table>';
		}
		
	}
	else{
		if($alls_id[$s_num]==""){
			echo "</br></br><p><font color='red' size='5'><b>★ 已完成互評 ★</b></font></p></br></br>";
		}
		else{
			echo "<p>沒有作品</p>";
		}
	}
	

	
	
	?>
	</p>
	</form>

<?php		

}
else{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}

?>
	
<form name="groovyform">
				<!--<input type="button" value="回首頁" onClick="self.parent.location='../index.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">-->
	</form>
	
	
	
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