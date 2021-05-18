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

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d");
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date']." +1day"));

// データ変更-----------------------------------------------------

if( $_REQUEST['action'] == "ok" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE customer SET bank_ng_flg = 0,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);


	if( $dRes )	$gMsg = 'データの変更が完了しました。';
	else		$gMsg = '何も変更しませんでした。';
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

$from = "FROM contract AS c,customer AS cs WHERE cs.id=c.customer_id AND c.del_flg =0 AND cs.del_flg =0";
if($_POST['bank_ng_flg']) $from .= " AND cs.bank_ng_flg =".$_POST['bank_ng_flg'];
else $from .= " AND cs.bank_ng_flg >0";

// データの取得-----------------------------------------------------

$dSql = "SELECT count(cs.id) ".$from ;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(cs.id) ".$from.$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.*,cs.no as no,cs.name as name,cs.name_kana as name_kana,cs.tel as tel,cs.bank_ng_flg,cs.reg_date as reg_date ".$from.$dWhere." order by c.contract_date LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト-----------------------------------------------------

$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

//courseリスト
$course_list  = getDatalist("course");

?>