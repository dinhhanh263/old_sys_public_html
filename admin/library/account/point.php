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

$dWhere .= " AND  s.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  s.pay_date<='".$_POST['pay_date2']."'";

if($_POST['shop_id']) $dWhere .= " AND  s.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['status'] ) $dWhere .= " AND s.loan_status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM ".$table. " WHERE point<>0 and del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(s.id) FROM " . $table . " s,customer c WHERE s.point<>0 and  s.customer_id=c.id AND s.del_flg = 0".$dWhere." ORDER BY s.pay_date DESC,s.id DESC ";
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT s.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel FROM " . $table . " s,customer c WHERE s.point<>0 and  s.customer_id=c.id AND s.del_flg = 0".$dWhere." ORDER BY s.pay_date DESC,s.id DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['pay_date']."') ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>