<?php
session_start();
//random peer group to class

if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
else{
	if(isset($_GET["n"])){$n = $_GET["n"];}
	else{$n = "";}
	if(isset($_GET["mid"])){$m_id = $_GET["mid"];}
	else{$m_id = "";}
	if(isset($_GET["mname"])){$m_name = $_GET["mname"];}
	else{$m_name = "";}
	
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

?>
<html>
<head>
<title>隨機指派互評結果</title>
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
	<table class="outtable" bgcolor="white">
	  <tr>
    <td colspan="2" class="menu">
	  [<a href="teacher.php">回首頁</a>]
      [<a href="logout.php">登出系統</a>]
    </td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">[隨機指派互評結果]<?php if($n!=""){echo $m_name."- 評".$n."件"; } ?></td>
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
	if($n=="" || $m_id==""){
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
			$j = 1;
			//取m_id,m_panums
			$sql = "SELECT m_id,m_name,m_panums FROM mission WHERE syear='".$syear."' ORDER BY m_order";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗_m");
			while($row = mysql_fetch_assoc($result)){
				echo '
				case '.$j.':
					parent.location.href="pglist.php?mid='.$row["m_id"].'&n='.$row["m_panums"].'&mname='.$row["m_name"].'";
					break;
				';
				$j++;
			}
			echo '
	
			}
		}
		</script>
	
		<form name="midiform">
		<font size="5"><b>請選擇任務：</b></font><select name=mysel onchange="playmidi()">
		<option>請選擇任務
		';
		$j = 1;
		//取m_id,m_panums
		$sql = "SELECT m_id,m_name,m_panums FROM mission WHERE syear='".$syear."' ORDER BY m_order";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_m");
		while($row = mysql_fetch_assoc($result)){
			echo '
			<option value="'.$j.'">'.$row["m_name"];
			$j++;
		}

		echo '
		</select>
		</form>
		';
		echo "</td></tr></table>";

	}
	else{
		//取出班級
		//取c_id,c_name
		$sql = "SELECT m2c.c_id,class.c_class FROM m2c,class WHERE m2c.m_id='".$m_id."' AND m2c.c_id=class.c_id";
		//$sql = "SELECT m2c.c.id,class.c_class FROM m2c,class WHERE m2c.m_id='".$m_id."' AND m2c.c_id=class.c_id ORDER BY class.c_class";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_m2c".$sql);
		while($row = mysql_fetch_assoc($result)){
				echo "<table width=\"0\" cellpadding=\"5\" >";
				echo "<tr><th COLSPAN=\"".(($n+1)*2)."\">".$row["c_class"]."指派結果</th></tr>";
				echo "<tr><th>座號</th><th>姓名</th><td COLSPAN=\"".(($n+1)*2)."\" align=\"center\">評審對象</td></tr>";
			
				//以c_id，從stu取s_id
				$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$row["c_id"]."' ORDER BY CAST(s_classnums AS UNSIGNED)";
				$result_si = mysql_query($sql_si,$pa);
				if(!$result_si)die("執行SQL命令失敗_si");
				
				//列出隨機指派結果
				while($row_si = mysql_fetch_assoc($result_si)){

					//以c_id,m_id，從pg取出學生學號,評審對象
					$sql_sp = "SELECT pg_member FROM pg WHERE s_id='".$row_si["s_id"]."' AND m_id='".$m_id."'";
					$result_sp = mysql_query($sql_sp,$pa);
					if(!$result_sp)die("執行SQL命令失敗_sp");					
					while($row_sp = mysql_fetch_assoc($result_sp)){
						//以s_id，從stu取s_classnums,s_name
						echo "<th align=\"right\">".$row_si["s_classnums"]."</th><th>".$row_si["s_name"]."</th>";
						
						//以評審對象的學號，從stu取出s_classnums,s_name
						$allpg_id = explode("-",$row_sp["pg_member"]);
						for($r=0;$r<count($allpg_id);$r++){
							$sql_sp3 = "SELECT s_classnums,s_name FROM stu WHERE s_id='".$allpg_id[$r]."'";
							$result_sp3 = mysql_query($sql_sp3,$pa);
							if(!$result_sp3)die("執行SQL命令失敗_sp3");
							$row_sp3 = mysql_fetch_assoc($result_sp3);
							echo "<td align=\"right\">".$row_sp3["s_classnums"]."</td><td>".$row_sp3["s_name"]."</td>";
						}
						//列出結果
						echo "</tr>";
					}
				}
			echo "<tr>";				
			echo "<td></td>";
			echo "<td></td>";
			echo "</tr>";
			echo "</table>";
		}
	}
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
				onClick="self.location='index.php'">
			<input
				type="button"
				name="groovybtn1"
				class="groovybutton-back"
				value="重新查詢"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location='pglist.php'">
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