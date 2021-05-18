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

$_POST['application_date']=$_POST['application_date'] ? $_POST['application_date'] : ($_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-01"));
$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-d");

$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : ($_POST['application_date'] ? $_POST['application_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['application_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['application_date2']." +1day"));


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
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
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

$dWhere .= " AND  t.loan_application_date>='".$_POST['application_date']."'";
$dWhere .= " AND  t.loan_application_date<='".$_POST['application_date2']."'";

if($_POST['shop_id']) $dWhere .= " AND  t.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND t.loan_status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(t.id) FROM " . $table . " t,customer c WHERE t.status = 0 and payment_loan<>0 and t.customer_id=c.id AND c.del_flg = 0 AND t.del_flg = 0".$dWhere." ORDER BY t.loan_application_date DESC,t.id DESC ";
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.contract_send as contract_send FROM " . $table . " t,customer c WHERE t.status = 0 and payment_loan<>0 and t.customer_id=c.id AND c.del_flg = 0 AND t.del_flg = 0".$dWhere." ORDER BY t.loan_application_date DESC,t.id DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

$staff_list = getDatalist("staff");

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company");

// 検索パラメータ------------------------------------------------------------------------
$param = '&keyword='.$_POST['keyword'].
		 '&shop_id='.$_POST['shop_id'].
		 '&status='.$_POST['status'].
		 '&line_max='.$_POST['line_max'].
		 '&start='.$dStart;

