<?php
session_start();

if(isset($_SESSION["t_id"])){
	$t_id = $_SESSION["t_id"];
}

$txt_rid = $_GET["txtrid"];
$s_id = $_GET["sid"];
$pg_sid = $_GET["pgsid"];
$m_id = $_GET["mid"];
if(isset($_GET["msid"])){$ms_id = $_GET["msid"];}else{$ms_id = "";}


$s_num = "";
if(isset($_GET["snum"])){
	$s_num = $_GET["snum"];
}

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

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");
$now_time2 = date("H:i:s");

$sql = "SELECT stu.s_name, stu.s_classnums, stu.c_id, m2c.m2c_status FROM stu, m2c WHERE stu.s_id='".$s_id."' AND stu.c_id=m2c.c_id AND m2c.m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$c_id = $row["c_id"];
$s_classnums = $row["s_classnums"];
$s_name = $row["s_name"];
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
	<form name="adjudge" action="#">
	<tr>
		<td class="title"><?php if(isset($_COOKIE["s_id"])){}else{ echo $s_id;}?><font size="4">的評審內容</font></td>
		<td>
	<?php 
	echo $pg_sid."的申訴 ";
	if(isset($_GET["stat"])){
		echo $_GET["stat"]."</br>";
		echo "判決：".$_GET["aresult"];
			
		if(isset($_COOKIE["s_id"])){
		}
		else{
			if($_GET["stat"]=="有效"){
				//申訴有效-寄信給申訴者s3
				$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category, mresult) VALUES('".$t_id."', '".$pg_sid."', '<a href=../showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&stat=有效&aresult=".$_GET["aresult"].">->瀏覽判決<-</a>','".$now_date." ".$now_time2."', 's6', '申訴".$_GET["stat"]." <a href=showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&stat=有效&aresult=".$_GET["aresult"].">->瀏覽判決<-</a>')"; //s3為互評受到退件
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗m1".$sql);
				
				//申訴有效-寄信給被申訴者s3
				$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$t_id."', '".$s_id."', '".$_GET["aresult"]."</br>　<a href=pa.php?mid=".$m_id.">修改評語</a>','".$now_date." ".$now_time2."', 's3')"; //s3為互評受到退件
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗m2".$sql);
				
				$sql = "SELECT pg_member,pg_pas FROM pg WHERE s_id='".$s_id."' AND m_id='".$m_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗s_pg");
				$row = mysql_fetch_assoc($result);
				$alls_id = explode("-",$row["pg_member"]);
				$alls_pas = explode("-",$row["pg_pas"]);
				
				$pg_member = "";
				$pg_pas = "";
				for($i=0;$i<count($alls_id);$i++){
					if($pg_member==""){
						$pg_member = $alls_id[$i];
					}
					else{
						$pg_member = $pg_member."-".$alls_id[$i];
					}
					
					if($pg_pas==""){
						if($alls_id[$i]==$pg_sid){
							$pg_pas = "1" ;
						}
						else{
							$pg_pas = $alls_pas[$i];
						}
					}
					else{
						if($alls_id[$i]==$pg_sid){
							$pg_pas = $pg_pas."-1";
						}
						else{
							$pg_pas = $pg_pas."-".$alls_pas[$i];
						}
					}
				}
				unset($alls_id);
				unset($alls_pas);
				
				//申訴有效-UPDATE評論為暫時儲存
				$sql = "UPDATE pg SET pg_pas='".$pg_pas."' WHERE s_id='".$s_id."' AND m_id='".$m_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗u_pg");
				
				//更新教師端訊息的處理結果
				$sql = "UPDATE messages SET mresult='申訴".$_GET["stat"]." <a href=showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&stat=有效&aresult=".$_GET["aresult"].">->瀏覽判決<-</a>' WHERE ms_id='".$ms_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗u_pg");
			}

			if($_GET["stat"]=="無效"){
				//申訴無效-寄信給申訴者
				$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$t_id."', '".$pg_sid."', '申訴無效！　<a href=../showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&stat=無效&aresult=".$_GET["aresult"].">->瀏覽判決<-</a>','".$now_date." ".$now_time2."', 's7')"; //s3為互評受到退件
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗m1".$sql);
				
				//更新教師端訊息的處理結果
				$sql = "UPDATE messages SET mresult='申訴".$_GET["stat"]." <a href=showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&stat=無效&aresult=".$_GET["aresult"].">->瀏覽判決<-</a>' WHERE ms_id='".$ms_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗u_pg");
			}
		}	
	}
	else{
		echo'
		　<input type="submit" name="stat" value="有效"/>
		　<input type="submit" name="stat" value="無效"/>
		<textarea cols="60" rows="2" name="aresult" placeholder="請輸入判決..."></textarea>';
	}
	?>
		<input type="hidden" name="txtrid" value="<?php echo $txt_rid; ?>">
		<input type="hidden" name="sid" value="<?php echo $s_id; ?>">
		<input type="hidden" name="pgsid" value="<?php echo $pg_sid; ?>">
		<input type="hidden" name="mid" value="<?php echo $m_id; ?>">
		<input type="hidden" name="msid" value="<?php echo $ms_id; ?>">
<?php
	//取出互評對象
	
	$sql_pg = "SELECT pg_member,pg_pas FROM pg WHERE c_id='".$c_id."' AND m_id='".$m_id."' AND s_id='".$s_id."'";
	$result_pg = mysql_query($sql_pg,$pa);
	if(!$result_pg)die("執行SQL命令失敗_pg");
	$row_pg = mysql_fetch_assoc($result_pg);
	$alls_id = explode("-",$row_pg["pg_member"]);
	$alls_pas = explode("-",$row_pg["pg_pas"]);
?>
		</br><input type="button" value="回首頁" onClick="self.parent.location='./index.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
		<input type="button" value="回訊息一覽" onClick="self.location='./message.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
		</td>
		<td>

		</td>
	</tr>
	</form>
	<tr>
		<td height="4" colspan="3"><hr></td>
	</tr>
	</table>
	
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	
	<!-- 內容區 -->

<div align="center">
	<form action="showbpa.php?s=adjudge" method="post" enctype="multipart/form-data" name="presult">
	
	<?php
	$sql = "SELECT * FROM works WHERE s_id='".$pg_sid."' AND m_id='".$m_id."' AND w_status='2' ORDER BY w_id DESC";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗1");
	$row = mysql_fetch_assoc($result);
	if(mysql_num_rows($result)!=0){
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
				<input type="hidden" name="pg_sid" value="'.$pg_sid.'">
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
					$sql_scarid = "SELECT sca_reply FROM scaler WHERE s_id='".$s_id."' AND m_id='".$m_id."' AND pg_sid='".$pg_sid."' AND sca_id='".$row_s["sca_id"]."'";
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
				$sql_txtrid = "SELECT txt_reply FROM textr WHERE s_id='".$s_id."' AND m_id='".$m_id."' AND pg_sid='".$pg_sid."' AND txt_id='".$row_t["txt_id"]."'";
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
						<tr><td><textarea cols=80 rows=3 name='txt".$j."' onkeyup='changeText(this);'>".$txtreply."</textarea></td></tr>";
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
/*			
			echo '
			<p align="right">
			<input 
				type="submit" 
				name="save" 
				class="groovybutton-blue" 
				value="　暫時儲存　" 
				onclick="switchpage(\'pasave.php\')" 
				onMouseOver="goLite2(this.form.name,this.name)" 
				onMouseOut="goDim2(this.form.name,this.name)" >
			<input 
				type="submit" 
				name="sure" 
				value="　確定送出　" 
				class="groovybutton-red" 
				onMouseOver="goLite(this.form.name,this.name)" 
				onMouseOut="goDim(this.form.name,this.name)" 
				onclick="switchpage(\'pasave.php?fin=done\')" >
			</p>
			
			<!-- 
			<div class="ui-body ui-body-b">
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><input type="submit" data-theme="a" onclick="switchpage(\'pasave.php\')" value="儲存" name="save" style="font-size: 12 pt; border-style: ridge; border-width:3 "></div>
					<div class="ui-block-b"><input type="submit" data-theme="b" onclick="switchpage(\'pasave.php?fin=done\')" value="送出" name="submit" style="font-size: 12 pt; border-style: ridge; border-width:3 "></div>
				</fieldset>
			</div>
			-->
			';
*/
			
			echo '</td></tr>';
			
		}
		echo '<tr><td width="800" align="center">';
		echo '<p>
					<!-- Scratch project START-->
					<applet id="ProjectApplet"
						style="display:block"
						code="ScratchApplet" codebase="./"
						archive="ScratchApplet.jar" height="387" width="482">
						<param name="project" value="./stu'.trim($row["w_desc"],".").'">
					</applet>
					<!-- Scratch project END-->
				</p>'; //trim 可刪除指定字元
		echo '</td></tr>';
		echo '<tr><td>　</td></tr>';
		echo '</table>';
	}
	else{
		echo "<p>沒有作品</p>";
	}
	/*
	echo "<p>觀看 ";
	for($k=0;$k<count($alls_id);$k++){	
		echo '<a href="showpa.php?sid='.$s_id.'&mid='.$m_id.'&snum='.$k.'">';
		
		echo '
			<input 
				type=button 
				name="work'.$k.'" 
				value="第'.($k+1).'件" 
				title="" 
				class="groovybutton-green" 
				onMouseOver="goLite3(this.form.name,this.name)" 
				onMouseOut="goDim3(this.form.name,this.name)" >';
		echo '</a> ';
	}
	*/
	
	?>
	</p>
	</form>
	
	<!--<form name="groovyform">
				<input type="button" value="回首頁" onClick="self.parent.location='./index.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
	</form>-->
	
	
	
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
