<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

$table = "karte_c";



// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	// 顧客編集
	if($_POST['id'] != "" ){
		$_POST['edit_date'] = date("Y-m-d H:i:s");
		$data_ID =  Input_Update_Data($table);
	}else{
		// 顧客新規
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		Input_Data($table);
	}
	if( $data_ID ) 	$gMsg = '記入が完了しました。';
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得
if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and  id = '".addslashes($_POST['id'])."'");
}elseif($_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'");
	
}
$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
//$shop_list[0] = "-";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//	$shop_code[$result['id']] = $result['code'];
//}

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// $mensdb = changedb();

?>
