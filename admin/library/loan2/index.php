<?php
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "loan_info2";

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
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(l.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or l.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND l.application_date>='".$_POST['application_date']."'";
$dWhere .= " AND l.application_date<='".$_POST['application_date2']."'";

// if($_POST['verify_status'])   $dWhere .= " AND l.verify_status='".$_POST['verify_status'] ."'";
if($_POST['contract_status']) $dWhere .= " AND l.contract_status='".$_POST['contract_status'] ."'";
if($_POST['support_status'])  $dWhere .= " AND l.support_status='".$_POST['support_status'] ."'";
if($_POST['cor_request'])     $dWhere .= " AND l.cor_request='".$_POST['cor_request'] ."'";

if($_POST['process_category'] && $_POST['regist_category'] ){
	$dWhere .= " AND (l.process_category in(".Post_To_DB_Cook2($_POST['process_category']) .") OR l.regist_category in(".Post_To_DB_Cook2($_POST['regist_category']) .") )";
}else{
	// 契約終了
	if($_POST['process_category'] ){
		$dWhere .= " AND l.process_category in(".Post_To_DB_Cook2($_POST['process_category']) .")";
	}
	// 受付終了
	if($_POST['regist_category'] ){
		$dWhere .= " AND l.regist_category in(".Post_To_DB_Cook2($_POST['regist_category']) .")";
	}
}

// 同意書リカバー
if($_POST['consent_recovery'] ){
	$dWhere .= " AND l.consent_recovery in(".Post_To_DB_Cook2($_POST['consent_recovery']) .")";
}
// ベリファイ確認状況
if($_POST['verify_status'] ){
	$dWhere .= " AND l.verify_status in(".Post_To_DB_Cook2($_POST['verify_status']) .")";
}
// 経過日数
if($_POST['pass_days'] ){
	// 30日以上
	if(in_array(1,$_POST['pass_days'])){
		$dWhere .= " AND datediff(current_date(),l.application_date)>30";
	// 60日以上
	}else{
		$dWhere .= " AND datediff(current_date(),l.application_date)>60";
	}
}
// 契約番号有無
if($_POST['if_contract_no'] ){
	// 付与済
	if(in_array(2,$_POST['if_contract_no']) && count($_POST['if_contract_no'])==1){
		$dWhere .= " AND l.loan_contract_no<>'' ";
	// 未付与
	}elseif(in_array(1,$_POST['if_contract_no']) && count($_POST['if_contract_no'])==1){
		$dWhere .= " AND l.loan_contract_no='' ";
	}
}
// 契約日有無
if($_POST['if_contract_date'] ){
	// 有
	if(in_array(2,$_POST['if_contract_date']) && count($_POST['if_contract_date'])==1){
		$dWhere .= " AND l.loan_contract_date<>'' AND l.loan_contract_date<>'0000-00-00' ";
	// 無
	}elseif(in_array(1,$_POST['if_contract_date']) && count($_POST['if_contract_date'])==1){
		$dWhere .= " AND (l.loan_contract_date='' OR l.loan_contract_date='0000-00-00') ";
	}
}
// 契約終了日有無
if($_POST['if_contract_end_date'] ){
	// 有
	if(in_array(2,$_POST['if_contract_end_date']) && count($_POST['if_contract_end_date'])==1){
		$dWhere .= " AND l.loan_end_date<>'' AND l.loan_end_date<>'0000-00-00' ";
	// 無
	}elseif(in_array(1,$_POST['if_contract_end_date']) && count($_POST['if_contract_end_date'])==1){
		$dWhere .= " AND (l.loan_end_date='' OR l.loan_end_date='0000-00-00') ";
	}
}
// 支払方法
if($_POST['transfer_status'] ){
	$dWhere .= " AND l.transfer_status in(".Post_To_DB_Cook2($_POST['transfer_status']) .")";
}

// データの取得------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query($dSql) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row()[0];

$dSql = "SELECT count(l.id) FROM " . $table . " l LEFT JOIN customer c ON c.id=l.customer_id AND l.customer_id<>0 AND c.del_flg=0 WHERE l.del_flg=0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query($dSql) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT l.*,c.no cno,t.course_id,t.times,t.price,t.payment_loan,t.loan_recept_no,t.balance
		 FROM " . $table . " l
		 LEFT JOIN customer c ON c.id=l.customer_id AND l.customer_id<>0 AND c.del_flg=0
		 LEFT JOIN contract t ON t.id=l.contract_id AND l.contract_id<>0 AND t.del_flg=0
		 WHERE l.del_flg=0 ".$dWhere."
		 ORDER BY l.application_date DESC,id DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query($dSql) or die('query error'.$GLOBALS['mysqldb']->error);

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

// 検索パラメータ------------------------------------------------------------------------
$param = '&keyword='.$_POST['keyword'].
		 '&contract_status='.$_POST['contract_status'].
		 '&support_status='.$_POST['support_status'].
		 '&cor_request='.$_POST['cor_request'].
		 '&line_max='.$_POST['line_max'].
		 '&start='.$dStart;

?>