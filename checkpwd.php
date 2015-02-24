<?php
ob_start();
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type="text/javascript" src="jquery-1.0.popwin.js"></script>
<script type="text/javascript">
$(function() {
	$("#btn01").popwin({
		element: "#box01",
		title: "請填寫以下訊息"
	});
	
	$("#btn02").popwin({
		element: "#box02",
		title: "請登入"
	});

})
</script>

<?php
if (empty($_POST['s_id'])||empty($_POST['s_pass']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=login.php">';
}
else {
	//取得表單資料
	$s_id = mysql_real_escape_string($_POST['s_id']);
	$s_pass = mysql_real_escape_string($_POST['s_pass']);
}

//建立資料連接
require_once('Connections/pasql.php');

//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫1");

//檢查帳密是否符合
$sql = "SELECT * FROM stu where s_id='$s_id'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗");

//如果查無帳號
if(mysql_num_rows($result)==0) {
	mysql_free_result($result);
	mysql_close($pa);
	echo "<script LANGUAGE=\"javascript\">";
	echo "alert(\"帳號密碼錯誤，請查明後再登入！\");";
	echo "history.back();";
	echo "</script>";   
}

//有此帳號
else {

	
	$sql = "SELECT * FROM stu where s_id='$s_id'";
    $result = mysql_query($sql,$pa);
    if(!$result)die("執行SQL命令失敗");
	$row = mysql_fetch_assoc($result);
	
	//帳密符合
	if($s_pass==$row["s_pass"]){
        
		//分解年級
		//$s_grade = substr($row["s_class"],0,1);
		
		$db_selected = mysql_select_db($database_pa, $pa);
		if(!$db_selected)die("無法開啟資料庫2");
		$sql0 = "SELECT * FROM system WHERE id = '1'";
		$result0 = mysql_query($sql0,$pa);
		if(!$result0)die("執行SQL命令失敗");
		$row0 = mysql_fetch_assoc($result0);
		$syear = $row0["syear"];
		
		$_SESSION["ssyear"] = $syear;
		//$_SESSION["ss_id"] = $s_id;
		setcookie("s_id",$s_id);
		//$_SESSION["s_grade"] = $s_grade;
		echo '<meta http-equiv="Refresh" CONTENT="0; url=index.php">';
	}
	else{
		echo "<script LANGUAGE=\"javascript\">";
		echo "alert(\"帳號密碼錯誤，請查明後再登入！\");";
		echo "history.back();";
		echo "</script>"; 
	}
	
}
?>