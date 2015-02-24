<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_pa = "localhost"; //資料庫IP位址
$database_pa = "database"; //資料庫名稱
$username_pa = "username"; //資料庫管理帳號
$password_pa = "password"; //資料庫管理密碼
$pa = mysql_pconnect($hostname_pa, $username_pa, $password_pa) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query('set names utf8', $pa);
?>