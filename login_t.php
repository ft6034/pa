<?php
session_start();

//僅限校內登入
if(substr ($_SERVER['REMOTE_ADDR'], 0, 7)!="172.17."){
	echo "<script language='javascript'>";
//	echo "  alert('僅開放校內登入!');";
//	echo "  history.back();";
	echo 'document.location.href="http://210.243.29.81/scratch";';
	echo "</script>";
}

if(isset($_SESSION["t_id"])) {
	header("location:teacher.php");
}
$now_time = date("Y.m.d H:i",time()+(8*60*60));

$s_id ="";
if(isset($_COOKIE["s_id"])) {
	$s_id = $_COOKIE["s_id"];
}

 if($s_id!="") {
    echo '<meta http-equiv="Refresh" CONTENT="0; url=./stu/stu.php">';
  }
?>

<html>
<head>

<title>教師登入</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>
<div align="center">
<form name="login_form" method="post" action="checkpwd_t.php" target="_self">
</br></br></br>
<table width='463' border='1' bordercolor="#CC0099" cellspacing="1">
  <td colspan="2" align="center" bgcolor="#CC0099"><b><font color="#FFFFFF"> 登 入 系 統 </font></b><br>
  <?php echo $now_time;?>
  </td>
<tr height="70" bordercolor="#FFFFFF">
	<td width="135">
		<div align="right">
			<p>帳號：</p>
			密碼：
		</div>
	</td>
  	<td width="324">
		<p><input name="t_id" type="text" id="t_account" maxlength="12" autofocus>
		教師帳號</p>
		<p><input name="t_pass" type="password" id="t_pass" maxlength="20">
		預設為生日，共4碼</p>
	</td>
   	
</tr>
</table></br>
    <input type="submit" name="Submit" value="確定">
    <input type="reset" name="reset" value="重填">
	<input type="button" value="回首頁" onClick="self.location='index.php'">
  </p>
</form>
</div>
<p>&nbsp;</p>
</body>
</html>
