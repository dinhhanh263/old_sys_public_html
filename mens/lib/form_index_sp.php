<?php 
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//session_save_path("../../tmp");
session_start();

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "../../lib/common_ad.php" );
include_once( "../../lib/tag_sp.php" );

//reservation action:change,cancel
if( $_REQUEST['act']) $_SESSION['ACT'] =  $_REQUEST['act'];
else unset($_SESSION['ACT']);

if($_REQUEST['id'] && $_REQUEST['rid']){
	$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] );
	if($reservation['id']){
	    $data = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
	} 
}
if($_REQUEST['id'] && $_REQUEST['act']=="cancel") header( "Location: ./cancel.html?id=".$_REQUEST['id']."&rid=".$_REQUEST['rid'] );


//if(isset($_POST)) $data = $_POST;
?>
