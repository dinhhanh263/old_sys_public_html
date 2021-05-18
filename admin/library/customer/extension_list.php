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

$_POST['extension_edit_date']=$_POST['extension_edit_date'] ? $_POST['extension_edit_date'] : date("Y-m-d");
$_POST['extension_edit_date2']=$_POST['extension_edit_date2'] ? $_POST['extension_edit_date2'] : ($_POST['extension_edit_date'] ? $_POST['extension_edit_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['extension_edit_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['extension_edit_date']." +1day"));

// データの仮削除-----------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$post_end_date = date("Y-n-j",strtotime($_REQUEST['end_date'] . "- 2year"));
	$sql = "UPDATE ".$table." SET extension_flg=0, extension_edit_date = 0,edit_date='".date('Y-m-d H:i:s')."',end_date='".$post_end_date."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	if( $dRes )	$gMsg = '保証期間延長を取消しました。';
	else		$gMsg = '何も変更ませんでした。';
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
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  t.extension_edit_date>='".$_POST['extension_edit_date']."'";
$dWhere .= " AND  t.extension_edit_date<='".$_POST['extension_edit_date2']."'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  t.shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['status'] ) $dWhere .= " AND t.status = '".addslashes($_POST['status'])."'";

$from = "FROM contract AS t,customer AS c WHERE c.id=t.customer_id AND t.del_flg =0 AND c.del_flg =0 AND t.status =0 AND t.extension_flg=1";

// データの取得-----------------------------------------------------

$dSql = "SELECT count(c.id) ".$from ;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(c.id) ".$from.$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,t.extension_edit_date as extension_edit_date ".$from.$dWhere."order by t.extension_edit_date LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト-----------------------------------------------------

$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);
//courseリスト
$course_list  = getDatalist("course");

?>