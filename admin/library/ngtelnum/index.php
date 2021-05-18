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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

//テーブル設定
$table = "ngtelnum";

//設定状態
$ngStatus = array( 0 => "禁止" , 1 => "許可") ;
$ngType   = array( 0 => "全部" , 1 => "一部") ;

// データの新規登録
if( $_POST['action'] == "new" )	Input_Data($table);

// データの変更
if( $_POST['action'] == "update" && $_POST['id']) Input_Update_Data($table);

// データの削除
if( $_POST['action'] == "delete" && $_POST['id']){
			
	$dRes = Delete_Data2($table);
	if( $dRes ){
		$gMsg = 'データの削除が完了しました。';
	}else{
		$gMsg = '何も削除しませんでした。';
	}
}

// 表示ページ設定
$dStart = 0;
$dLine_Max = 50;
if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
	$dStart = $_POST['start'];
}

// 検索条件の設定

if( $_POST['keyword'] != "" ){
	$dWhere = " WHERE ";
	$dWhere .= "  name LIKE '%".addslashes( $_POST['keyword'] )."%'";
}

// データの取得
$dSql = "SELECT count(*) FROM " . $table;
$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$dAll_Cnt = $dRtn1->fetch_row();

$dSql = "SELECT count(id) FROM " . $table . $dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

$dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY name LIMIT ".$dStart.",".$dLine_Max;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

?>