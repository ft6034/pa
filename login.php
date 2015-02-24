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

$now_time = date("Y年m月d日 H:i",time()+(8*60*60));

if(isset($_SESSION["t_id"])) {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./teacher.php">';
}
if($_COOKIE["s_id"]!="") {
	echo '<meta http-equiv="Refresh" CONTENT="0; url=./stu/stu.php">';
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>首頁-來自：<?php echo $_SERVER[REMOTE_ADDR];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css">

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

	<table class="outtable">
	<tr>
		<td colspan="2" class="header">網路同儕互評系統</td>
	</tr>
	<tr>
		<td class="title">
		登入系統
		</td>
		<td class="function">&nbsp;</td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr></td>
	</tr>
	</table>
	
	</td>
	<td background="./img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="./img/bg-l.png" width="60"></td>
	<td align="center">
	
	
	
	<form name="login_form" id="login_form" method="post" action="checkpwd.php" target="_top">
</br>
<table width='463' border='1' bordercolor="#CC0099" cellspacing="1">
  <td colspan="2" align="center" bgcolor="#CC0099"><b><font color="#FFFFFF"> 登 入 系 統 </b><br>
  
  <?php echo $now_time;?></font>
  </td>
<tr height="70" bordercolor="#FFFFFF">
	<td width="135">
		<div align="right">
			<p><font color="#005DBE">帳號：</p>
			密碼：</font>
		</div>
	</td>
  	<td width="324">
		<p><input name="s_id" type="text" id="s_id_id" maxlength="6" placeholder="請輸入學號（６個字）...">
		學生學號，共６碼</p>
		<p><input name="s_pass" type="password" id="s_pass" maxlength="20" placeholder="請輸入密碼...">
		預設為生日，共４碼</p>
	</td>
   	
</tr>
</table></br>
    <input type="submit" name="Submit" value="確定">
    <input type="reset" name="reset" value="重填">
	<input type="button" value="回首頁" onClick="parent.location='index.php'">
	<a href="index.php">查詢帳號</a>
  </p>
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
