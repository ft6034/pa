<?php
if (empty($_COOKIE['s_id']))	{
	echo '<meta http-equiv="Refresh" CONTENT="0; url=../index.php">';
}
?>

<html>
<head>

<title>登入</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style2.css" rel="stylesheet" type="text/css"></head>
<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="white">

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
	
	
	</table>
	<!-- 標題區 end-->
	
	</td>
	<td background="../img/bg-r.png" width="60"></td>
</tr>

<tr>
	<td background="../img/bg-l.png" width="60"></td>
	<td align="center">
	
	<!-- 內容區 start-->
	
<div align="center">
<form name="chpass_form" method="post" action="dochpass.php" target="_self">
</br>
<table width='463' border='1' bordercolor="#CC0000" cellspacing="1">
  <td colspan="2" align="center" bgcolor="#CC000"><b><font color="#FFFFFF"> 修 改 密 碼 </font></b></td>
<tr height="70" bordercolor="#FFFFFF">
	<td width="135">
		<div align="right">
			<p>原密碼：</p>
			<p>新密碼：</p>
			<p>再輸入一次<br>新密碼：</p>
		</div>
	</td>
  	<td width="324">
		<p><input name="s_pass" type="password" id="s_pass" maxlength="20"></p>
		<p><input name="s_newpass" type="password" id="s_newpass" maxlength="20"></p>
		<p><input name="s_newpass2" type="password" id="s_newpass2" maxlength="20"></p>
	</td>
   	
</tr>
</table></br>
    <input type="submit" name="Submit" value="確定">
    <input type="reset" name="Submit2" value="重填">　
  </p>
</form>
</div>
<p>&nbsp;</p>
	
	
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
