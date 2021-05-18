<?php //var_dump($_POST);
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
    session_start();
    header('Expires:-1');
    header('Cache-Control:');
    header('Pragma:');
include_once( "../config/config.php" );
include_once( "../lib/db.php" );
include_once( "../lib/function.php" );
include_once( "../lib/common_ad.php" );
include_once( "../lib/tag.php" );



// reservation action:change,cancel
if( $_REQUEST['act']) $_SESSION['ACT'] = $_REQUEST['act'];
else unset($_SESSION['ACT']);

if($_POST['mode']=="send"){ //申し込み重なりがあり再度申込みを行う（customer_id未発行で再申し込み）
  $data= $_POST;
}

// 予約変更
if(is_numeric($_REQUEST['id']) && is_numeric($_REQUEST['rid']) && strlen($_REQUEST['rid'])<8 ){
	$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and id=".$_REQUEST['rid']." and customer_id=".$_REQUEST['id'] );
	if($rsv['id']){
	    $data = Get_Table_Row("customer"," WHERE del_flg=0 and id=" .$_REQUEST['id'] );
	}
/*/ }elseif(is_numeric($_REQUEST['id']) && (!isset($_REQUEST['rid']) || strlen($_REQUEST['rid'])==8 ) ){
}elseif(is_numeric($_REQUEST['id']) && ( strlen($_REQUEST['rid'])==8 ) ){
	$data= Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_REQUEST['id'])."'");*/
}

// 予約キャンセル
if($_REQUEST['id'] && $_REQUEST['act']=="cancel") header( "Location: ./cancel.html?id=".$_REQUEST['id']."&rid=".$_REQUEST['rid'] );

//予約変更へ影響があり
/*if(isset($_POST)) {
	$data = $_POST;

}*/

// 誕生日(年)のselect作成
$shop_list = makeShopList();
$pref_list = makePrefList();

// 端末切り替え用パラメーター
if($_REQUEST['id'] && $_REQUEST['rid'] && $_REQUEST['act'] ){
	$location_param= "?id=".$_GET['id']."&rid=".$_GET['rid']."&act=".$_GET['act'];
}else{
	$location_param= "";
}
?>
