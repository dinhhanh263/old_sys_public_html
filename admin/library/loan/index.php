<?php
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "loan_info";

$_POST['application_date'] = $_POST['application_date']  ? $_POST['application_date']  : ($_POST['application_date2'] ? $_POST['application_date2'] : date("2016-09-01"));
$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-d");

$pre_date  = date("Y-m-d", strtotime($_POST['application_date2']." -1day"));
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
	$dWhere .= "  replace(l.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(l.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or l.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(l.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or l.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND l.application_date>='".$_POST['application_date']."'";
$dWhere .= " AND l.application_date<='".$_POST['application_date2']."'";

// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row()[0];

$dSql = "SELECT count(l.id) FROM " . $table . " l LEFT JOIN customer c ON c.id=l.customer_id AND l.customer_id<>0 AND c.del_flg=0 WHERE l.del_flg=0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT l.*,l.no cno,c.name cname,c.name_kana cname_kana,c.birthday cbirthday,c.tel ctel,c.mail cmail,t.course_id,t.times,t.price,t.payment_loan,t.loan_recept_no,t.balance
		 FROM " . $table . " l
		 LEFT JOIN customer c ON c.id=l.customer_id AND l.customer_id<>0 AND c.del_flg=0
		 LEFT JOIN contract t ON t.id=l.contract_id AND l.contract_id<>0 AND t.del_flg=0
		 WHERE l.del_flg=0 ".$dWhere."
		 ORDER BY l.application_date DESC,id DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// 検索パラメータ------------------------------------------------------------------------
$param = '&keyword='.$_POST['keyword'].
		 '&contract_status='.$_POST['contract_status'].
		 '&support_status='.$_POST['support_status'].
		 '&cor_request='.$_POST['cor_request'].
		 '&line_max='.$_POST['line_max'].
		 '&start='.$dStart;