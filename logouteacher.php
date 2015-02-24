<?php
	session_start();
	//清除session t_id內容
	unset($_SESSION["t_id"]);
	//將使用者導回主網頁
	echo header("location:index.php");
?>