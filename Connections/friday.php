<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_friday = "localhost";
$database_friday = "friday";
$username_friday = "zz";
$password_friday = "123";
$friday = mysql_pconnect($hostname_friday, $username_friday, $password_friday) or trigger_error(mysql_error(),E_USER_ERROR); 
?>