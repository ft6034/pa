<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
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

//1.如果有GET m_id就顯示該任務的資料
//2.如果有POST m_id就更新任務資料
//3.如果都沒有，就顯示任務清單
//4.刪除任務
$status = 0;
if(isset($_GET["mid"])){
	if(isset($_GET["del"])){
		$status = '4';
		$sql = "DELETE FROM mission WHERE m_id='".$_GET["mid"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗3");
		else{			
			echo "<script language='javascript'>";
			echo "  alert('任務刪除成功！');";
			echo "	document.location.href='mdmission.php';";
			echo "</script>";
		}
	}
	else{
		$status = '1';
		//取任務資料
		$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND m_id='".$_GET["mid"]."' AND t_id='".$_SESSION["t_id"]."' ORDER BY m_order";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		$num_rows = mysql_num_rows($result);
		$row = mysql_fetch_assoc($result);
		$m_id = $row["m_id"];
		$m_name = $row["m_name"];
		$m_desc = $row["m_desc"];
		$m_date = $row["m_date"];
		$m_start = $row["m_start"];
		$m_stop = $row["m_stop"];
		$m_wtype = $row["m_wtype"];
		//$syear = $row["m_syear"];
		$m_grade = $row["m_grade"];
		$m_order = $row["m_order"];
		$m_proportion  = $row["m_proportion"];
	}
}

else if(isset($_POST["m_id"])){
	$status = '2';
	$sql = "UPDATE mission SET m_name='".$_POST["m_name"]."', m_desc='".$_POST["m_desc"]."', m_grade='".$_POST["m_grade"]."', syear='".$_POST["syear"]."', m_order='".$_POST["m_order"]."', m_proportion='".$_POST["m_proportion"]."', m_wtype='".$_POST["m_wtype"]."' WHERE m_id='".$_POST["m_id"]."'";		
	$result = mysql_query($sql,$pa);
	if(!$result)die("執行SQL命令失敗3");
	else{			
		echo "<script language='javascript'>";
		echo "  alert('任務修改成功！');";
		echo "	document.location.href='mdmission.php';";
		echo "</script>";
	}
}
else{
	$status = '3';
}
?>
<html>
<head>
<title>修改任務</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style2.css" rel="stylesheet" type="text/css">
</head>
<body>
<script type='text/javascript'>//<![CDATA[ 

function ValidateNumber(e, pnumber)
    {
        if (!/^\d+$/.test(pnumber))
        {
            var newValue =/^\d+/.exec(e.value);         
            if (newValue != null)         
            {             
                e.value = newValue;        
            }      
            else     
            {          
                e.value = "";    
            } 
        }
        return false;
    }

function ValidateFloat(e, pnumber)
{
    if (!/^\d+[.]?\d*$/.test(pnumber))
    {
        var newValue = /^\d+[.]?\d*/.exec(e.value);         
        if (newValue != null)         
        {             
            e.value = newValue;        
        }      
        else     
        {          
            e.value = "";    
        } 
    }
    return false;
}


function ValidateFloat2(e, pnumber)
{
    if (!/^\d+[.]?[1-9]?$/.test(pnumber))
    {
        var newValue = /\d+[.]?[1-9]?/.exec(e.value);         
        if (newValue != null)         
        {             
            e.value = newValue;        
        }      
        else     
        {          
            e.value = "";    
        } 
    }
    
    return false;
}

//]]>  

</script>
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
	<table class="outtable">
	<tr>
		<td colspan="2" class="menu">
		[<a href="index.php">回首頁</a>]
		[<a href="logout.php">登出系統</a>]
		</td>
	</tr>
	<tr>
		<td colspan="2" class="header">[網路同儕互評系統]</td>
	</tr>
	<tr>
		<td class="title">修改任務</td>
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
	
        <form action="mdmission.php" method="post" enctype="multipart/form-data">
        <table width="900">
<?php
	if($status=='3')	{
		echo "<tr align='center'><th>任務編號</th><th width='100'>任務名稱</th><th>任務說明</th><th>計分比重</th><th>適用年級</th><th>學年學期</th><td></td></tr>";
		//取任務清單
		$sql = "SELECT * FROM mission WHERE syear='".$syear."' AND t_id='".$_SESSION["t_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗2");
		//取任務數量，來調整空白列的數量
		$num_rows = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td class='td-solid'>".$row["m_order"]."</td>";
			//echo "<td>".$row["m_id"]."</td>";
			echo "<td class='td-solid'>".$row["m_name"]."</td>";
			echo "<td class='td-solid'><p align='left'>".$row["m_desc"]."</p></td>";
			//echo "<td>".$row["m_date"]."</td>";
			//echo "<td>".$row["m_start"]."</td>";
			//echo "<td>".$row["m_stop"]."</td>";
			//echo "<td>".$row["m_status"]."</td>";
			echo "<td class='td-solid'>".$row["m_proportion"]."</td>";
			echo "<td class='td-solid'>".$row["m_grade"]."</td>";
			echo "<td class='td-solid'>".$row["syear"]."</td>";
			echo "<td class='td-solid'><input type=\"button\" value=\"修改\" onClick=\"self.location='mdmission.php?mid=".$row["m_id"]."'\"></td>";
			echo "<td class='td-solid'><input type=\"button\" value=\"刪除\" onClick=\"self.location='mdmission.php?mid=".$row["m_id"]."&del=go'\"></td>";
			echo "</tr>";
		}
	}
	if($status=='1')	{
		echo '
			<input type="hidden" name="m_id" value="'.$m_id.'">
			<tr>
				<td>任務編號：</td><td><input type="text" name="m_order" value="'.$m_order.'"></td>
			</tr>
			<tr>
				<td>任務名稱：</td><td><input type="text" name="m_name" value="'.$m_name.'"></td>
			</tr>
			<tr>
				<td>任務說明：</td><td><textarea cols=60 rows=4 name="m_desc">'.$m_desc.'</textarea></td>
			</tr>
			<tr>
				<td>適用年級：</td><td><select name="m_grade" size="1" selected="'.$m_grade.'">
										<option value="6">六年級
										<option value="5">五年級
										<option value="4">四年級
										<option value="3">三年級
										<option value="t">測試班
									</select></td>
			</tr>
			<tr>
				<td>計分比重：</td><td><input type="text" name="m_proportion"  style="ime-mode:disabled" onkeyup="return ValidateFloat2(this,value)"  value="'.$m_proportion.'"/></td>
			</tr>
			<tr>
				<td>學年學期：</td><td><input type="text" name="syear" value="'.$syear.'"></td>
			</tr>
			<tr>
				<td>作業檔案格式：</td><td><input type="text" name="m_wtype" value="'.$m_wtype.'">　例如PDF檔案：<font color="red">.pdf</font></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td></td><td><input type="submit" name="Submit" value="確定修改"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			';
	}
?>
        </table>
        <hr>
        
        <p align="center">
			<input type="button" name="button" value="回上一頁" onClick="window.history.back();">
        </p>
      </form>

	<!-- 內容區 end-->
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