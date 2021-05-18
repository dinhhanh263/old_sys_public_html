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

$table = "reservation";
$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$_POST['hope_date2']=$_POST['hope_date2'] ? $_POST['hope_date2'] : ($_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['hope_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date']." +1day"));


// 新規キャンセル：区分->キャンセル(type=3)、契約していない->reg_flg=0

// データの仮削除------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
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
	$dWhere .= "  replace(customer.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or customer.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(customer.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or customer.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}
$dWhere .= " AND reservation.hope_date>='".$_POST['hope_date']."'";
$dWhere .= " AND reservation.hope_date<='".$_POST['hope_date2']."'";
if($_POST['shop_id'])$dWhere .= " AND  reservation.shop_id='".$_POST['shop_id'] ."'";
if( $_POST['status'] ) $dWhere .= " AND reservation.status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

// $dSql = "SELECT count(*) FROM ".$table. " WHERE type=3 and reg_flg=0 and del_flg = 0";
// 3.キャンセル、21.カウンセリング/キャンセル、22.トリートメント/キャンセル　もキャンセル一覧に追加 2017/04/12 add by shimada
// $dSql = "SELECT count(*) FROM ".$table. " WHERE type IN(3,21,22) and reg_flg=0 and del_flg = 0";
// $dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
// $dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(reservation.id) FROM " . $table . ",customer WHERE reservation.customer_id=customer.id and reservation.type IN(3,21,22) and customer.reg_flg=0 and reservation.del_flg=0 ".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT reservation.*,customer.no as no,customer.name as name,customer.tel as tel FROM " . $table . ",customer WHERE reservation.customer_id=customer.id and reservation.type IN(3,21,22) and customer.reg_flg=0 AND reservation.del_flg = 0".$dWhere." ORDER BY reservation.hope_date DESC,reservation.hope_time DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();


// リスト
$course_list  = getDatalistMens("course");
?>