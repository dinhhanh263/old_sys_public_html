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
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

$table = "course";
unset($_POST['shop_id']);

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	if(!$_POST['old_flg']) $_POST['old_flg'] = 0;   // 旧コース
	if(!$_POST['zero_flg']) $_POST['zero_flg'] = 0; // 通いホーダイコース
	if(!$_POST['new_flg']) $_POST['new_flg'] = 0;   // 新月額フラグ
	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	if( $data_ID ) 	header( "Location: ./");
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>
