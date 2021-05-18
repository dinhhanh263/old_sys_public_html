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
$post_date1 = $_POST['date1'];
$post_date2 = $_POST['date2'];

$date1 = $post_date1 ? $post_date1 : ($post_date2 ? $post_date2 : "2018-01-01");
$date2 = $post_date2 ? $post_date2 : ($post_date1 ? $post_date1 : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($date2." -1day"));
$next_date = date("Y-m-d", strtotime($date2." +1day"));

// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" ){
	// 依頼事項データ仮削除
	$sql = "UPDATE request_items SET del_flg = 1, edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['request_id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);

	header("Location: ./cc_request.php?keyword=".$_POST['keyword']);
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
	$dWhere .= " REPLACE(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR REPLACE(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND SUBSTRING(r.reg_date,1,10)>='".$date1."' AND SUBSTRING(r.reg_date,1,10)<='".$date2."'";
// if($_POST['shop_id']) $dWhere .= " AND r.shop_id='".$_POST['shop_id'] ."'";
if($_POST['cc_request'])  $dWhere .= " AND r.status = '".addslashes($_POST['cc_request'])."'";
if($_POST['cc_type'])  $dWhere .= " AND r.type = '".addslashes($_POST['cc_type'])."'";
if($_POST['search_process_status']){
	$search_process_status = ($_POST['search_process_status']==9) ? 0 : $_POST['search_process_status'];
	$dWhere .= " AND r.process_status = '".addslashes($search_process_status)."'";
}
if($_POST['search_loan_respond']){
	$search_loan_respond = ($_POST['search_loan_respond']==9) ? 0 : $_POST['search_loan_respond'];
	$dWhere .= " AND r.loan_respond = '".addslashes($search_loan_respond)."'";
}
// 通常処理完了の非表示
if($_POST['search_process_status']<>3 && $_POST['keyword'] == "" && $_POST['request_id'] == ""){
	$dWhere .= " AND r.end_flg = 0";
}
// ローン会社依頼状況
if($_POST['search_loan_request_status']){
	$dWhere .= " AND r.loan_request_status = '".addslashes($_POST['search_loan_request_status'] )."'";
}
// 登録完了後のリダイレクト時
if ($_POST['request_id']) {
	$dWhere .= " AND r.id = '".addslashes($_POST['request_id'])."'";
}
// 店舗アカウントログイン時の表示
if ($authority_level==17) {
	$dWhere .= " AND r.type = 2";
}
// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(r.id) FROM request_items r,customer c
		 WHERE c.id=r.customer_id AND c.del_flg=0 AND r.del_flg=0
		".$dWhere." ORDER BY r.reg_date";

$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT c.id,c.no,c.name,c.birthday,c.tel,c.name_kana,c.attorney_status,
				b.id bid,b.bank_name,b.bank_branch,b.bank_account_type,b.bank_account_no,b.bank_account_name,
				t.id contract_id,t.status,t.pay_type,t.contract_date,t.end_date,t.course_id,t.loan_company_id,
				t.old_contract_id,t.fixed_price,t.discount,t.payment_loan,t.balance,t.sales_id,t.times,t.r_times,t.loan_status,t.start_ym,
				r.id request_id,r.reg_date,r.status cc_request,r.type,r.shop_id,r.last_visit_ym,r.transfer_date,r.transfer_commission,
				s.type course_type 
		 FROM customer c 
		 LEFT JOIN bank b ON c.id=b.customer_id AND b.del_flg=0,
		 	  request_items r 
		 LEFT JOIN contract t ON t.id=r.contract_id AND t.del_flg=0
		 LEFT JOIN course s ON s.id=t.course_id AND s.del_flg=0
		 WHERE c.id=r.customer_id AND c.del_flg=0 AND r.del_flg=0
		".$dWhere." ORDER BY r.reg_date DESC LIMIT ".$dStart.",".$dLine_Max;

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");
// $shop_lists = getDatalistArray3("shop","area", $gShops_priority);
$course_list = getDatalist("course");
$loan_company_list = getDatalist("loan_company");

$param = '&keyword='.$_POST['keyword'].
		 '&cc_request='.$_POST['cc_request'].
		 '&cc_type='.$_POST['cc_type'].
		 '&search_process_status='.$_POST['search_process_status'].
		 '&search_loan_respond='.$_POST['search_loan_respond'].
		 '&line_max='.$_POST['line_max'].
		 '&start='.$dStart;
