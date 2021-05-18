<?php 
include_once("../../lib/tag_sp.php");
//session_save_path("../../tmp");
session_start();
include_once("../../lib/common_ad.php");
$rk = '';
if(array_key_exists('rk',$_GET)){
    $rk = $_GET['rk'];
}
?>