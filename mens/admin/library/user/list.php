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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

if($_REQUEST['status_search']=='')$_REQUEST['status_search']='-';

//テーブル設定----------------------------------------------------------------------------------------
$table = "user";


// データの削除----------------------------------------------------------------------------------------
if( $_REQUEST['mode'] == "delete" && $_REQUEST['id']){
	$dRes = Delete_Data2($table);
	if( $dRes ){
		$gMsg = 'データの削除が完了しました。';
	}else{
		$gMsg = '何も削除しませんでした。';
	}
}

// 会員状態の変更=>申込分類の変更----------------------------------------------------------------------------------------
if( $_REQUEST['mode'] == "status_edit" && $_REQUEST['id']){
	//配信状態反転
	$status = $_REQUEST['status'];
	// DB更新
	$sql = "UPDATE ".$table." SET type = '" . $status."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$GLOBALS['mysqldb']->query($sql);
}

// 配信状態の変更----------------------------------------------------------------------------------------
if( $_POST['mode'] == "edit" && $_POST['id']){
	//配信状態反転
	$send_status = $_POST['err_flg'] ? 0 : 1;
	// DB更新
	$sql = "UPDATE ".$table." SET err_flg = '" . $send_status."'";
	$sql .= " WHERE id = '".addslashes($_POST['id'])."'";
	$GLOBALS['mysqldb']->query($sql);
}

// 表示ページ設定----------------------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = $_REQUEST['line_max'] ? $_REQUEST['line_max'] : 20;
if( is_numeric( $_REQUEST['start'] ) && $_REQUEST['start'] >= 0 && $_REQUEST['start'] < 99999 ){
	$dStart = $_REQUEST['start'];
}

// 期間指定----------------------------------------------------------------------------------------
if($_REQUEST['limit_year1'] != "" && $_REQUEST['limit_month1'] != "" && $_REQUEST['limit_day1'] != "" && $_REQUEST['limit_year2'] != "" && $_REQUEST['limit_month2'] != "" && $_REQUEST['limit_day2'] != ""){
	$limit1 = $_REQUEST['limit_year1'].'-'.$_REQUEST['limit_month1'].'-'.$_REQUEST['limit_day1'];
	$limit2 = $_REQUEST['limit_year2'].'-'.$_REQUEST['limit_month2'].'-'.$_REQUEST['limit_day2'];
}else{
	$limit1 = date("Y").'-01'.'-01';
	//$limit1 = date("Y").'-'.date("m").'-01';
	//$limit2 = date("Y").'-'.(date("m") + 1).'-01';
	$limit2_year  = date("Y",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_month = date("m",mktime(0, 0, 0, date("m") +1 , date("d"), date("Y")));
	$limit2_day = '0';
	//$limit2 = $limit2_year.'-'.$limit2_month.'-'.$limit2_day;
	$limit2 = date("Y-m-d", mktime(0, 0, 0, $limit2_month, $limit2_day, $limit2_year));
}
$dateLimit = " TO_DAYS(reg_date) >= TO_DAYS('".$limit1."') AND TO_DAYS(reg_date) <= TO_DAYS('".$limit2."')";

//表示順設定(default:登録最新順）----------------------------------------------------------------------------------------
if(!$_REQUEST['order']){
	$_REQUEST['order'] = "reg_date";
	$_REQUEST['seq'] = 1;
}
$dOrder = " ORDER BY ".$_REQUEST['order'];
$dSeq = $_REQUEST['seq'] ? " DESC " : " ASC ";

$order = $dOrder . $dSeq .",reg_date DESC ";

// 検索条件の設定----------------------------------------------------------------------------------------
if( $_REQUEST['keyword'] != "" ){
	$dWhere = " and ( ";
	$dWhere .= "  name LIKE '%".addslashes( $_REQUEST['keyword'] )."%'";
	$dWhere .= "  or id LIKE '%".addslashes( $_REQUEST['keyword'] )."%'";
	$dWhere .= "  or inquiry LIKE '%".addslashes( $_REQUEST['keyword'] )."%'";
	$dWhere .= "  or mail_address LIKE '%".addslashes( $_REQUEST['keyword'] )."%'";
	$dWhere .= "  or adcode ='".addslashes( $_REQUEST['keyword'] )."'";
	$dWhere .= " ) ";
}
if( $_REQUEST['flg'] )$dWhere .= " and  reg_flg=".$_REQUEST['flg'];
if( $_REQUEST['if_phone'] )$dWhere .= " and  phone<>''";
if( $_REQUEST['if_mail'] )$dWhere .= " and  mail<>''";
if( $_REQUEST['status_search']<>'' && $_REQUEST['status_search']<>'-')$dWhere .= " and  status=".$_REQUEST['status_search'];

// データの取得----------------------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM " . $table . " WHERE reg_flg!=0 ";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE reg_flg!=0 and " . $dateLimit .$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . " WHERE reg_flg!=0 and " . $dateLimit . $dWhere . $order . " LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

$hidden='<input name="keyword" type="hidden" value="'.$_REQUEST['keyword'].'">
		<input name="start" type="hidden" value="'.$_REQUEST['start'].'">
		<input name="status_search" type="hidden" value="'.$_REQUEST['status_search'].'">
		<input name="order" type="hidden" value="'.$_REQUEST['order'].'">
		<input name="seq" type="hidden" value="'.$_REQUEST['seq'].'">
		<input name="limit1" type="hidden" value="'.$limit1.'">
		<input name="limit2" type="hidden" value="'.$limit2.'">
		<input name="limit_year1" type="hidden" value="'.$_REQUEST['limit_year1'].'">
		<input name="limit_month1" type="hidden" value="'.$_REQUEST['limit_month1'].'">
		<input name="limit_day1" type="hidden" value="'.$_REQUEST['limit_day1'].'">
		<input name="limit_year2" type="hidden" value="'.$_REQUEST['limit_year2'].'">
		<input name="limit_month2" type="hidden" value="'.$_REQUEST['limit_month2'].'">
		<input name="limit_day2" type="hidden" value="'.$_REQUEST['limit_day2'].'">
		<input name="flg" type="hidden" value="'.$_REQUEST['flg'].'">
		<input name="if_phone" type="hidden" value="'.$_REQUEST['if_phone'].'">
		<input name="if_mail" type="hidden" value="'.$_REQUEST['if_mail'].'">';

?>
