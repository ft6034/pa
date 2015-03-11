<?php
session_start();
//判斷是否登入
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
$s_id = $_GET["sid"];
$m_id = $_GET["mid"];

//建立資料連接
require_once('./Connections/pasql.php');
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");

//取目前的學年學期
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

$s_name = "";
$sql = "SELECT stu.s_name, stu.s_classnums, stu.c_id, m2c.m2c_status FROM stu, m2c WHERE stu.s_id='".$s_id."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$c_id = $row["c_id"];
$s_classnums = $row["s_classnums"];
$s_name = $row["s_name"];

//取得等第數量
$sql_n = "SELECT sca_n FROM scale WHERE m_id='".$m_id."' ORDER BY sca_order";
$result_n = mysql_query($sql_n,$pa);
if(!$result_n)die("執行SQL命令失敗_n");
$row_n = mysql_fetch_assoc($result_n);
$sca_n = $row_n["sca_n"];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="./style2.css" rel="stylesheet" type="text/css">

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
<link rel="stylesheet" href="./jquery.mobile-1.0.min.css" />
<script src="./js/jquery-1.7.1.min.js"></script>
<script src="./js/jquery.mobile-1.0.min.js"></script>
-->

<script type="text/javascript">
var iCount = 0;
function changeText(objElement) {
    var oTextCount = document.getElementById("txtCount");
    var oCount = document.getElementById("hdnCount");
    iCount = objElement.value.length;
    oTextCount.innerHTML = "" + iCount;
    oCount.value = parseInt(iCount);
}
</script>

<script>
		//全選-完全做到
        function checkall() {
        //找到所有input
            var rad = document.getElementsByTagName("input");
            for (var i = 0; i < rad.length; i++) {
                var e = rad[i];
                if (e.type == 'radio'&&e.value=='<?=$sca_n;?>') {
                    e.checked = true;
                }

            }
        }
       //全不選
        function check() {
             var rad = document.getElementsByTagName("input");
            for (var i = 0; i < rad.length; i++) {
                var e = rad[i];
                if (e.type == 'radio') {
                    e.checked = false;      
                }

            }
        }
</script>

<!--shadowbox-->

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script src="./sb303/jquery-latest.js" type="text/JavaScript"></script>

<link rel="stylesheet" type="text/css" href="./sb303/shadowbox.css" />

<script type="text/javascript" src="./sb303/shadowbox.js"></script>

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

	<!-- 標題區 -->
	<table class="outtable">
	<tr>
		<td class="title"><?php echo $s_name;?><font size="4">的作品</font></td>
		<td class="function">
<?php
		$next="";
		$sql = "SELECT s_id,s_name FROM stu WHERE s_classnums<".$s_classnums." AND c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED) DESC";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗1");
		$row = mysql_fetch_assoc($result);
		$next_sid = $row["s_id"];
		$next_sname = $row["s_name"];
		if(mysql_num_rows($result)!=0){
			//$next = "<a href='showrework.php?mid=".$m_id."&sid=".$next_sid."'>[評上一位 (".$next_sname.") ]</a>　";
			$next = "<a href='showrework.php?mid=".$m_id."&sid=".$next_sid."'>[ 上一位 ]</a>　";
		}
		echo $next;
		$next="";

		$sql = "SELECT s_id,s_name FROM stu WHERE s_classnums>".$s_classnums." AND c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗1");
		$row = mysql_fetch_assoc($result);
		$next_sid = $row["s_id"];
		$next_sname = $row["s_name"];
		if(mysql_num_rows($result)!=0){
			//$next = "<a href='showrework.php?mid=".$m_id."&sid=".$next_sid."'>[ 評下一位 (".$next_sname.") ]</a>";
			$next = "<a href='showrework.php?mid=".$m_id."&sid=".$next_sid."'>[ 下一位 (".$next_sname.") ]</a>";
		}
		echo $next;
		$next="";
?>		
		
		</td>
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
	
	<!-- 內容區 -->
	<?php
	//判斷是否已經完成評審
	$sql = "SELECT * FROM rework WHERE s_id='".$s_id."' AND m_id='".$m_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);
	$rew_id = $row["rew_id"];
	
	echo "<p>";
	echo "<font color='red'>【";
	switch ($row["t_status"]){
		case "":
			echo " 尚未評審 ";
		break;
	
		case "0":
			echo " 尚未評審 ";
		break;
					
		case "1":
			echo " 已儲存，尚未送出 ";
		break;
					
		case "2":
			echo " 已送出，評審完成 ";
		break;
	}
	echo "】</font>";
	echo "</p>";
	?>

<div align="center">
	<form action="taresave.php" method="post" enctype="multipart/form-data" name="presult" id="presult">
	
	<?php
	if(mysql_num_rows($result)!=0){
		echo '<table>';
		echo '<tr><td width="800" align="center"><p>';
		
		//判斷作品檔案格式，展示作品
		if (substr($row["rew_desc"],-3) == ".sb"){
			echo '
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="./stu'.trim($row["rew_desc"],".").'">
					</applet>
					<!-- Scratch project END-->
				'; //trim 可刪除指定字元
		}
		else if (substr($row["rew_desc"],-3) == "jpg"||substr($row["rew_desc"],-3) == "png"||substr($row["rew_desc"],-3) == "bmp"||substr($row["rew_desc"],-3) == "gif"){
			echo "<a href=./stu".trim($row["rew_desc"],".")." rel=\"shadowbox\" target=\"_top\"><img src=./stu".trim($row["rew_desc"],".")." height=\"387\"></a>";
		}
		elseif (substr($row["rew_desc"],-4) == ".mp4"||substr($row["rew_desc"],-4) == "webm"||substr($row["rew_desc"],-4) == ".ogg"){
			echo '
				<video width="600" controls>
				<source src="'.$row["rew_desc"].'" type="video/'.str_replace('.','',strrchr($row["rew_desc"], ".")).'">
				Your browser does not support the video tag.
				</video>	
			';
		}
		else {
			echo "<p align=\"center\"><a href=./stu".trim($row["rew_desc"],".")." target='_blank'> [ <font color='green'>下載作品▼</font> ] </a></p>";
		}
		
		echo '</p></td></tr>';
		echo '<tr><td align="right"><textarea cols="80" rows="1" name="aresult" placeholder="請輸入退件原因..."></textarea><input type="submit" value="退件"></td></tr>';
		
		
		//檢查是否已有互評項目,有的話就顯示
		$sql_s = "SELECT sca_id,m_id,sca_directions,sca_n,sca_order,sca_word FROM scale WHERE m_id='".$m_id."' ORDER BY sca_order";
		$result_sn = mysql_query($sql_s,$pa);
		$result_s = mysql_query($sql_s,$pa);
		if(!$result_s)die("執行SQL命令失敗_s");
		$row_sn = mysql_fetch_assoc($result_sn);
		
		$sql_t = "SELECT txt_id,m_id,txt_directions,txt_order FROM text WHERE m_id='".$m_id."' ORDER BY txt_order";
		$result_t = mysql_query($sql_t,$pa);
		if(!$result_t)die("執行SQL命令失敗_t");
		
		if(mysql_num_rows($result_s)!=0 || mysql_num_rows($result_t)!=0){ //已有評審項目
			
			echo '		
				<input type="hidden" name="m_id" value="'.$m_id.'">
				<input type="hidden" name="rew_id" value="'.$rew_id.'">
				<input type="hidden" name="scanum" value="'.mysql_num_rows($result_s).'">
				<input type="hidden" name="txtnum" value="'.mysql_num_rows($result_t).'">
				<input type="hidden" name="pg_sid" value="'.$s_id.'">
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
				$allsca_word  = explode("-",$row_s["sca_word"]);
				$allsca_word[] = "表現優異，獲得額外加1分";
				$allsca_word[] = "表現優異，獲得額外加2分";
					
				for($k=0;$k<($row_s["sca_n"]+2);$k++){
					
					//檢查是否已經填寫過。
					$sql_scarid = "SELECT sca_reply FROM scaleretr WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$s_id."' AND sca_id='".$row_s["sca_id"]."'";
					$result_scarid = mysql_query($sql_scarid,$pa);
					if(!$result_scarid)die("執行SQL命令失敗_scarid");
					$row_scarid = mysql_fetch_assoc($result_scarid);
					/*
					if(mysql_num_rows($result_scarid)!=0){	//若已填寫-> 自動填入資料
						
					}
					*/

					$checked = "";
					if($row_scarid["sca_reply"]==($k+1)){
						$checked = 'checked="checked"';
					}

					echo '
					<input type="radio" name="sca'.$i.'" id="sca'.$i.$k.'" value="'.($k+1).'" '.$checked.'/>
          			<label for="sca'.$i.$k.'"><font size="2">'.$allsca_word [$k].'</font></label>
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
			echo '<tr><td align="right">
					<input id="Radio11" type="button" name="radio" value="全選-完全做到" onClick="checkall(this.form);">
					<input id="Radio12" type="button" name="radio" value="全不選" onClick="check(this.form)"></td></tr>';
			echo '<tr><td width="100%">　</td></tr>';
			echo '<tr><th>文字回饋題</th></tr>';

			$j=0;
			while($row_t = mysql_fetch_assoc($result_t)){
				//檢查是否已經填寫過。
				$sql_txtrid = "SELECT txt_reply FROM textretr WHERE t_id='".$_SESSION["t_id"]."' AND pg_sid='".$s_id."' AND txt_id='".$row_t["txt_id"]."'";
				$result_txtrid = mysql_query($sql_txtrid,$pa);
				if(!$result_txtrid)die("執行SQL命令失敗_txtrid");
				$txtreply = "所有標準均達成，表現良好！";
				while($row_txtrid = mysql_fetch_assoc($result_txtrid)){
					$txtreply = $row_txtrid ["txt_reply"];
				}
					
				echo '<input type="hidden" name="txt_id'.$j.'" value="'.$row_t["txt_id"].'">'; //文字題序號
				echo "<tr>";
				//echo "<td> ".($i+$j+1)."</td>";
				echo "<td><b><font size='3'>".$row_t["txt_directions"]."</font></b></td></tr>
						<tr><td>
						<select name='credit_opinion' onChange='popText();' >
							<option value='0'>您的評語範例</option>";
				
				//取通用評語範例
				$sql_sample = "SELECT txt_sample FROM textrs WHERE m_id='general' AND owner='".$_SESSION["t_id"]."'";
				$result_sample = mysql_query($sql_sample,$pa);
				if(!$result_sample)die("執行SQL命令失敗_sample");
				while($row_sample = mysql_fetch_assoc($result_sample)){
					echo "<option value='".$row_sample ["txt_sample"]."'>".$row_sample ["txt_sample"]."</option>";
				}
				//取任務評語範例
				$sql_sample = "SELECT txt_sample FROM textrs WHERE m_id='".$m_id."' AND owner='".$_SESSION["t_id"]."'";
				$result_sample = mysql_query($sql_sample,$pa);
				if(!$result_sample)die("執行SQL命令失敗_sample");
				while($row_sample = mysql_fetch_assoc($result_sample)){
					echo "<option value='".$row_sample ["txt_sample"]."'>".$row_sample ["txt_sample"]."</option>";
				}
							
				echo "
						</select></br> 
						<textarea cols=80 rows=3 name='txt".$j."'>".$txtreply."</textarea> 
						<input type='submit' name='savesample' value='存成[通用]的評語範例' onclick=\"switchpage('taresave.php?sample=gsave')\" >
						<input type='submit' name='savesample' value='存成[此任務]的評語範例' onclick=\"switchpage('taresave.php?sample=msave')\" >
						</td></tr>";
				echo '
					<script>
						function popText() {
							var opn_var = document.presult.credit_opinion.options[document.presult.credit_opinion.selectedIndex].value;
							if(opn_var != "0") {
								document.presult.txt'.$j.'.value = 		document.presult.credit_opinion.options[document.presult.credit_opinion.selectedIndex].value;
							}
						}
					</script>
				';
				echo "<tr><td>評語參考詞： ";
				$sql_h = "SELECT h_word FROM help ORDER BY RAND() LIMIT 3";
				$result_h = mysql_query($sql_h,$pa);
				if(!$result_h)die("執行SQL命令失敗_h");
				while($row_h = mysql_fetch_assoc($result_h)){
					echo $row_h["h_word"]." ";
				}
				echo '<p><font color="gray">目前輸入<span id="txtCount">'.mb_strlen($txtreply, 'utf-8').'</span>個字</font></p>';
				echo "</td></tr>";
				$j++;
			}
			echo '<tr><td>';
			
			if($next_sid==""){$next_sid=$s_id;}
			echo '
			<p align="right">
			<input type="hidden" name="next_sid" value="'.$next_sid.'">
			<input 
				type="submit" 
				name="save" 
				class="groovybutton-blue" 
				value="　暫時儲存　" 
				onclick="switchpage(\'taresave.php\')" 
				onMouseOver="goLite2(this.form.name,this.name)" 
				onMouseOut="goDim2(this.form.name,this.name)" >
			<input 
				type="submit" 
				name="sure" 
				value="　確定送出　" 
				class="groovybutton-red" 
				onMouseOver="goLite(this.form.name,this.name)" 
				onMouseOut="goDim(this.form.name,this.name)" 
				onclick="switchpage(\'taresave.php?fin=done\')" >
			</p>
			
			<!-- 
			<div class="ui-body ui-body-b">
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><input type="submit" data-theme="a" onclick="switchpage(\'taresave.php?wid='.$rew_id.'\')" value="儲存" name="save" style="font-size: 12 pt; border-style: ridge; border-width:3 "></div>
					<div class="ui-block-b"><input type="submit" data-theme="b" onclick="switchpage(\'taresave.php?wid='.$rew_id.'&fin=done\')" value="送出" name="submit" style="font-size: 12 pt; border-style: ridge; border-width:3 "></div>
				</fieldset>
			</div>
			-->
			';

			echo '</td></tr>';
			echo '</table>';
		}
		
	}
	else{
		echo "<font size='4'>未繳交修正作品</font>";
	}
	$m_name="";
	$sql = "SELECT m_name FROM mission WHERE m_id='".$m_id."'";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗");
	$row = mysql_fetch_assoc($result);
	$m_name=$row["m_name"];
	
	$c_name="";
	$sql_c = "SELECT c_class FROM class WHERE c_id='".$c_id."' AND syear='".$syear."'";
	$result_c = mysql_query($sql_c,$pa);
	if(!$result_c)die("執行SQL命令失敗_c");
	$row_c = mysql_fetch_assoc($result_c);
	$c_name=$row_c["c_class"];
	?>
	</p>
	</form>
	
	<form name="groovyform">
				<input type="button" value="回訊息頁" onClick="self.parent.location='message.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
				<input type="button" value="回評分頁" onClick="self.parent.location='./pclass.php?cid=<?=$c_id;?>&mid=<?=$m_id;?>&cname=<?=$c_name;?>&mname=<?=$m_name;?>'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
	</form>
	
	
	
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