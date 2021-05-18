<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

// 表示期間------------------------------------------------------------------------
$post_date1 = filter_input( INPUT_POST, "date1" );
$post_date2 = filter_input( INPUT_POST, "date2" );

$date1 = $post_date1 ? $post_date1 : ($post_date2 ? $post_date2 : "2014-02-28");
$date2 = $post_date2 ? $post_date2 : ($post_date1 ? $post_date1 : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($date2." -1day"));
$next_date = date("Y-m-d", strtotime($date2." +1day"));

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
	$dWhere .= " REPLACE(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND  r.hope_date>='".$date1."' AND r.hope_date<='".$date2."'";
if($_POST['shop_id']) $dWhere .= " AND r.shop_id='".$_POST['shop_id'] ."'";
if($_POST['cc_request'])  $dWhere .= " AND r.cc_request = '".addslashes($_POST['cc_request'])."'";

// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(r.id) FROM reservation r,contract t,customer c
		 WHERE c.id=r.customer_id AND t.id=r.contract_id AND c.del_flg=0 AND r.del_flg=0 AND t.del_flg=0
		 AND r.cc_request<>0 ".$dWhere." ORDER BY r.hope_date";

$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.id,c.no,c.name,c.name_kana,
				t.id contract_id,t.status,t.pay_type,t.contract_date,t.course_id,t.times,t.r_times,t.price,t.fixed_price,t.discount,t.loan_company_id,t.payment_cash,t.payment_card,t.payment_transfer,t.payment_loan,t.balance,

				r.hope_date,r.cc_request,r.memo4
		 FROM reservation r,customer c,contract t
		
		 WHERE c.id=r.customer_id
		   AND t.id=r.contract_id
		   AND c.del_flg=0
		   AND r.del_flg=0
		   AND t.del_flg=0
		   AND r.cc_request<>0".$dWhere." GROUP BY c.id ORDER BY r.hope_date DESC,t.id DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);
$course_list = getDatalist("course");
$loan_company_list = getDatalist("loan_company");
