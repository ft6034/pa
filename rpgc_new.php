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

	if($m_id!=""){
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
		//判斷是否已選擇班級
		if($c_id!=""){
		
			//是否為全部重新指派
			if($c_id=="allreset"){
				$sql_re = "SELECT m2c.c_id,class.c_class FROM m2c,class WHERE m2c.m_id='".$m_id."' AND m2c.c_id=class.c_id";
				$result_re = mysql_query($sql_re,$pa);
				if(!$result_re)die("執行SQL命令失敗_re");
				while($row_re = mysql_fetch_assoc($result_re)){
					
					
					//刪除所有紀錄(班級-任務)
					$sql_del = "DELETE FROM pg WHERE c_id='".$row_re["c_id"]."' AND m_id='".$m_id."'";
					$result_del = mysql_query($sql_del,$pa);
					if(!$result_del)die("執行SQL命令失敗_del");					
					
					
					//echo "<tr><th>座號</th><th>姓名</th>";
					echo "<tr><th COLSPAN=\"".($n*2+1)."\">".$row_re["c_class"]."分組名單</th>";
					echo "</tr>";
			
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$row_re["c_id"]."'	 ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					
					unset($s_classnums);
					unset($s_name);
					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$s_classnums[] = $row_si["s_classnums"];
						$s_name[] = $row_si["s_name"];
					}
				
					//隨機排列學生座號
					shuffle($s_classnums);
			
					$i = 0;
					//取座號
					foreach ($s_classnums as $index => $value) {
						if(($i%$n)==0){
							echo "<tr><td>第".(($i/$n)+1)."組</td>";
						}
						echo "<td>".$value."</td>"; 
				
						//取姓名,學號
						$sql_sn = "SELECT s_id,s_name FROM stu WHERE c_id='".$row_re["c_id"]."' AND s_classnums=".$value;
						$result_sn = mysql_query($sql_sn,$pa);
						if(!$result_sn)die("執行SQL命令失敗_sn");
						while($row_sn = mysql_fetch_assoc($result_sn)){
							echo "<td>".$row_sn["s_name"]."</td>";
							
							//將小組學號組合成字串
							if($s_ids==""){
								$s_ids = $row_sn["s_id"];
							}
							else{
								$s_ids = $s_ids."-".$row_sn["s_id"];
							}
							
						}
						if($i%$n==$n-1){
							echo "</tr>";
						}
						$i += 1;
						
						if($i%$n==0 || $i==count($s_classnums)){

							//新增隨機互評紀錄
							//echo $s_ids;
							if($s_ids!=""){
								//$sql_sids = "INSERT INTO pg (c_id,m_id,pg_member) VALUES ('".$row_re["c_id"]."','".$m_id."','".$s_ids."')";
								//echo $sql_sids;
								
								//2013.02.16
								$alls_id = explode("-",$s_ids);		
								//小組人數不足
								if(count($alls_id)<$n){
									//補足人數
									$n_add = $n - count($alls_id);
									//取$n_add個學號來補，
									for($q=0;$q<$n_add;$q++){
										$sql_sn_q = "SELECT s_id,s_name FROM stu WHERE c_id='".$row_re["c_id"]."' AND s_classnums=".$s_classnums[$q];
										$result_sn_q = mysql_query($sql_sn_q,$pa);
										if(!$result_sn_q)die("執行SQL命令失敗_sn_q");
										$row_sn_q = mysql_fetch_assoc($result_sn_q);
										

											if($s_ids_add==""){
												$s_ids_add = $row_sn_q["s_id"];
											}
											else{
												$s_ids_add = $s_ids_add."-".$row_sn_q["s_id"];
											}
										
									}
									
									//寫入結果
									for($k=0;$k<count($alls_id);$k++){
										for($l=0;$l<count($alls_id);$l++){
											if($alls_id[$l]!=$alls_id[$k]){
												if($s_ids2==""){
													$s_ids2 = $alls_id[$l];
												}
												else{
													$s_ids2 = $s_ids2."-".$alls_id[$l];
												}
											}
											$s_ids2 = $s_ids2."-".$s_ids_add;
										}
										$sql_sids = "INSERT INTO pg (s_id,c_id,m_id,pg_member) VALUES ('".$alls_id[$k]."','".$row_re["c_id"]."','".$m_id."','".$s_ids2."')";
										$result_sids = mysql_query($sql_sids,$pa);
										if(!$result_sids)die("執行SQL命令失敗_sids");
										$s_ids2="";
									}	
								}
								else{
									for($k=0;$k<count($alls_id);$k++){
										for($l=0;$l<count($alls_id);$l++){
											if($alls_id[$l]!=$alls_id[$k]){
												if($s_ids2==""){
													$s_ids2 = $alls_id[$l];
												}
												else{
													$s_ids2 = $s_ids2."-".$alls_id[$l];
												}
											}
										}
										$sql_sids = "INSERT INTO pg (s_id,c_id,m_id,pg_member) VALUES ('".$alls_id[$k]."','".$row_re["c_id"]."','".$m_id."','".$s_ids2."')";
										$result_sids = mysql_query($sql_sids,$pa);
										if(!$result_sids)die("執行SQL命令失敗_sids");
										$s_ids2="";
									)
								}
								
								
								$s_ids="";
							}

						}
					}
					
				}
			}
			elseif(strpos($c_id, "-")){
				$allc_id = explode("-",$c_id);
				for($k=0;$k<count($allc_id);$k++){	
					
					$sql_re = "SELECT c_class FROM class WHERE c_id='".$allc_id[$k]."'";
					$result_re = mysql_query($sql_re,$pa);
					if(!$result_re)die("執行SQL命令失敗_re");
					
					while($row_re = mysql_fetch_assoc($result_re))
					//echo "<tr><th>座號</th><th>姓名</th>";
					echo "<tr><th COLSPAN=\"".($n*2+1)."\">".$row_re["c_class"]."分組名單</th>";
					echo "</tr>";
			
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$allc_id[$k]."' ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					
					unset($s_classnums);
					unset($s_name);
					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$s_classnums[] = $row_si["s_classnums"];
						$s_name[] = $row_si["s_name"];
					}
				
					//隨機排列學生座號
					shuffle($s_classnums);
			
					$i = 0;
					//取座號
					foreach ($s_classnums as $index => $value) {
						if($i%$n==0){
							echo "<tr><td>第".(($i/$n)+1)."組";
							//echo ;
							echo "</td>";
						}
						echo "<td>".$value."</td>"; 
				
						//取姓名
						$sql_sn = "SELECT s_id,s_name FROM stu WHERE c_id='".$allc_id[$k]."' AND s_classnums=".$value;
						$result_sn = mysql_query($sql_sn,$pa);
						if(!$result_sn)die("執行SQL命令失敗_sn");
						while($row_sn = mysql_fetch_assoc($result_sn)){
							echo "<td>".$row_sn["s_name"]."</td>";
							
							//將小組學號組合成字串
							if($s_ids==""){
								$s_ids = $row_sn["s_id"];
							}
							else{
								$s_ids = $s_ids."-".$row_sn["s_id"];
							}
							
						}
						if($i%$n==$n-1){
							echo "</tr>";
						}
						$i += 1;
						
						if($i%$n==0 || $i==count($s_classnums)){

							//新增隨機互評紀錄
							//echo $s_ids;
							if($s_ids!=""){
								$sql_sids = "INSERT INTO pg (c_id,m_id,pg_member) VALUES ('".$allc_id[$k]."','".$m_id."','".$s_ids."')";
								//echo $sql_sids;
								$result_sids = mysql_query($sql_sids,$pa);
								if(!$result_sids)die("執行SQL命令失敗_sids");
								$s_ids="";
							}

						}
					}
					
				}
			}
			else{
				//echo "<tr><th>座號</th><th>姓名</th>";
				echo "<tr><th COLSPAN=\"".($n*2+1)."\">".$c_name."分組名單</th>";
				echo "</tr>";
			
				//以c_id，從stu取s_id
				$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
				$result_si = mysql_query($sql_si,$pa);
				if(!$result_si)die("執行SQL命令失敗_si");
				//建立學生名單array
				while($row_si = mysql_fetch_assoc($result_si)){
					$s_classnums[] = $row_si["s_classnums"];
					$s_name[] = $row_si["s_name"];
				}
				
				//隨機排列學生座號
				shuffle($s_classnums);
			
				$i = 0;
				//取座號
				foreach ($s_classnums as $index => $value) {
				
					if($i%$n==0){
						echo "<tr><td>第".(($i/$n)+1)."組";
						//echo ;
						echo "</td>";
					}
					echo "<td>".$value."</td>"; 
				
					//取姓名
					$sql_sn = "SELECT s_id,s_name FROM stu WHERE c_id='".$c_id."' AND s_classnums=".$value;
					$result_sn = mysql_query($sql_sn,$pa);
					if(!$result_sn)die("執行SQL命令失敗_sn");
					while($row_sn = mysql_fetch_assoc($result_sn)){
						echo "<td>".$row_sn["s_name"]."</td>";
						
							//將小組學號組合成字串
							if($s_ids==""){
								$s_ids = $row_sn["s_id"];
							}
							else{
								$s_ids = $s_ids."-".$row_sn["s_id"];
							}
							
					}
					if($i%$n==$n-1){
						echo "</tr>";
					}
					$i += 1;
					
					if($i%$n==0 || $i==count($s_classnums)){

							//新增隨機互評紀錄
							//echo $s_ids;
							if($s_ids!=""){
								$sql_sids = "INSERT INTO pg (c_id,m_id,pg_member) VALUES ('".$c_id."','".$m_id."','".$s_ids."')";
								//echo $sql_sids;
								$result_sids = mysql_query($sql_sids,$pa);
								if(!$result_sids)die("執行SQL命令失敗_sids");
								$s_ids="";
							}

						}
						
				}
			}
	
			

			echo "<tr>";				
			echo "<td></td>";
			echo "<td></td>";
			echo "</tr>";
		}

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