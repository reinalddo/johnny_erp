<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn1 = DBSRVR;
$database_conn1 = DBNAME;
$username_conn1 = DBUSER;
$password_conn1 = DBPASS;
$conn1 = mysql_pconnect($hostname_conn1, $username_conn1, $password_conn1) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
