<?php
/*
if(!($cn = mysql_connect( HOST_NAME, DB_UESR, DB_PW ))) die("Could not connect: ".mysql_error());
if(!(mysql_select_db(DB_NAME))) die("DB:Could not connect: ".mysql_error());
mysql_query('SET NAMES utf8');
*/

$mysqldb = new mysqli(HOST_NAME, DB_UESR, DB_PW, DB_NAME);


?>
