<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

//権限分け表示
$authority = Get_Table_Row("customer"," WHERE del_no = '".addslashes($_SESSION['code'])."' and password='".addslashes($_SESSION['mypass'])."'");
//不正アクセス
if(!$authority['id'] || empty($_SESSION)){
    header("Location: ./");
    exit();
}

?>