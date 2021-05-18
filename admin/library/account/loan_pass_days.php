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

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : ($_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-01"));
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : date("Y-m-d");

$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date2']." +1day"));

// 表示ページ設定------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------
/*
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
if( $_POST['status'] ) $dWhere .= " AND t.loan_status = '".addslashes($_POST['status'])."'";
*/
// データの取得------------------------------------------------------------------------
$dFromWhere = "
 FROM customer c,sales s,contract t left join loan_company l on l.del_flg=0 AND l.id=t.loan_company_id
 WHERE c.id=t.customer_id
 AND t.id=s.contract_id
 AND c.del_flg=0
 AND t.del_flg=0
 AND s.del_flg=0
 AND t.payment_loan>0
 AND t.payment_loan=s.payment_loan
 AND t.loan_status=3
 AND t.status=0
 ORDER BY datediff(current_date(),s.pay_date) DESC
";

$dSql = "SELECT count(s.id) ".$dFromWhere.$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.id '顧客ID',c.no '会員番号',c.name '名前',c.name_kana '名前カナ',t.id,t.status '契約状況',t.loan_status 'ローン状況',l.name 'ローン会社',t.contract_date '契約日',s.pay_date 'ローン申込日',s.payment_loan 'ローン申込金額',datediff(current_date(),s.pay_date) '経過日数',s.type,t.conversion_flg ".$dFromWhere.$dWhere." LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company");

?>
