<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once LIB_DIR . 'auth.php';

$table = "contract";

$_POST['application_date']=$_POST['application_date'] ? $_POST['application_date'] : ($_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-01"));
$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-d");

$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : ($_POST['application_date'] ? $_POST['application_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['application_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['application_date2']." +1day"));

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

$staff_list = getDatalist("staff");

//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}

// ローン会社リスト----------------------------------------------------------------------------
$loan_company_list = getDatalist("loan_company");

// 検索条件の設定-------------------------------------------------------------------
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

// データの取得----------------------------------------------------------------------
$dSql = "SELECT t.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.contract_send as contract_send FROM " . $table . " t,customer c WHERE t.status = 0 and payment_loan<>0 and t.customer_id=c.id AND c.del_flg = 0 AND t.del_flg = 0".$dWhere." ORDER BY t.loan_application_date DESC,t.id DESC ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "loan_list.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("区分,店舗,契約日,ローン申込日,ローン会社,会員番号,顧客氏名,フリガナ,電話番号,購入コース,ローン申込金額,承認状態,担当者\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($gContractStatus[$data['status']],"SJIS-win", "UTF-8") . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['contract_date'] . ",";
		echo $data['loan_application_date'] . ",";
		echo ($data['loan_company_id'] ?  mb_convert_encoding($loan_company_list[$data['loan_company_id']],"SJIS-win", "UTF-8") : '') . ",";
		echo $data['no']. ",";
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['tel'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8") . ",";
		echo $data['payment_loan']. ",";
		echo mb_convert_encoding($gLoanStatus[$data['loan_status']],"SJIS-win", "UTF-8"). ",";
		echo mb_convert_encoding($staff_list[$data['staff_id']],"SJIS-win", "UTF-8"). ",";
		echo "\n";
	}

	//CSV Export Log
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
