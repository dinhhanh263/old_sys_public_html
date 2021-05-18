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
$table = "menu";

// データの変更
unset($_POST['shop_id']);
if( $_POST['action'] == "edit" && $_POST['id']) Input_Update_Data($table);

//表示順設定(default:登録最新順）
$order = " ORDER BY pid,rank DESC";
// データの取得

$dSql = "SELECT * FROM " . $table . $order ;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

//上位メニュー一覧
$pmenu_sql = $GLOBALS['mysqldb']->query( "select * from menu WHERE pid=0 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $pmenu_sql->fetch_assoc() ) {
    $gPmenuList[$result['id']] = $result['name'];
}
?>