<html>
<head>
<title>作品上傳</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="./style.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>
<body bgcolor="white">
<?php 
date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");
$now_time2 = date("H:i:s");

//開啟資料庫
require_once("./Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT syear FROM system WHERE id='1'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗1");
$row = mysql_fetch_assoc($result);
$syear = $row["syear"];

$update = "";

//上傳檔案
if ( isset($_FILES["up_work"]) ){
	if ( strtolower(strrchr($_FILES["up_work"]["name"], ".") != ".csv" ) ){ //檢查上傳檔案的格式是否正確
		echo "<script language='javascript'>";
		echo "  alert('檔案格式錯誤，請使用csv檔案格式');";
		echo "  history.back();";
		echo "</script>";
	}
	else{
		if($_FILES["up_work"]!="")	{
			//$src_file = $_FILES["up_work"]["tmp_name"];
			$desc = "stu";
			$src_ext = strtolower(strrchr($_FILES["up_work"]["name"], "."));
		  
			//加入判斷src_ext是哪一種類型
		  
			$desc_file_name = $desc.$src_ext;
			if(move_uploaded_file($_FILES["up_work"]["tmp_name"], "./upload/".$desc_file_name)) {
		
				$update = "check";
			
			} else {
				echo "檔案上傳失敗!";
				echo "<script language='javascript'>";
				echo "  alert('上傳失敗，無法建立檔案!');";
				//echo "  history.back();";
				echo "</script>";
			}
		
		}
		else{
			echo "<script language='javascript'>";
			echo "  alert('上傳失敗1!');";
			//echo "  history.back();";
			echo "</script>";
		}
	}
}

?>

<br><br>
<center>
<table border="0" cellpadding="0" cellspacing="0" >

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
		<td class="title">學生資料批次匯入</td>
		<td>&nbsp;</td>
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

if($update == "check"){
	
	echo "<p><font color='red'><b>請確認以下資料，若無誤請按 [確定匯入]</b> </br>若出現亂碼，請修正檔案為utf-8編碼</font></br></p>";
	echo "<b>學年學期：".$syear."</b></br>";
	echo "<form action='mkgstus.php' method='post' enctype='multipart/form-data'>
	<input type='hidden' name='update' value='y'>
	<table>
	<tr><th>學號</th><th>生日4碼</th><th>姓名</th><th>性別</th><th>班級</th><th>座號</th></tr>";

    setlocale(LC_ALL, "zh_TW.UTF8");//設定存取語系
    $dbname="./upload/stu.csv";//欲讀取的csv檔案    
    if (!$fp = fopen($dbname,"r")){ //開檔判斷
        echo "Cannot open $dbname"; //檔案無法開啟
        exit;
    }else{
        $size = filesize($dbname)+1;
        $row=0;
        while($temp=fgetcsv($fp,$size,",")){
            if ($row>0){
                echo "<tr align='center'><td>".$temp[0]."</td><td>".$temp[1]."</td><td>".$temp[2]."</td><td>".$temp[3]."</td><td>".$temp[4]."</td><td>".$temp[5]."</td></tr>";
            }
                $row=$row+1;
        }
        fclose($fp);//關閉檔案
    }
	echo "</table>";
	echo '<input type="submit" name="Submit" value="確定匯入"></form>';
	echo '<input type="button" name="button" value="重新上傳" onClick="self.parent.location=\'mkgstus.php\'">';
}

elseif(isset($_POST["update"])){ //確認檔案無誤
	//開始新增or更新學生資料	
	setlocale(LC_ALL, "zh_TW.UTF8");//設定存取語系
    $dbname="./upload/stu.csv";//欲讀取的csv檔案    
    if (!$fp = fopen($dbname,"r")){ //開檔判斷
        echo "Cannot open $dbname"; //檔案無法開啟
        exit;
    }else{
        $size = filesize($dbname)+1;
        $row=0;
        while($temp=fgetcsv($fp,$size,",")){
            if ($row>0){
				//取 c_id
				$sql = "SELECT c_id FROM class WHERE c_class='".$temp[4]."' AND syear='".$syear."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗_cid");
				//若c_id不存在, 移轉到新增班級頁
				if(mysql_num_rows($result)==0){
					/*
					//創建一個新班級
					$sql = "INSERT INTO class (c_class, syear) VALUES('".$temp[4]."', '".$syear."')";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_newcid");
					//取 c_id
					$sql = "SELECT c_id FROM class WHERE c_class='".$temp[4]."' AND syear='".$syear."'";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_cid2");
					*/
					echo "<script language='javascript'>";
					echo "  alert('該班級不存在，請先新增此班級！');";
					echo "document.location.href='mkclass.php';";
					echo "</script>";
				}
				$row_cid = mysql_fetch_assoc($result);
				$c_id = $row_cid["c_id"];
				
				//判斷該學號是否已存在
				$sql = "SELECT s_id FROM stu WHERE s_id='".$temp[0]."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗_sid");
				if(mysql_num_rows($result)==0){ //學號不存在
					//新增學生
					$sql = "INSERT INTO stu (s_id,s_pass,s_name,s_sex,c_id, s_classnums) Values ";
					$sql .= "('".$temp[0]."','".$temp[1]."','".$temp[2]."','".$temp[3]."','".$c_id."','".$temp[5]."');";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_newstu");
				}
				else{ //學號已存在
					//更新學生資料
					$sql = "UPDATE stu SET c_id='".$c_id."', s_classnums='".$temp[5]."' WHERE s_id='".$temp[0]."'";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_updatestu");
				}
				
				//判斷s2c對照資料是否已存在, s_id & syear
				$sql = "SELECT s2c_id FROM s2c WHERE s_id='".$temp[0]."' AND syear='".$syear."'";
				$result = mysql_query($sql,$pa);
				if(!$result)die("執行SQL命令失敗_sid");
				if(mysql_num_rows($result)==0){ //s2c不存在, s_id & syear
					//新增s2c的紀錄
					$sql = "INSERT INTO s2c(syear, s_id, c_id, s_classnums)";
					$sql .= " VALUES('".$syear."', '".$temp[0]."', '".$c_id."', '".$temp[5]."')";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_s2c");
				}
				else{ //s2c存在, s_id & syear
					//判斷s2c對照資料是否已存在, s_id & syear & c_id
					$sql = "SELECT s2c_id FROM s2c WHERE s_id='".$temp[0]."' AND c_id='".$c_id."' AND syear='".$syear."'";
					$result = mysql_query($sql,$pa);
					if(!$result)die("執行SQL命令失敗_sid");
					if(mysql_num_rows($result)==0){ //s2c存在, 但 c_id 不同
						//更新 c_id
						$sql = "UPDATE s2c SET c_id='".$c_id."' WHERE s_id='".$temp[0]."' AND syear='".$syear."'";
						$result = mysql_query($sql,$pa);
						if(!$result)die("執行SQL命令失敗_cidupdate");
					}
				}
            }
                $row=$row+1;
        }
        fclose($fp);//關閉檔案
    }
	echo "<script language='javascript'>";
	echo "  alert('學生資料匯入完成！');";
	echo "document.location.href='admin.php';";
	echo "</script>";
}
else{

	echo '	
	
      <form action="mkgstus.php" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>選擇要上傳的學生資料檔 ：</td>
				<td><input name="up_work" type="file"></td>
				<td>副檔名：<font color="red">csv</font>
				<p align="center"><a href="./samples/stu.csv"> [ <font color="green">下載範例▼</font> ] </a></p>
				</td>
			</tr>
        </table>
        <hr>
        
        <p align="center">
			<input type="submit" name="Submit" value="確定上傳">
        </p>
      </form>
    
	';
}

?>



<input type="button" name="button" value="回首頁" onClick="self.parent.location='./admin.php'">
	
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