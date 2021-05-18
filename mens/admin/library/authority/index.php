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

$gShowStatus = 	array( 0 => "稼働" , 		1 => "非稼働" );

// テーブル設定
$table = "authority";
unset($_POST['shop_id']);

// 新規or編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit") {
	if($_POST['id'] != ""){
		Input_Update_Data($table);
		//従業員所、アカウント更新
		if($_POST['staff_id'])$GLOBALS['mysqldb']->query("update staff set auth_id='".$_POST['id']."',type='".$_POST['authority']."',login_id='".$_POST['login_id']."',password='".$_POST['password']."' where id='".$_POST['staff_id']."'");
	}else Input_Data($table) ;
}

// データの削除----------------------------------------------------------------------------

if( $_POST['action'] == "delete" && $_POST['id']){
	$dRes = Delete_Data2($table);
	if( $dRes ) $gMsg = 'データの削除が完了しました。';
	else 		$gMsg = '何も削除しませんでした。';
}

// 表示ページ設定----------------------------------------------------------------------------

$dStart = 0;
$dLine_Max = 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定----------------------------------------------------------------------------

if( $_POST['keyword'] != "" ){
	$dWhere = " WHERE ";
	$dWhere .= "  loging_id LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= "  or id LIKE '%".addslashes( $_POST['keyword'] )."%'";
}

// データの取得----------------------------------------------------------------------------

$dSql = "SELECT count(*) FROM " . $table;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql );
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . $dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY login_id desc LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

?>