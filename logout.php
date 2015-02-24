<?php
session_start();
	//清除session內容
	session_unset();
	//清除cookie內容
	setcookie("s_id", "");
	setcookie("m_id", "");
	//將使用者導回主網頁
	echo header("location:index.php");
?>