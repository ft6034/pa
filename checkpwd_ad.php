<?php
session_start();
?>
<html>
<head>
<title>確認密碼</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>
<?php
//如果已經登入，移動到首頁
if(isset($_SESSION["admin_id"])) {
	header("location:admin.php");
}

if (empty($_POST['admin_id'])||empty($_POST['admin_pass']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=login_ad.php">';
}
else {
	//取得表單資料
	$admin_id = mysql_real_escape_string($_POST['admin_id']);
	$admin_pass = mysql_real_escape_string($_POST['admin_pass']);
}

//建立資料連接
require_once('Connections/pasql.php');

//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫1");

//檢查帳密是否符合
$sql = "SELECT * FROM system where adminid='".$admin_id."'";
$result = mysql_query($sql,$pa);
if(!$result)die("執行SQL命令失敗");
$row = mysql_fetch_assoc($result);
echo $sql;
//如果查無帳號
if(mysql_num_rows($result)==0) {
	mysql_free_result($result);
	mysql_close($pa);
	echo "<script LANGUAGE=\"javascript\">";
	echo "alert('帳號密碼錯誤，請查明後再登入！');";
	echo "history.back();";
	echo "</script>";   
}

//有此帳號
else {
	//帳密符合
	if($admin_pass==$row["adminpass"]){
		$sql0 = "SELECT * FROM system WHERE id = '1'";
		$result0 = mysql_query($sql0,$pa);
		if(!$result0)die("執行SQL命令失敗");
		$row0 = mysql_fetch_assoc($result0);
		$syear = $row0["syear"];
		
		$_SESSION["ssyear"] = $syear;
		$_SESSION["admin_id"] = $row["adminid"];
		echo '<meta http-equiv="Refresh" CONTENT="0; url=./admin.php">';
		echo "<script LANGUAGE='javascript'>";
		echo "alert('歡迎".$_SESSION["admin_id"]."登入！');";
		echo "</script>";  
		header("location:admin.php");
	}
	else{
		echo "<script LANGUAGE=\"javascript\">";
		echo "alert('帳號密碼錯誤，請查明後再登入！');";
		echo "history.back();";
		echo "</script>";   
	}

 
}
?>
</body>
</html>