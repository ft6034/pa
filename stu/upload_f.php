<html>
<head>
<title>修正後作品上傳</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php

  if(!isset($_COOKIE["s_id"])) {
    echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
  }

date_default_timezone_set('Asia/Taipei');
$now_date = date("Y.m.d");
$now_time = date("H.i.s");
$now_time2 = date("H:i:s");
$m_id = $_GET["mid"];

//開啟資料庫
require_once("../Connections/pasql.php");
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");	
$sql = "SELECT m_name,m_spath FROM mission WHERE m_id='".$m_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗");
$row = mysql_fetch_assoc($result);
$m_name = $row["m_name"];
$f_type = strrchr($row["m_spath"], ".");

//檢查上傳檔案的格式是否正確
if ( $_FILES["up_work"]["name"] !="" && $f_type != strtolower(strrchr($_FILES["up_work"]["name"], ".")) ){
	echo "<script language='javascript'>";
	echo "  alert('檔案格式錯誤，此任務的檔案格式為".$f_type."');";
	echo "  history.back();";
	echo "</script>";
}
else{


$sql_cid = "SELECT class.c_id,class.c_class FROM class,stu WHERE stu.s_id='".$_COOKIE["s_id"]."' AND class.c_id=stu.c_id";
$result_cid = mysql_query($sql_cid,$pa);
if(!$result_cid)die("執行SQL命令失敗_cid");
$row_cid = mysql_fetch_assoc($result_cid);

if(isset($_POST["w_name"])) {
	if($_FILES["up_work"]!="")	{
          //$src_file = $_FILES["up_work"]["tmp_name"];
		//$desc = "學號-日期-時間-任務id";
		$desc = $_COOKIE["s_id"]."-".$now_date."-".$now_time."-".$m_id;
		$src_ext = strtolower(strrchr($_FILES["up_work"]["name"], "."));
		  
		  //加入判斷src_ext是哪一種類型
		  
        $desc_file_name = $desc.$src_ext;
          //$thumbnail_desc_file_name = "./works/$desc_file_name";
          //resize_photo($src_file, $src_ext, $thumbnail_desc_file_name, 200);
		if(move_uploaded_file($_FILES["up_work"]["tmp_name"], "./works/".$row_cid["c_id"]."_".$row_cid["c_class"]."/".$desc_file_name)) {

		//新增rework紀錄
		$sql = "INSERT INTO rework(rew_name, rew_desc, s_id, m_id, rew_date, t_status)";
		$sql .= " VALUES('".$_POST["w_name"]."', './works/".$row_cid["c_id"]."_".$row_cid["c_class"]."/".$desc_file_name."', '".$_COOKIE["s_id"]."', '".$m_id."','".$now_date." ".$now_time2."','0')";

		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗1");
		
		//取t_id
		$sql = "SELECT t_id FROM c2t WHERE c_id='".$row_cid["c_id"]."'";
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗_tid");
		$row = mysql_fetch_assoc($result);
		$t_id = $row["t_id"];
		
		//傳訊息通知教師
		$sql = "INSERT INTO messages (sender, receiver, contents, ms_date, category) VALUES('".$_COOKIE["s_id"]."', '".$t_id."', '".$pg_sid."繳交修正後作品</br><a href=showrework.php?mid=".$m_id."&sid=".$_COOKIE["s_id"].">進行評審</a>','".$now_date." ".$now_time2."', 't5')"; //t5為學生重交作品
		$result = mysql_query($sql,$pa);
		if(!$result)die("執行SQL命令失敗message".$sql);

		echo "<script language='javascript'>";
		echo "  alert('上傳成功!');";
		//echo "document.location.href='show.php?mid=".$m_id."';";
		echo "parent.location.href='showre.php?mid=".$m_id."';";
		echo "</script>";
			
        } else {
			echo "檔案上傳失敗!";
			echo "<script language='javascript'>";
			echo "  alert('上傳失敗2!');";
			echo "  history.back();";
			echo "</script>";
        }
		
    }
	else{
		echo "<script language='javascript'>";
		echo "  alert('上傳失敗1!');";
		echo "  history.back();";
		echo "</script>";
	}
}

/*
  function resize_photo($src_file, $src_ext, $dest_name, $max_size) {
    switch ($src_ext)	{
      case ".jpg":
        $src = imagecreatefromjpeg($src_file);
        break;
      case ".png":
        $src = imagecreatefrompng($src_file);
        break;
      case ".gif":
        $src = imagecreatefromgif($src_file);
        break;
    }
    $src_w = imagesx($src);
    $src_h = imagesy($src);
    if($src_w > $src_h) {
      $thumb_w = $max_size;
      $thumb_h = intval($src_h / $src_w * $thumb_w);
    } else {
      $thumb_h = $max_size;
      $thumb_w = intval($src_w / $src_h * $thumb_h);
    }
    $thumb = imagecreatetruecolor($thumb_w, $thumb_h);
    imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
    imagejpeg($thumb, $dest_name, 100);
    imagedestroy($src);
    imagedestroy($thumb); 
  }
*/
}
?>

<br><br>
<center>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFF2CD">

<tr>
	<td background="../img/bg-lt.png" width="60" height="60"></td>
	<td background="../img/bg-top.png" width="800" height="60">
	</td>
	<td background="../img/bg-rt.png" width="60" height="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">

	<!-- 標題區 start-->
	<table class="outtable">
	<tr>
		<td class="title">修正後作品上傳</td>
		<td class="function">&nbsp;</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	<!-- 標題區 end-->
	
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">
	
	
	<!-- 內容區 start-->

<table class="outtable">
    <tr>
    <td colspan="2" align="center" valign="middle">
      <form action="upload_f.php?mid=<?php echo $m_id;?>" method="post" enctype="multipart/form-data">
        <table width="0">
			<tr>
				<td>任務名稱：</td><td><font color="blue" size="5"><?php echo $m_name;?></font> <input type="hidden" name="w_name" value="<?php echo $m_name;?>"> <!--<input type="text" name="w_name" value="">--></td>
			</tr>
			<tr>
				<td>選擇要繳交檔案 ：</td><td><input name="up_work" type="file"><?php echo " 副檔名：<font color='red'>".$f_type."</font>";?></td>
			</tr>
        </table>
        <hr>
        
        <p align="center">
			<input type="submit" name="Submit" value="確定繳交">
        </p>
      </form>
    </td>
  </tr>
</table>
	
	<!-- 內容區 end-->
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