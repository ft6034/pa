<?php
session_start();
//random peer group
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
else{

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

	$n = $_GET["n"];
	$c_id = $_GET["cid"];
	$m_id = $_GET["mid"];
	$c_name = $_GET["cname"];
	$m_name = $_GET["mname"];

?>
<html>
<head>
<title>隨機指派互評</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="jquery.mobile-1.0.min.css" />
<script src="./js/jquery-1.7.1.min.js"></script>
<script src="./js/jquery.mobile-1.0.min.js"></script>

<link href="style2.css" rel="stylesheet" type="text/css">

</head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="white">

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
	<!--<tr>
    <td colspan="2" class="menu">
	  [<a href="teacher.php">回首頁</a>]
      [<a href="logout.php">登出系統</a>]
    </td>
	</tr>-->
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">隨機指派互評
		<?php if($n!=""){echo "-每人互評件數為".($n-1)."件"; }?></td>
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
	<div align="center">
      
        
<?php

//判斷是否已選擇互評人數
if($n==""){
	echo "<table width=\"0\" cellpadding=\"5\" ><tr><td>";
	echo "</br></br>";
	
	//選擇互評人數
	echo '
	<script language="Javascript">
	function playmidi()
	{
		switch(midiform.mysel.selectedIndex)
		{
	';
			//取m_id,m_panums
			$sql = "SELECT m_id,m_name,m_panums FROM mission WHERE syear='".$syear."' ORDER BY m_order";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗_m");
			while($row = mysql_fetch_assoc($result)){
				echo '
				case '.$row["m_id"].':
					parent.location.href="pglist.php?mid='.$row["m_id"].'&n='.$row["m_panums"].'&manme='.$row["m_name"].'";
					break;
			';
			}

		echo '
	
		}
	}
	</script>
	
	<form name=midiform>
	<font size="5"><b>每人互評多少件：</b></font><select name=mysel onchange="playmidi()">
	<option>請選擇互評件數
	';

	//取m_id,m_panums
		$sql = "SELECT m_id,m_name,m_panums FROM mission WHERE syear='".$syear."' ORDER BY m_order";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_m");
		while($row = mysql_fetch_assoc($result)){
			echo '
			<option>'.$row["m_id"];
		}

	echo '
	</select>
	</form>
	';
	echo "</td></tr></table>";

}
else{

    
	  
	echo  "<table class=\"outtable2\">";
	echo  "<tr><th>任務名稱</th><th>已指派互評</th><th>未指派互評</th><th>全部指派</th></tr>";

	//列出任務
	$sql = "SELECT m_id,m_name FROM mission WHERE syear='".$syear."' AND t_id='".$_SESSION["t_id"]."' ORDER BY m_order";
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗_取任務");
	$trbg = 0;
	while($row = mysql_fetch_assoc($result)){
		$trbg += 1;
		if($trbg%2==1){
			echo "<tr bgcolor=\"white\"><td class='td-solid'>".$row["m_name"]."</td>";
		}
		else{
			echo "<tr bgcolor=\"#FFFFCC\"><td class='td-solid'>".$row["m_name"]."</td>";
		}

		//取已指派任務班級
		$sql_c = "SELECT class.c_id,class.c_class FROM m2c,class WHERE m2c.m_id='".$row["m_id"]."' AND m2c.c_id=class.c_id ORDER BY class.c_class";
		$result_c = mysql_query($sql_c,$pa);
		if(!$result_c)die("執行SQL命令失敗-取已指派任務班級_c");
		while($row_c = mysql_fetch_assoc($result_c)){
			//取已指派互評班級
			$sql_pgc = "SELECT c_id FROM pg WHERE m_id='".$row["m_id"]."' AND c_id='".$row_c["c_id"]."'";
			$result_pgc = mysql_query($sql_pgc,$pa);
			if(!$result_pgc)die("執行SQL命令失敗-取已指派互評班級_pgc");
			
			if(mysql_num_rows($result_pgc)!=0){
				$pded[] = $row_c["c_id"]."-".$row_c["c_class"];
			}
			else{
				$pdun[] = $row_c["c_id"]."-".$row_c["c_class"];
			}
		}
		echo "<td class='td-solid'>";
		if(count($pded)!=0){
			foreach ($pded as $value) {
				$pded_c = explode("-",$value);
				echo $pded_c[1]." ";
			}
		}
		unset($pded);
		echo "</td>";
		
		echo "<td class='td-solid'>";
		if(count($pdun)!=0){
			$classun = "";
			foreach ($pdun as $value) {
				$pdun_c = explode("-",$value);
				echo "<a href=\"rpgc.php?cid=".$pdun_c[0]."&mid=".$row["m_id"]."&mname=".$row["m_name"]."&cname=".$pdun_c[1]."&n=".$n." \" data-role=\"button\" data-inline=\"true\" data-icon=\"plus\" >".$pdun_c[1]."</a> ";
				if($classun != ""){
					$classun = $classun."-".$pdun_c[0];
				}
				else{
					$classun = $pdun_c[0];
				}
				
			}
			echo "</br><a href=\"rpgc.php?cid=".$classun."&mid=".$row["m_id"]."&mname=".$row["m_name"]."&n=".$n." \" data-role=\"button\" data-inline=\"true\" data-icon=\"plus\" >指派所有未指派過的班級</a></br>";
		}
		unset($pdun);		
		echo "</td>";
		
		echo "<td class='td-solid'>";
		echo "<a href=\"rpgc.php?cid=allreset&mid=".$row["m_id"]."&mname=".$row["m_name"]."&n=".$n." \">全部重新指派</br>(刪除互評記錄)</a>";
		echo "</td></tr>";
	}
	
	echo "</table>";

}
?>

</div>
	
	<!-- 內容區 end-->
	<form name="groovyform">
			<input
				type="button"
				name="groovybtn1"
				class="groovybutton-back"
				value="回首頁"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location='index.php'"
				data-inline="true" data-icon="home">
			<?php
			if($n!=""){
				echo '
				<input
				type="button"
				name="groovybtn1"
				class="groovybutton-back"
				value="重設互評人數"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location=\'rpg.php\'"
				data-inline="true" data-icon="back">
				';
			}
			?>
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



<?php
}
?>