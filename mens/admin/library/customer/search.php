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

$table = "customer";

$_POST['reg_date']=$_POST['reg_date'] ? $_POST['reg_date'] : date("Y-m-d");
$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : date("Y-m-d");


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
$dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " or no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or address LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

if($_POST['serach_shop_id'])$dWhere .= " AND  shop_id='".$_POST['serach_shop_id'] ."'";
if( $_POST['adcode'] !=0 ) $dWhere .= " AND adcode = '".addslashes($_POST['adcode'])."'";
$dWhere .= " AND no LIKE '%".str_replace("　","",mb_convert_kana($_POST['no'],"SKV", "UTF-8") )."%'";
$dWhere .= " AND replace(name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['name'],"SKV", "UTF-8") )."%'";
$dWhere .= " AND replace(name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['name_kana'],"SKV", "UTF-8") )."%'";
$dWhere .= " AND replace(tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['tel']) )."%'";
$dWhere .= " AND mail LIKE '%".addslashes( $_POST['mail'] )."%'";
$dWhere .= " AND address LIKE '%".addslashes( $_POST['address'] )."%'";

if( $_POST['status'] ) $dWhere .= " AND status = '".addslashes($_POST['status'])."'";

// データの取得------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0".$dWhere." ORDER BY reg_date DESC LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();


//courseリスト
$course_list  = getDatalistMens("course");

// ADリスト
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by name" );
$adcode_list[0] = "全媒体";
while ( $result = $adcode_sql->fetch_assoc() ) {
	$adcode_list[$result['id']] = $result['name'];
}

if($_POST['shop_id']) $param .= "&shop_id=".$_POST['shop_id'];
if($_POST['route']) $param .= "&route=".$_POST['route'];
if($_POST['hope_date']) $param .= "&hope_date=".$_POST['hope_date'];
if($_POST['hoipe_time']) $param .= "&hoipe_time=".$_POST['hoipe_time'];
if($_POST['type']) $param .= "&type=".$_POST['type'];
if($_POST['room_id']) $param .= "&room_id=".$_POST['room_id'];

?>