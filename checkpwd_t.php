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
if(isset($_SESSION["t_id"])) {
	header("location:teacher.php");
}

if (empty($_POST['t_id'])||empty($_POST['t_pass']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=login_t.php">';
}
else {
	//取得表單資料
	$t_id = mysql_real_escape_string($_POST['t_id']);
	$t_pass = mysql_real_escape_string($_POST['t_pass']);
}

//建立資料連接
require_once('Connections/pasql.php');

//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫1");

//檢查帳密是否符合
$sql = "SELECT * FROM teacher where t_id='".$t_id."'";
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
	if($t_pass==$row["t_pass"]){
		$sql0 = "SELECT * FROM system WHERE id = '1'";
		$result0 = mysql_query($sql0,$pa);
		if(!$result0)die("執行SQL命令失敗");
		$row0 = mysql_fetch_assoc($result0);
		$syear = $row0["syear"];
		
		$_SESSION["ssyear"] = $syear;
		$_SESSION["t_id"] = $row["t_id"];
		echo '<meta http-equiv="Refresh" CONTENT="0; url=./teacher.php">';
		echo "<script LANGUAGE='javascript'>";
		echo "alert('歡迎".$_SESSION["t_id"]."登入！');";
		echo "</script>";  
		header("location:teacher.php");
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