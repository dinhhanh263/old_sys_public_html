<?php
/*
if(!($cn = mysqli_connect( KIREIMO_HOST_NAME, KIREIMO_DB_UESR, KIREIMO_DB_PW ))) die("Could not connect: ".$GLOBALS['mysqldb']->error);
if(!($GLOBALS['mysqldb']->select_db(KIREIMO_DB_NAME))) die("DB:Could not connect: ".$GLOBALS['mysqldb']->error);
$GLOBALS['mysqldb']->query('SET NAMES utf8');
*/

$mysqldb = new mysqli(KIREIMO_HOST_NAME, KIREIMO_DB_UESR, KIREIMO_DB_PW, KIREIMO_DB_NAME);

?>
