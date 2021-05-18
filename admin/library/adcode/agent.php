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


//テーブル設定
$table = "agent";

// データの新規登録----------------------------------------------------------------------------
if( $_POST['action'] == "new" )	Input_New_Agent();

// データの変更
if( $_POST['action'] == "update" && $_POST['id']) Input_Update_Agent();

// データの仮削除
if( $_REQUEST['action'] == "delete" && $_REQUEST['id'] ){
	$dRes = Delete_Data2($table);
	if( $dRes ) $gMsg = 'データの削除が完了しました。';
	else $gMsg = '何も削除しませんでした。';
}

//代理店リスト----------------------------------------------------------------------------
$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent WHERE pid='' ORDER BY name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $agent->fetch_assoc() ) {
	$gAgent[$list['id']] = $list['name'];
}

// 表示ページ設定----------------------------------------------------------------------------
$dStart = 0;
$dLine_Max = 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定----------------------------------------------------------------------------

if( $_POST['keyword'] != "" ){
	$dWhere = " AND ( ";
	$dWhere .= "  name LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= "  or id LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

// データの取得----------------------------------------------------------------------------
$dSql = "SELECT count(*) FROM " . $table. " WHERE del_flg=0";
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row()[0];

$dSql = "SELECT count(id) FROM " . $table . " WHERE del_flg=0". $dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . " WHERE del_flg=0". $dWhere." ORDER BY name LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

?>