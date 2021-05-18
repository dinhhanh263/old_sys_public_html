<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$gStatus = array( 0 => "非公開" , 1 => "公開"  );

$_POST['id'] = $_REQUEST['id'];
$table = "goods";

//adminlog
if(  $_REQUEST['customer_id'] != "" ) {
	$object_customer = Get_Table_Row("customer"," WHERE id = '".($_REQUEST['customer_id'])."'");
}elseif( $_POST['id'] != "" ){
	$object_data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$object_customer = Get_Table_Row("customer"," WHERE id = '".($object_data['customer_id'])."'");	
}

//編集
if( $_POST['action'] == "edit" ) {
	if(!Get_Table_Row("item_brand"," WHERE name = '".addslashes($_POST['goods_name'])."'")){
		$GLOBALS['mysqldb']->query("insert  item_brand set name = '".addslashes($_POST['goods_name'])."'") or die('query error'.$GLOBALS['mysqldb']->error);
	}
	if(!Get_Table_Row("item_kinsei"," WHERE name = '".addslashes($_POST['kinsei'])."'")){
		$GLOBALS['mysqldb']->query("insert  item_kinsei set name = '".addslashes($_POST['kinsei'])."'") or die('query error'.$GLOBALS['mysqldb']->error);
	}

	if($_POST['id'] != ""){
		$data_ID = Input_Update_Data($table) ;
		//adminlog
		$auth_sql .= ",access_type = 4,access_page = '商品編集',object_id = '".$_REQUEST['id']."'";
	}else{
		$data_ID = Input_Data($table) ;
		//adminlog
		$object_data = Get_Table_Row($table," WHERE id = '".addslashes($data_ID)."'");
		$auth_sql .= ",access_type = 3,access_page = '商品新規',object_id = '".$data_ID."'";
	}
	if( $data_ID ) 	header( "Location: ./?customer_id=". $_REQUEST['customer_id']);
	else 			$gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}else $auth_sql .= ",access_type = 2,access_page = '商品詳細',object_id = '".$_REQUEST['id']."'";

//adminlog
if($auth_sql) {
	$auth_sql .= ",object_name = '".$object_customer['customer_name']."'";
	$GLOBALS['mysqldb']->query($auth_sql) or die('query error'.$GLOBALS['mysqldb']->error);
}

// 詳細を取得
if(  $_REQUEST['customer_id'] != "" ) {
	//$data = Get_Table_Row($table," WHERE customer_id = '".addslashes($_REQUEST['customer_id'])."'");
	$data['customer_id'] = $_REQUEST['customer_id'];
	
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($_REQUEST['customer_id'])."'");	
}
elseif( $_POST['id'] != "" ) {
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($data['customer_id'])."'");	
}

if(is_array($customer))	$data['customer_name']=$customer['customer_name'];

$tantou1 = Get_Table_Row("tantou"," WHERE id = '".addslashes($customer['tantou1'])."'");
$tantou2 = Get_Table_Row("tantou"," WHERE id = '".addslashes($customer['tantou2'])."'");

//品名リスト作成
$item_brand = $GLOBALS['mysqldb']->query( "SELECT * FROM item_brand order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list1 = $item_brand->fetch_assoc() ) $item_brand_list[$list1['id']] = $list1['name'];
if(is_array($item_brand_list))$goods_name = implode(";", $item_brand_list);

//金製リスト作成
$item_kinsei = $GLOBALS['mysqldb']->query( "SELECT * FROM item_kinsei order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list2 = $item_kinsei->fetch_assoc() ) $item_kinsei_list[$list2['id']] = $list2['name'];
if(is_array($item_kinsei_list))$kinsei = implode(";", $item_kinsei_list);


//品名リスト作成
$item_landing = $GLOBALS['mysqldb']->query( "SELECT * FROM item_landing order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $item_landing->fetch_assoc() ) $item_landing_list[$list['id']] = $list['name'];
if(is_array($item_landing_list))$landing_name_list = implode(";", $item_landing_list);

if($data['buy_date']=='0000-00-00')$data['buy_date'] = "";

//詳細へ遷移
if( $_POST['mode'] == "go1" ) header( "Location: ../customer/edit.php?id=".$data['customer_id'] );

//対応記録へ遷移
if( $_POST['mode'] == "go2" ) header( "Location: ../customer/ap_history.php?id=".$data['customer_id'] );

//商品へ遷移
if( $_POST['mode'] == "go3" ) header( "Location: ../goods/edit.php?customer_id=".$data['customer_id'] );

//添付ファイル へ遷移
if( $_POST['mode'] == "go4" ) header( "Location: ../customer/file_up.php?id=".$data['customer_id'] );
?>
