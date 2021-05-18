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

$table = "shop";

// 画像の取り消し------------------------------------------------------------------------

if( $_POST['mode'] == "delete_image" && $_POST['id'] && $_POST['image_type']){
	$sql = "UPDATE ".$table." SET ".$_POST['image_type']."=''" . " WHERE id = " . addslashes($_POST['id']);
	$rtn = $GLOBALS['mysqldb']->query($sql) or die('query error'.$GLOBALS['mysqldb']->error);
	
// 編集------------------------------------------------------------------------

}elseif( $_POST['action'] == "edit" ) {
	//動画、画像アップ
	if( !empty($_FILES) ){
		foreach($_FILES as $key => $val){
			$file_name = str_replace("up_","",$key);
			if(move_uploaded_file( $_FILES[$key]['tmp_name'] , IMG_UPLOAD_DIR . $_FILES[$key]['name'] )){
				$_POST[$file_name] = $_FILES[$key]['name'];
			}
		}
	}

	$data_ID = ($_POST['id'] != "") ? Input_Update_Data($table) : $data_ID = Input_Data($table);
	if( $data_ID ) 	header( "Location: ./");
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
}

// 詳細を取得------------------------------------------------------------------------

if( $_POST['id'] != "" )  $data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");

?>
