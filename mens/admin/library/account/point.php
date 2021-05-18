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

$table = "sales";

$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : ($_POST['pay_date2'] ? $_POST['pay_date2'] : date("2014-02-28"));
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : date("Y-m-d");

$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date2']." +1day"));


// データの仮削除------------------------------------------------------------------------
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND  sales.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  sales.pay_date<='".$_POST['pay_date2']."'";

if($_POST['shop_id']) $dWhere .= " AND  sales.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND sales.loan_status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM ".$table. " WHERE point<>0 and del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE point<>0 and del_flg = 0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT sales.*,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel FROM " . $table . ",customer WHERE sales.point<>0 and  sales.customer_id=customer.id AND sales.del_flg = 0".$dWhere." ORDER BY sales.pay_date DESC,sales.id DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();

//staff list
$staff_list = getDatalistMens("staff","-",$where_shop);

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>