<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
include_once LIB_DIR . 'function.php';
include_once LIB_DIR . 'db.php';
include_once LIB_DIR . 'auth.php';


$table = "contract";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("2014-02-28"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));


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

$dWhere .= " AND  t.contract_date>='".$_POST['contract_date']."'";
$dWhere .= " AND  t.contract_date<='".$_POST['contract_date2']."'";

if($_POST['shop_id']) $dWhere .= " AND  t.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";
if( $_POST['course_id'] ) $dWhere .= " AND t.course_id = '".addslashes($_POST['course_id'])."'";

// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM ".$table. " WHERE status=0 and del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row()[0];

$dSql = "SELECT count(t.id) FROM " . $table . " t,customer c WHERE t.status=0 and t.customer_id=c.id and c.del_flg=0 AND t.del_flg = 0".$dWhere." ORDER BY t.contract_date ";
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.adcode as adcode FROM " . $table . " t,customer c WHERE t.status=0 and t.customer_id=c.id and c.del_flg=0 AND t.del_flg = 0".$dWhere." ORDER BY t.contract_date LIMIT ".$dStart.",".$dLine_Max;
//var_dump($dSql );
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//staff list
//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$_POST['contract_date']."') ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list[0] = "全コース";
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

?>