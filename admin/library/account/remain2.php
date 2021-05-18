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
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "contract";

$_POST['latest_date']=$_POST['latest_date'] ? $_POST['latest_date'] : date("Y-m-d");
$_POST['latest_date2']=$_POST['latest_date2'] ? $_POST['latest_date2'] : ($_POST['latest_date'] ? $_POST['latest_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['latest_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['latest_date']." +1day"));


// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
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
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND  contract.latest_date>='".$_POST['latest_date']."'";
$dWhere .= " AND  contract.latest_date<='".$_POST['latest_date2']."'";
if($_POST['shop_id']) $dWhere .= " AND  contract.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND contract.status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

//契約中以外の消化も考慮
$dSql = "SELECT contract.*,customer.id as customer_id,customer.no as no,customer.name as name,customer.name_kana as name_kana,customer.tel as tel FROM " . $table . ",customer WHERE contract.customer_id=customer.id AND customer.del_flg = 0 and contract.r_times<>0 AND contract.del_flg = 0".$dWhere." ORDER BY contract.customer_id,contract.latest_date";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "全店舗";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//staff list
$staff_list = getDatalist("staff","-",$where_shop);

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_times[$result['id']] = $result['times'];
}

?>