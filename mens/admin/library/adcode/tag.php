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

$table = "tag";

// データの仮削除----------------------------------------------------------------------------

if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] >= 1 ){
	$sql = "UPDATE ".$table." SET del_flg = 1,edit_date='".date("Y-m-d H:i:s") ."'";
	$sql .= " WHERE id = '".addslashes($_REQUEST['id'])."'";
	$dRes = $GLOBALS['mysqldb']->query($sql);
	if( $dRes )	$gMsg = 'データの削除が完了しました。';
	else		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定----------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = 20;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定----------------------------------------------------------------------------

$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere = " and ( ";
	$dWhere .= "  name LIKE '%".addslashes( $_POST['keyword'] )."%'";
	
	$dWhere .= " ) ";
}
if( $_POST['status'] ) $dWhere .= " AND status = '".addslashes($_POST['status'])."'";

// データの取得----------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM ".$table. " WHERE del_flg = 0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg = 0".$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0".$dWhere." ORDER BY name  LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

?>