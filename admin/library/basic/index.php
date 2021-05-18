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
$table = "basic";
unset($_POST['shop_id']);

// データの変更----------------------------------------------------------------------------
if( $_POST['action'] == "edit" && $_POST['id']) Input_Update_Data($table);

//表示順設定(default:登録最新順）-----------------------------------------------------------
$order = " ORDER BY name DESC";

// データの取得----------------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . $order ;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

?>