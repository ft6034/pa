<?php
session_start();
//random peer group to class

if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
else{
	$n = $_GET["n"]; //互評小組人數
	$c_id = $_GET["cid"];
	$m_id = $_GET["mid"];
	$c_name = $_GET["cname"];
	$m_name = $_GET["mname"];
	
	//製作尚未互評記錄字串
	$pas="";
	for($z=0;$z<($n-1);$z++){
		if($pas==""){
			$pas = "0";
		}
		else{
			$pas = $pas."-0";
		}
	}

	if($m_id!="" && $c_id!=""){
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
	  [<a href="./stu/stu.php">模擬學生</a>]
      [<a href="logout.php">登出系統</a>]
    </td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">[隨機指派互評結果]<?php echo $m_name;?>- 評<?php echo ($n-1);?>件</td>
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
      <form action="tr.php" method="post" enctype="multipart/form-data">
        <table width="0" cellpadding="5">

<?php
		
		
			//全部重新指派
			if($c_id=="allreset"){
				$sql_re = "SELECT m2c.c_id,class.c_class FROM m2c,class WHERE m2c.m_id='".$m_id."' AND m2c.c_id=class.c_id";
				$result_re = mysql_query($sql_re,$pa);
				if(!$result_re)die("執行SQL命令失敗_re");
				while($row_re = mysql_fetch_assoc($result_re)){
					
					
					//刪除所有紀錄(班級-任務)
					$sql_del = "DELETE FROM pg WHERE c_id='".$row_re["c_id"]."' AND m_id='".$m_id."'";
					$result_del = mysql_query($sql_del,$pa);
					if(!$result_del)die("執行SQL命令失敗_del");					
					
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$row_re["c_id"]."'	 ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					
					unset($a);
					unset($s_classnums);
					unset($s_name);
					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$a[] = $row_si["s_id"]; //學生s_id數列
						$s_classnums[] = $row_si["s_classnums"];
						$s_name[] = $row_si["s_name"];
					}
				
					//隨機排列學生座號
					shuffle($s_classnums);

					$srr="abcdefghijklmnopqrstuvwxyz";
					//有n個對象，就產生n個數列(從 $s_id0 開始, $s_id1, $s_id2,...)
					for($i=1;$i<$n;$i++){
						random1_cids:
						//隨機排列學生座號
						${substr($srr,$i,1)} = $a;
						shuffle(${substr($srr,$i,1)});

						//目前來到第$i個數列，要比對$i個數列
						for($j=0;$j<$i;$j++){
							//進到數列裡比對
							for($k=0;$k<count($a);$k++){
								if($i!=$j){
									if(${substr($srr,$i,1)}[$k]==${substr($srr,$j,1)}[$k]){
										goto random1_cids;
									}
								}
							}
						}
					}
					
					//製作互評者字串
					for($k=0;$k<count($a);$k++){
						$s_ids2s = "";
						for($i=1;$i<$n;$i++){
							if($s_ids2s==""){
								$s_ids2s = ${substr($srr,$i,1)}[$k];
							}
							else{
								$s_ids2s = $s_ids2s."-".${substr($srr,$i,1)}[$k];
							}
						}
						$s_ids2[$k] = $s_ids2s;
					}
					
					for($k=0;$k<count($a);$k++){
						$sql_sids = "INSERT INTO pg (s_id,c_id,m_id,pg_member,pg_pas) VALUES ('".$a[$k]."','".$row_re["c_id"]."','".$m_id."','".$s_ids2[$k]."','".$pas."')";
						$result_sids = mysql_query($sql_sids,$pa);
						if(!$result_sids)die("執行SQL命令失敗_sids");
					}
					

					//列出隨機指派結果

					//以c_id,m_id，從pg取出學生學號,評審對象
					$sql_sp = "SELECT s_id,pg_member FROM pg WHERE c_id='".$row_re["c_id"]."' AND m_id='".$m_id."'";
					$result_sp = mysql_query($sql_sp,$pa);
					if(!$result_sp)die("執行SQL命令失敗_sp");
					
					echo "<tr><th COLSPAN=\"".($n*2)."\">".$row_re["c_class"]."指派結果</th></tr>";
					echo "<tr><th>座號</th><th>姓名</th><td COLSPAN=\"".(($n-1)*2)."\" align=\"center\">評審對象</td></tr>";
					
					while($row_sp = mysql_fetch_assoc($result_sp)){
						//以s_id，從stu取s_classnums,s_name
						$sql_sp2 = "SELECT s_classnums,s_name FROM stu WHERE s_id='".$row_sp["s_id"]."'";
						$result_sp2 = mysql_query($sql_sp2,$pa);
						if(!$result_sp2)die("執行SQL命令失敗_sp2");
						$row_sp2 = mysql_fetch_assoc($result_sp2);
						echo "<th align=\"right\">".$row_sp2["s_classnums"]."</th><th>".$row_sp2["s_name"]."</th>";
						
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
					echo "<tr><td COLSPAN=\"".($n*2+1)."\"> </td></tr>";
				}
			}
			//全部指派(尚未指派的班級)
			else if(strpos($c_id, "-")){
				$allc_id = explode("-",$c_id);
				for($l=0;$l<count($allc_id);$l++){
					
					$sql_re = "SELECT c_class FROM class WHERE c_id='".$allc_id[$l]."'";
					$result_re = mysql_query($sql_re,$pa);
					if(!$result_re)die("執行SQL命令失敗_re");
					$row_re = mysql_fetch_assoc($result_re);
			
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$allc_id[$l]."' ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					
					$srr="abcdefghijklmnopqrstuvwxyz";

					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$a[] = $row_si["s_id"]; //學生s_id數列
					}				
					
					//有n個對象，就產生n個數列(從 $s_id0 開始, $s_id1, $s_id2,...)
					for($i=1;$i<$n;$i++){
						random1_cida:
						//隨機排列學生座號
						${substr($srr,$i,1)} = $a;
						shuffle(${substr($srr,$i,1)});

						//目前來到第$i個數列，要比對$i個數列
						for($j=0;$j<$i;$j++){
							//進到數列裡比對
							for($k=0;$k<count($a);$k++){
								if($i!=$j){
									if(${substr($srr,$i,1)}[$k]==${substr($srr,$j,1)}[$k]){
										goto random1_cida;
									}
								}
							}
						}
					}
					
					//製作互評者字串
					for($k=0;$k<count($a);$k++){
						$s_ids2s = "";
						for($i=1;$i<$n;$i++){
							if($s_ids2s==""){
								$s_ids2s = ${substr($srr,$i,1)}[$k];
							}
							else{
								$s_ids2s = $s_ids2s."-".${substr($srr,$i,1)}[$k];
							}
						}
						$s_ids2[$k] = $s_ids2s;
					}
					
					for($k=0;$k<count($a);$k++){
						$sql_sids = "INSERT INTO pg (s_id,c_id,m_id,pg_member,pg_pas) VALUES ('".$a[$k]."','".$allc_id[$l]."','".$m_id."','".$s_ids2[$k]."','".$pas."')";
						$result_sids = mysql_query($sql_sids,$pa);
						if(!$result_sids)die("執行SQL命令失敗_sids");
					}
					unset($a);
					for($i=1;$i<$n;$i++){
						unset(${substr($srr,$i,1)});
					}
					unset($s_ids2);
					
					//列出隨機指派結果

					//以c_id,m_id，從pg取出學生學號,評審對象
					$sql_sp = "SELECT s_id,pg_member FROM pg WHERE c_id='".$allc_id[$l]."' AND m_id='".$m_id."'";
					$result_sp = mysql_query($sql_sp,$pa);
					if(!$result_sp)die("執行SQL命令失敗_sp");
					
					echo "<tr><th COLSPAN=\"".($n*2)."\">".$row_re["c_class"]."指派結果</th></tr>";
					echo "<tr><th>座號</th><th>姓名</th><td COLSPAN=\"".(($n-1)*2)."\" align=\"center\">評審對象</td></tr>";
					
					while($row_sp = mysql_fetch_assoc($result_sp)){
						//以s_id，從stu取s_classnums,s_name
						$sql_sp2 = "SELECT s_classnums,s_name FROM stu WHERE s_id='".$row_sp["s_id"]."'";
						$result_sp2 = mysql_query($sql_sp2,$pa);
						if(!$result_sp2)die("執行SQL命令失敗_sp2");
						$row_sp2 = mysql_fetch_assoc($result_sp2);
						echo "<th align=\"right\">".$row_sp2["s_classnums"]."</th><th>".$row_sp2["s_name"]."</th>";
						
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
					echo "<tr><td COLSPAN=\"".($n*2+1)."\"> </td></tr>";
				}
			}
			//指派or取消單一班級
			else{
				if($_GET["act"]=="a"){
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$a[] = $row_si["s_id"]; //學生s_id數列
						$s_classnums[] = $row_si["s_classnums"];
						$s_name[] = $row_si["s_name"];
					}
					$srr="abcdefghijklmnopqrstuvwxyz";
					//有n個對象，就產生n個數列(從 $s_id0 開始, $s_id1, $s_id2,...)
					for($i=1;$i<$n;$i++){
						random1:
						//隨機排列學生座號
						${substr($srr,$i,1)} = $a;
						shuffle(${substr($srr,$i,1)});

						//目前來到第$i個數列，要比對$i個數列
						for($j=0;$j<$i;$j++){
							//進到數列裡比對
							for($k=0;$k<count($a);$k++){
								if($i!=$j){
									if(${substr($srr,$i,1)}[$k]==${substr($srr,$j,1)}[$k]){
										goto random1;
									}
								}
							}
						}
					}
					
					/*
					//列出隨機指派結果
					for($i=0;$i<$n;$i++){
						echo "<tr><td>".substr($srr,$i,1)."</td>";
						for($k=0;$k<count($a);$k++){
							echo "<td>".${substr($srr,$i,1)}[$k]."<td>";
						}
						echo "</tr>";
					}
					*/
					
					//製作互評者字串
					for($k=0;$k<count($a);$k++){
						$s_ids2s = "";
						for($i=1;$i<$n;$i++){
							if($s_ids2s==""){
								$s_ids2s = ${substr($srr,$i,1)}[$k];
							}
							else{
								$s_ids2s = $s_ids2s."-".${substr($srr,$i,1)}[$k];
							}
						}
						$s_ids2[$k] = $s_ids2s;
					}
					
					for($k=0;$k<count($a);$k++){
						$sql_sids = "INSERT INTO pg (s_id,c_id,m_id,pg_member,pg_pas) VALUES ('".$a[$k]."','".$c_id."','".$m_id."','".$s_ids2[$k]."','".$pas."')";
						$result_sids = mysql_query($sql_sids,$pa);
						if(!$result_sids)die("執行SQL命令失敗_sids");
					}
					
					
					
			
					//列出隨機指派結果

						//以c_id,m_id，從pg取出學生學號,評審對象
						$sql_sp = "SELECT s_id,pg_member FROM pg WHERE c_id='".$c_id."' AND m_id='".$m_id."'";
						$result_sp = mysql_query($sql_sp,$pa);
						if(!$result_sp)die("執行SQL命令失敗_sp");
					
						echo "<tr><th COLSPAN=\"".($n*2)."\">".$c_name."指派結果</th></tr>";
						echo "<tr><th>座號</th><th>姓名</th><td COLSPAN=\"".(($n-1)*2)."\" align=\"center\">評審對象</td></tr>";
					
						while($row_sp = mysql_fetch_assoc($result_sp)){
							//以s_id，從stu取s_classnums,s_name
							$sql_sp2 = "SELECT s_classnums,s_name FROM stu WHERE s_id='".$row_sp["s_id"]."'";
							$result_sp2 = mysql_query($sql_sp2,$pa);
							if(!$result_sp2)die("執行SQL命令失敗_sp2");
							$row_sp2 = mysql_fetch_assoc($result_sp2);
							echo "<th align=\"right\">".$row_sp2["s_classnums"]."</th><th>".$row_sp2["s_name"]."</th>";
						
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
						echo "<tr><td COLSPAN=\"".($n*2+1)."\"> </td></tr>";
				}
				elseif($_GET["act"]=="c"){
					//刪除指派紀錄(班級-任務)
					$sql_del = "DELETE FROM pg WHERE c_id='".$c_id."' AND m_id='".$m_id."'";
					$result_del = mysql_query($sql_del,$pa);
					if(!$result_del)die("執行SQL命令失敗_delc");	
				}
			}
	
			

			echo "<tr>";				
			echo "<td></td>";
			echo "<td></td>";
			echo "</tr>";
		

?>
        </table>

      </form>
	
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
				value="繼續指派互評"
				title=""
				onMouseOver="goLite(this.form.name,this.name)"
				onMouseOut="goDim(this.form.name,this.name)"
				onClick="self.location='rpg.php?n=<?php echo $n;?>'">
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
	else{
		echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
	}
}
?>