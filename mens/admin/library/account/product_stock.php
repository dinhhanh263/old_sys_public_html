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

$table = "product_stock";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date']." +1day"));

if($authority_level <=1 && !isset($_POST['use_status'])){
	$_POST['use_status'] = 1;
}

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

if($_POST['shop_id']) $dWhere .= " AND  ps.shop_id='".$_POST['shop_id'] ."'";
// if( $_POST['status'] ) $dWhere .= " AND ps.status = '".addslashes($_POST['status'])."'";

if( $_POST['use_status']) $dWhere .= " AND ps.use_status = '".addslashes($_POST['use_status'])."'";//商品名
if($_POST['product_no']) $dWhere .= " AND ps.product_no = '".addslashes($_POST['product_no'])."'";//商品名

if( $_POST['customer_id']){
	$dWhere .= " AND ps.customer_id='".$_POST['customer_id'] ."'";
}else{
	$dWhere .= " AND  ps.pay_date>='".$_POST['pay_date']."'";
	$dWhere .= " AND  ps.pay_date<='".$_POST['pay_date2']."'";
}

// データの仮削除------------------------------------------------------------------------
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	//更新日の設定
	$_POST['edit_date'] = date("Y-m-d H:i:s");
	//削除金額の計算
	$price_array = Get_Table_Array_Multi($table,"product_count,price"," where del_flg=0 and id=".$_GET['id']);
	$price = $price_array[0]['product_count']*$price_array[0]['price'];
	//複数購入有無の確認
	$test_array = Get_Table_Array($table,"id"," WHERE del_flg=0 and sales_id = '".addslashes($_GET['sales_id'])."'");
	//sales_table入金額の取得
	$sales_price = Get_Table_Array_Multi("sales","option_price,option_card"," where id='".addslashes($_GET['sales_id'])."'");
	$sales_id = addslashes($_GET['sales_id']);
	//削除以外の購入品
	$product_leftover = array_filter($test_array,function($k){
		return $k !== $_GET['id'];
	});

	//product_stockの更新
	$sql2 = "UPDATE ".$table." SET del_flg = 1, edit_date='".$_POST['edit_date']."'";
	$sql2 .= " WHERE id = '".addslashes($_GET['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql2) or die('query error'.$GLOBALS['mysqldb']->error);

	//salesテーブルの更新
	if(count($test_array)==1){//同時購入なしの場合、salesは論理削除
		$sql1 = "UPDATE sales SET del_flg = 1, edit_date='".$_POST['edit_date']."'";
		$sql1 .= " WHERE id = '".$sales_id."'";
		$dRes = $GLOBALS['mysqldb']->query($sql1) or die('query error'.$GLOBALS['mysqldb']->error);
	}else{ //同時購入ありの場合、salesはupdate
		if($sales_price[0]['option_price'] > 0){// 入金が現金だったら
			$_POST['option_price'] = $sales_price[0]['option_price'] - $price;
			$sales_field3  = array("stock_id","option_price","edit_date");
		}else if($sales_price[0]['option_card'] > 0){// 入金がカードだったら
			$_POST['option_card'] = $sales_price[0]['option_card'] - $price;
			$sales_field3  = array("stock_id","option_card","edit_date");
		}
		$_POST['stock_id'] = implode(",", $product_leftover);
		$sales_id = Update_Data("sales",$sales_field3,$sales_id);
	}

	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

if($_REQUEST['mode']=="display" || $authority_shop['id'] || $_POST['customer_id']){

	// データの取得------------------------------------------------------------------------

	$dSql = "SELECT count(*) FROM ".$table. " ps WHERE del_flg = 0";
	$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	$dAll_Cnt = $dRtn1->fetch_row();

	$dSql = "SELECT count(ps.id) FROM " . $table . " ps,customer c WHERE ps.del_flg = 0".$dWhere;
	$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	$dGet_Cnt = $dRtn2->fetch_row()[0];

	$dSql  = "SELECT ps.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel ";
	$dSql .= "FROM " . $table . " ps,customer c WHERE ps.customer_id=c.id AND c.del_flg=0 AND ps.del_flg = 0".$dWhere;

	$dSql .= " ORDER BY ps.sales_id,ps.pay_date,ps.reg_date ";
	$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

}

// 店舗リスト------------------------------------------------------------------------
// $shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
// $shop_list[0] = "全店舗";
// while ( $result = $shop_sql->fetch_assoc() ) {
// 	$shop_list[$result['id']] = $result['name'];
// }
$shop_list = getDatalist_shop("全店舗");

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['contract_date']."') ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// 商品リスト---------------------------------------------------------------------------------
$product_sql = $GLOBALS['mysqldb']->query( "select * from product WHERE status = 2 AND del_flg = 0" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $product_sql->fetch_assoc() ) {
	$product_list[$result['id']] = $result['name'];
	//税抜
	// $product_pretax[] = $result['base_price'];
	//税込
	// $product_price[] = round(($result['base_price'] * $tax2),0);
	$product_name[] = $result['name'];
}


?>