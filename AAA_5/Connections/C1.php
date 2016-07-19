<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_C1 = "localhost";
$database_C1 = "datos";
$username_C1 = "root";
$password_C1 = "xx";
$C1 = mysql_pconnect($hostname_C1, $username_C1, $password_C1) or die(mysql_error());
?>