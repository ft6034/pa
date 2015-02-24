<?php
session_start();
if(!isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./index.php">';
}
$now_date = date("Y.m.d");
$now_time = date("H.i.s",time()+(8*60*60));
$now_time2 = date("H:i:s",time()+(8*60*60));

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
//取目前的學年學期
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗0");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

if(isset($_GET["newm"]) && $_GET["newm"]=="y"){
	$newm = "y";
}
else{
	$newm = "n";
}

//有任務名稱，就建立任務
if(isset($_POST["m_name"])){
		require_once("./Connections/pasql.php");
		//開啟資料庫
		$db_selected = mysql_select_db($database_pa, $pa);
		if(!$db_selected)die("無法開啟資料庫");	

	if($_FILES["up_work"]!="")	{
          //$src_file = $_FILES["up_work"]["tmp_name"];
		//$desc = "班級座號-日期-時間-任務id";
		$desc = $_SESSION["t_id"]."-".$now_date."-".$now_time; //檔名
		$src_ext = strtolower(strrchr($_FILES["up_work"]["name"], ".")); //副檔名
		  
		  //加入判斷src_ext是哪一種類型
		  
        $desc_file_name = $desc.$src_ext;
          //$thumbnail_desc_file_name = "./works/$desc_file_name";
          //resize_photo($src_file, $src_ext, $thumbnail_desc_file_name, 200);
		if(move_uploaded_file($_FILES["up_work"]["tmp_name"], "./samples/".$desc_file_name)) {

		/*
		echo "<script language='javascript'>";
		echo "  alert('範例上傳成功!');";
		echo "</script>";
		*/
					
        } else {
			echo "<script language='javascript'>";
			echo "  alert('範例上傳失敗2!');";
			echo "  history.back();";
			echo "</script>";
        }
    }
	else{
		echo "<script language='javascript'>";
		echo "  alert('沒有範例檔案!');";
		echo "  history.back();";
		echo "</script>";
	}

		//建立mission紀錄
		$sql = "INSERT INTO mission(m_name, m_desc, m_grade, syear, t_id, m_spath, m_order,m_date,m_proportion)";
		$sql .= " VALUES('".$_POST["m_name"]."', '".$_POST["m_desc"]."','".$_POST["m_grade"]."', '".$_POST["syear"]."','".$_SESSION["t_id"]."','./samples/".$desc_file_name."','".$_POST["m_order"]."','".$now_date." ".$now_time2."','".$_POST["m_proportion"]."')";

		$result = mysql_query($sql,$pa);
		if(!$result){
			echo "<script language='javascript'>";
			echo "  alert('執行SQL命令失敗1!');";
			echo "  history.back();";
			echo "</script>";
		}
		else{			
			//echo $sql;
			$sql = "SELECT m_id FROM mission WHERE m_name='".$_POST["m_name"]."' AND syear='".$_POST["syear"]."'";
			$result = mysql_query($sql,$pa);
			if(!$result)die("執行SQL命令失敗2");
			$row = mysql_fetch_assoc($result);
			echo "<script language='javascript'>";
			echo "  alert('成功建立任務!');";
			echo "document.location.href='assign.php?newm=y&mid=".$row["m_id"].";";
			echo "</script>";
		}
}
?>
<html>
<head>
<title>新增任務</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
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
		<td class="title">新增任務</td>
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
<?php
	if($newm=="y"){
		$newm_get = "?newm=y";
	}
	else{
		$newm_get = "";
	}
?>
	<form action="mkmission.php<?php echo $newm_get;?>" method="post" enctype="multipart/form-data">
        <table class="outtable2">
			<tr>
				<td>任務編號 ：</td><td><input name="m_order" type="text"
				<?php
					$sql = "SELECT m_order FROM mission WHERE t_id='".$_SESSION["t_id"]."' AND syear='".$syear."' ORDER BY m_order DESC";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗0");
					$row = mysql_fetch_assoc($result);
					echo 'value="'.($row["m_order"]+1).'">';
				?>
				</td>
			</tr>
			<tr>
				<td>任務名稱：</td><td><input type="text" name="m_name" autofocus></td>
			</tr>
			<tr>
				<td>任務說明：</td><td><textarea cols=60 rows=4 name="m_desc"></textarea></td>
			</tr>
			<tr>
				<td>適用年級：</td><td><select name="m_grade" size="1" selected="6">
										<option value="6">六年級
										<option value="5">五年級
										<option value="4">四年級
										<option value="3">三年級
										<option value="t">測試班
									</select></td>
			</tr>
			<tr>
				<td>計分比重：</td><td><input type="text" name="m_proportion"  style="ime-mode:disabled" onkeyup="return ValidateFloat2(this,value)"  value="1"/></td>
			</tr>
			<tr>
				<td>學年學期：</td><td><input type="text" name="syear" value="<?php echo $syear;?>"></td>
			</tr>
			<tr>
				<td>上傳範例 ：</td><td><input name="up_work" type="file"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
        </table>
        <hr>
        
        <p align="center">
			<input type="submit" name="Submit" value="確定新增">
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