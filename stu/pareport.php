<html>
<head>
<?php
//判斷是否為從show.php正常連結過來
if (empty($_COOKIE["s_id"]) || empty($_GET["s"]) || empty($_GET["sid"]) || empty($_GET["pgsid"]) || empty($_GET["mid"]) || empty($_GET["txtrid"]))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}
else{
	$par_status = $_GET["s"];
	$s_id = $_GET["sid"];
	$pg_sid = $_GET["pgsid"];
	$m_id = $_GET["mid"];
	$txt_rid = $_GET["txtrid"];
	@$reply = $_GET["reply"];
	date_default_timezone_set('Asia/Taipei');
	$now_date = date("Y.m.d");
	$now_time = date("H.i.s");
	$now_time2 = date("H:i:s");
	//判斷是否已確認要好評/申訴
	if(isset($_GET["par"])){

		//建立資料連接
		require_once('../Connections/pasql.php');
		//開啟資料庫
		$db_selected = mysql_select_db($database_pa, $pa);
		if(!$db_selected)die("無法開啟資料庫");
	
		if($par_status=="good"){
			//紀錄"好評"
			$sql = "INSERT INTO pareport (txt_rid,par_stat) VALUES('".$txt_rid."','g')";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗g");
			
				$sql = "SELECT pg_member,pg_pas FROM pg WHERE s_id='".$s_id."' AND m_id='".$m_id."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗s_pg");
				$row = mysql_fetch_assoc($result);
				$alls_id = explode("-",$row["pg_member"]);
				
				for($i=0;$i<count($alls_id);$i++){
					if($alls_id[$i]==$pg_sid){
						$snum = $i;
					}
				}
				unset($alls_id);
			
			//寄信給受到好評者
			$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$pg_sid."', '".$s_id."', '<a href=pa.php?mid=".$m_id."&snum=".$snum."&pareport=受到好評！ > ->受到好評<- </a>','".$now_date." ".$now_time2."', 's5')"; //s5為互評受到好評
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗m1".$sql);
			
			$sql = "SELECT c_id FROM stu WHERE s_id='".$pg_sid."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗c_id");
			$row = mysql_fetch_assoc($result);
			echo '<meta http-equiv="Refresh" CONTENT="0; url=show.php?mid='.$m_id.'&cid='.$row["c_id"].'>';
		}
		elseif($par_status=="appeal"){
			//紀錄"申訴"
			$sql = "INSERT INTO pareport (txt_rid,par_stat) VALUES('".$txt_rid."','b')";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗b");
			
			$sql = "SELECT t_id FROM stu,c2t WHERE stu.s_id='".$pg_sid."' AND stu.c_id=c2t.c_id";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗t_id");
			$row = mysql_fetch_assoc($result);
			$t_id = $row["t_id"];
			
			if(isset($_POST["contents"])){$contents = $_POST["contents"];}else{$contents = "";}
			//發送通知
			$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$pg_sid."', '".$t_id."', '".$pg_sid."申訴".$s_id."</br>理由：".$contents." <a href=showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id.">瀏覽</a>','".$now_date." ".$now_time2."', 't4')"; //t4為學生提出申訴
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗m".$sql);
			
			$sql = "SELECT ms_id FROM messages WHERE sender='".$pg_sid."' AND receiver='".$t_id."' AND ms_date='".$now_date." ".$now_time2."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗ms_id".$sql);
			$row = mysql_fetch_assoc($result);
			$ms_id = $row["ms_id"];
			
			$sql = "UPDATE messages SET contents='".$pg_sid."申訴".$s_id."</br>理由：".$contents." <a href=showbpa.php?txtrid=".$txt_rid."&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&msid=".$ms_id.">瀏覽</a>' WHERE sender='".$pg_sid."' AND receiver='".$t_id."' AND ms_date='".$now_date." ".$now_time2."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗update_contents".$sql);
			
			$sql = "SELECT c_id FROM stu WHERE s_id='".$pg_sid."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗c_id");
			$row = mysql_fetch_assoc($result);
			echo '<meta http-equiv="Refresh" CONTENT="0; url=show.php?mid='.$m_id.'&cid='.$row["c_id"].'>';
		}
	}
	else{

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
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
		echo "<p>評語：<b>".$reply."</b></p>";
		if($par_status=="good"){
			echo "<a href='pareport.php?par=sure&s=good&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&txtrid=".$txt_rid."'><input type='button' value='確定給好評'></a>";
		}
		elseif($par_status=="appeal"){
			echo "<form action='pareport.php?par=sure&s=appeal&sid=".$s_id."&pgsid=".$pg_sid."&mid=".$m_id."&txtrid=".$txt_rid."' method='post'>";
			echo "<font color='red'>請簡述申訴原因</font></br>";
			echo "<textarea cols=80 rows=3 name='contents'></textarea></br>";
			echo "<input type='submit' value='確定'>";
			echo "</form>";
		}
	}
}
?>
	</p>
	</form>

	
<form name="groovyform">
				<input type="button" value="回首頁" onClick="self.parent.location='../index.php'" style="font-size: 12 pt; border-style: ridge; border-width:3 ">
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