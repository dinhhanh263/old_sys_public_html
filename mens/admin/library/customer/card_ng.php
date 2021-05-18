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

// $table = "contract";
$table = "contract_P";

$_POST['contract_date']=$_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d");
$_POST['contract_date2']=$_POST['contract_date2'] ? $_POST['contract_date2'] : ($_POST['contract_date'] ? $_POST['contract_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['contract_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['contract_date']." +1day"));

// データ変更-----------------------------------------------------

if( $_REQUEST['action'] == "ok" && $_REQUEST['id'] >= 1 ){
	if($_REQUEST['digicat_ng_flg']){
		$sql = "UPDATE customer SET digicat_ng_flg = 0,edit_date='".date('Y-m-d H:i:s')."'";
		$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
		$dRes = $GLOBALS['mysqldb']->query($sql);
	}
	if($_REQUEST['nextpay_end_ng_flg']){
		$sql = "UPDATE customer SET nextpay_end_ng_flg = 0,edit_date='".date('Y-m-d H:i:s')."'";
		$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
		$dRes = $GLOBALS['mysqldb']->query($sql);
	}
	if($_REQUEST['nextpay_op_ng_flg']){
		$sql = "UPDATE customer SET nextpay_op_ng_flg = 0,edit_date='".date('Y-m-d H:i:s')."'";
		$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
		$dRes = $GLOBALS['mysqldb']->query($sql);
	}

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
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " OR c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " OR c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND  tp.contract_date>='".$_POST['contract_date']."'";
$dWhere .= " AND  tp.contract_date<='".$_POST['contract_date2']."'";
if($_POST['search_shop_id'] !=0) $dWhere .= " AND  tp.shop_id='".($_POST['search_shop_id'])."'";
if( $_POST['status'] ) $dWhere .= " AND tp.status = '".addslashes($_POST['status'])."'";

$from = "FROM contract_P AS tp,customer AS c WHERE c.id=tp.customer_id AND tp.del_flg =0 AND c.del_flg =0";
if( $_REQUEST['debit_type'] && $_REQUEST['card_ng']) {
	if( $_REQUEST['debit_type']==1) $from .= " AND c.digicat_ng_flg=".$_POST['card_ng'];
	if( $_REQUEST['debit_type']==2) $from .= " AND c.nextpay_end_ng_flg=".$_POST['card_ng'];
	if( $_REQUEST['debit_type']==3) $from .= " AND c.nextpay_op_ng_flg=".$_POST['card_ng'];
}elseif($_REQUEST['debit_type']){
	if( $_REQUEST['debit_type']==1) $from .= " AND c.digicat_ng_flg<>0";
	if( $_REQUEST['debit_type']==2) $from .= " AND c.nextpay_end_ng_flg<>0";
	if( $_REQUEST['debit_type']==3) $from .= " AND c.nextpay_op_ng_flg<>0";
}elseif($_REQUEST['card_ng']){
	 $from .= " AND (c.digicat_ng_flg=".$_POST['card_ng']." || c.nextpay_end_ng_flg=".$_POST['card_ng']." || c.nextpay_op_ng_flg=".$_POST['card_ng']." )";
}else{
	$from .= " AND (c.digicat_ng_flg<>0 || c.nextpay_end_ng_flg<>0 || c.nextpay_op_ng_flg<>0 )";
}

// データの取得-----------------------------------------------------

$dSql = "SELECT count(c.id) ".$from ;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(c.id) ".$from.$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT tp.*,c.no as no,c.name as name,c.name_kana as name_kana,c.tel as tel,c.digicat_ng_flg, c.nextpay_end_ng_flg,c.nextpay_op_ng_flg,c.reg_date as reg_date ".$from.$dWhere." order by tp.contract_date LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト-----------------------------------------------------

$shop_list=getDatalist_shop("▶全店舗");

//courseリスト
$course_list  = getDatalistMens("course");

?>