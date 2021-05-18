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

$table = "contract";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d");
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date']." +1day"));

// データの仮削除-----------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定-----------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定-----------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(cs.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(cs.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR cs.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(cs.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR cs.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  c.contract_date>='".$_POST['contract_date']."'";
$dWhere .= " AND  c.contract_date<='".$_POST['contract_date2']."'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  c.shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['status'] ) $dWhere .= " AND c.status = '".addslashes($_POST['status'])."'";

$from = "FROM contract AS c,customer AS cs WHERE cs.id=c.customer_id AND c.del_flg =0 AND cs.del_flg =0 AND c.status =0 AND c.course_id IN ( 1, 5, 15, 16, 18, 19, 44, 45, 46, 47, 48, 49, 50, 51 ) ";

// データの取得-----------------------------------------------------

$dSql = "SELECT count(cs.id) ".$from ;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(cs.id) ".$from.$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.*,cs.no as no,cs.name as name,cs.name_kana as name_kana,cs.tel as tel,cs.reg_date as reg_date ".$from.$dWhere."order by c.contract_date LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト-----------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();

//courseリスト
$course_list  = getDatalistMens("course");

?>