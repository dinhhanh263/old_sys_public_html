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

//テーブル設定
$table = "adcode";

// データの変更
if( $_POST['mode'] == "issue" && $_POST[checkboxName]) {
	foreach($_POST[checkboxName] as $id=>$vals)
	{
		$sql = "UPDATE " . $table . " SET status=1" . " WHERE id = " . addslashes($id);
		$rtn = $GLOBALS['mysqldb']->query($sql);
	}
}

// データの取得
$dSql = "SELECT * FROM " . $table . " WHERE status<>1 ORDER BY agent_id,id DESC";
$dRtn = $GLOBALS['mysqldb']->query( $dSql );

$agent = $GLOBALS['mysqldb']->query( "SELECT * FROM agent" );
?>