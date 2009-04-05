<?php

$mysql_ini = parse_ini_file("mysql.ini");

$mysqli = new mysqli($mysql_ini["host"], $mysql_ini["username"], $mysql_ini["password"], $mysql_ini["dbname"]);

?>