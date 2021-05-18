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

$c_table = "contract";
$p_table = "contract_P";

// 詳細を取得----------------------------------------------------------------------------
if( $_GET['customer_id'] != "" )  {
	// 契約コース情報(1回以上 OR 返金保証期間終了後のコース) 「OR 1000 < course_id」の条件を追加 2017/06/06 add by shimada
	$all_contract = Get_Table_Array($c_table,"*"," WHERE del_flg=0 and (1 < times OR 1000 < course_id) and customer_id = '".addslashes($_GET['customer_id'])."'");
	//$data = Get_Table_Row($c_table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_GET['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($_GET['shop_id'])."'");
	
	// 契約IDごとのレジ情報
	foreach ($all_contract as $key => $value) {
		if($all_contract['sales_id'] != 0 ) $sales[$value['id']] = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($value['sales_id'])."'");
	}
		
}

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト
$course_list  = getDatalistMens("course");

?>
