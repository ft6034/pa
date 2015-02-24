<?php
session_start();
//random peer group to class


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
		
		
			//指派or取消單一班級

				if($_GET["act"]=="a"){

					echo "<tr><th COLSPAN=\"".($n*2+1)."\">".$c_name."指派結果</th>";
					echo "</tr>";
			
					//以c_id，從stu取s_id
					$sql_si = "SELECT s_id,s_classnums,s_name FROM stu WHERE c_id='".$c_id."' ORDER BY CAST(s_classnums AS UNSIGNED)";
					$result_si = mysql_query($sql_si,$pa);
					if(!$result_si)die("執行SQL命令失敗_si");
					//建立學生名單array
					while($row_si = mysql_fetch_assoc($result_si)){
						$a[] = $row_si["s_id"];
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
					
					for($i=0;$i<$n;$i++){
						echo "<tr><td>".substr($srr,$i,1)."</td>";
						for($k=0;$k<count($a);$k++){
							echo "<td>".${substr($srr,$i,1)}[$k]."<td>";
						}
						echo "</tr>";
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
