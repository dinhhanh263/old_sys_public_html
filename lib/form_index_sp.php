<?php
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//session_save_path("../../tmp");
session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "../../lib/common_ad.php" );
include_once( "../../lib/tag_sp.php" );

// reservation action:change,cancel
if( $_REQUEST['act']) $_SESSION['ACT'] =  $_REQUEST['act'];
else unset($_SESSION['ACT']);

if($_POST['mode']=="send"){ //申し込み重なりがあり再度申込みを行う（customer_id未発行で再申し込み）
  $data= $_POST;
}
//if($_REQUEST['id']) $data= Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_REQUEST['id'])."'");

// 予約変更
if(is_numeric($_REQUEST['id']) && is_numeric($_REQUEST['rid']) && strlen($_REQUEST['rid'])<8 ){
	$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] );
	if($rsv['id']){
	    $data = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
      $data['persons'] = $rsv['persons'];
      $data['hope_time'] = $rsv['hope_time'];
	}
/*/}elseif(is_numeric($_REQUEST['id']) && (!isset($_REQUEST['rid']) || strlen($_REQUEST['rid'])==8 ) ){
}elseif(is_numeric($_REQUEST['id']) && ( strlen($_REQUEST['rid'])==8 ) ){
	$data= Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_REQUEST['id'])."'");*/
}

// 予約キャンセル
if($_REQUEST['id'] && $_REQUEST['act']=="cancel") header( "Location: ./cancel.html?id=".$_REQUEST['id']."&rid=".$_REQUEST['rid'] );


$shop_list = makeShopList();
$pref_list = makePrefList();

?>
