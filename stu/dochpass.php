<?php
session_start();
if(empty($_COOKIE['s_id'])){
	header("location:../index.php");
	exit();
}
?>
<html>
<head>
<title>修改聯絡人資料</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>
<?php
/*
執行修改密碼
*/

$s_id = $_COOKIE['s_id'];

if (empty($_POST['s_newpass'])||empty($_POST['s_newpass2']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=chapss.php">';
}
else {
	//取得表單資料
	$s_pass =  mysql_real_escape_string($_POST['s_pass']);
	$s_newpass = mysql_real_escape_string($_POST['s_newpass']);
	$s_newpass2 = mysql_real_escape_string($_POST['s_newpass2']);	
}

//建立資料連接
require_once('../Connections/pasql.php');
//開啟資料庫
$db_selected = mysql_select_db($database_pa, $pa);
if(!$db_selected)die("無法開啟資料庫");

if ($s_newpass==$s_newpass2){ //檢查新密碼是否一致
	//檢查原密碼是否符合
	$sql = "SELECT * FROM stu where s_id='$s_id' AND s_pass='$s_pass'";
	$result = mysql_query($sql, $pa) or die(mysql_error());

	//如果原密碼不符合
	if(mysql_num_rows($result)==0) {
		mysql_free_result($result);
		mysql_close($pa);
		echo "<SCRIPT LANGUAGE='javascript'>";
		echo "alert('原密碼錯誤，請查明後再修改！');";
		echo "history.back();";
		echo "</SCRIPT>";
	} else {
		
            //修改密碼
			$sql = "UPDATE stu SET s_pass='$s_newpass' where s_id='$s_id'";
			$result = mysql_query($sql,$pa) or die(mysql_error());
			echo "<SCRIPT LANGUAGE='javascript'>";
			echo "alert('密碼修改成功！');";
			echo "parent.location.href='../index.php';";
			echo "</SCRIPT>";
		
	}
}
else {
	//如果新密碼有問題
	echo "<SCRIPT LANGUAGE='javascript'>";
	echo "alert('新密碼有誤，請重新輸入！');";
	echo "history.back();";
	echo "</SCRIPT>";
}
?>
</body>
</html>
