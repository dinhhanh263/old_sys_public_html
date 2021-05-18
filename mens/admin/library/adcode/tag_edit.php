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
unset($_POST['shop_id']);

//編集--------------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
	$_POST['tag'] = str_replace("'", '##"##', $_POST['tag'] );
	//$_POST['tag'] = str_replace('""', '"', $_POST['tag'] );
	if(!$_POST['id']) $_POST['reg_date'] = date("Y-m-d H:i:s");
	else $_POST['edit_date'] = date("Y-m-d H:i:s");
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	if( $data_ID ) 	header( "Location: ./tag.php");
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得----------------------------------------------------------------------------

if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>
